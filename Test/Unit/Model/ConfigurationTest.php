<?php


namespace Aligent\Chat\Test\Unit\Model;

use Aligent\Chat\Logger\LiveChatLogger;
use Aligent\Chat\Model\Configuration;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Backend\Model\Auth\Session as AdminSession;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Magento\User\Model\User;

class ConfigurationTest extends TestCase
{
    /**
     * @var Configuration
     */
    private Configuration $configuration;

    /**
     * @var MockObject|ScopeConfigInterface
     */
    private ScopeConfigInterface|MockObject $scopeConfigMock;

    /**
     * @var MockObject|WriterInterface
     */
    private WriterInterface|MockObject $configWriterMock;

    /**
     * @var MockObject|TypeListInterface
     */
    private TypeListInterface|MockObject $cacheTypeListMock;

    /**
     * @var AdminSession|MockObject
     */
    private AdminSession|MockObject $adminSessionMock;

    /**
     * @var LiveChatLogger|MockObject
     */
    private LiveChatLogger|MockObject $liveChatLoggerMock;

    const string EXPECTED_SCOPE = 'default';
    const int EXPECTED_SCOPE_ID = 0;

    /**
     * Initializes the test environment by creating mock objects
     * for dependencies required by the Configuration class.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->scopeConfigMock = $this->createMock(ScopeConfigInterface::class);
        $this->configWriterMock = $this->createMock(WriterInterface::class);
        $this->cacheTypeListMock = $this->createMock(TypeListInterface::class);
        $this->adminSessionMock = $this->getMockBuilder(AdminSession::class)
            ->disableOriginalConstructor()
            ->addMethods(['getUser'])
            ->getMock();
        $this->liveChatLoggerMock = $this->getMockBuilder(LiveChatLogger::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->configuration = new Configuration(
            $this->scopeConfigMock,
            $this->configWriterMock,
            $this->cacheTypeListMock,
            $this->adminSessionMock,
            $this->liveChatLoggerMock
        );
    }

    /**
     * Tests the isEnabled method of the Configuration class to ensure it returns true
     * when the corresponding configuration flag is set to true.
     *
     * @return void
     */
    public function testIsEnabled(): void
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->with(Configuration::CONFIG_PATH_GENERAL_ENABLED, ScopeInterface::SCOPE_STORE)
            ->willReturn(true);

        $this->assertTrue($this->configuration->isEnabled());
    }

    /**
     * Tests the setLiveChatConfigurationsFormData method by ensuring that
     * the configWriterMock 'save' method is called with the correct arguments
     * for each form data field, the logger records the data, and cache clean
     * is triggered appropriately.
     *
     * @return void
     */
    public function testSetLiveChatConfigurationsFormData(): void
    {
        $formData = [
            'livechat_license_number' => '12345',
            'livechat_groups' => 'Support',
            'livechat_params' => 'param1'
        ];

        // Expect methods for updating configurations to be called with respective values
        $this->configWriterMock->expects($this->exactly(3))
            ->method('save')
            ->withConsecutive(
                [Configuration::CONFIG_PATH_GENERAL_LICENCE, '12345', ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0],
                [Configuration::CONFIG_PATH_GENERAL_GROUP, 'Support', ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0],
                [Configuration::CONFIG_PATH_GENERAL_PARAMS, 'param1', ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0]
            );

        // Expect logging
        $this->liveChatLoggerMock->expects($this->once())
            ->method('logData')
            ->with($this->isType('array'));

        // Expect cache clean to be called
        $this->cacheTypeListMock->expects($this->exactly(1))
            ->method('cleanType')
            ->withConsecutive(
                ['config']
            );

        $this->configuration->setLiveChatConfigurationsFormData($formData);
    }

    /**
     * Tests the setLiveChatLicense method by ensuring that the
     * configWriterMocks 'save' method is called with the correct arguments.
     *
     * @return void
     */
    public function testSetLiveChatLicense(): void
    {
        $licenseNumber = '12345';
        $expectedConfigPath = 'livechat/general/license';

        // Expect the save method to be called with correct arguments
        $this->configWriterMock->expects($this->once())
            ->method('save')
            ->with(
                $expectedConfigPath,
                $licenseNumber,
                self::EXPECTED_SCOPE,
                self::EXPECTED_SCOPE_ID
            );

        $this->configuration->setLiveChatLicense($licenseNumber);
    }

    /**
     * Tests the setLiveChatGroup method to ensure it correctly calls the config writer's 'save' method
     * with the expected parameters.
     *
     * @return void
     */
    public function testSetLiveChatGroup(): void
    {
        $groups = 'Sales, Support';
        $expectedConfigPath = 'livechat/general/groups';

        // Expect the save method to be called with correct arguments
        $this->configWriterMock->expects($this->once())
            ->method('save')
            ->with(
                $expectedConfigPath,
                $groups,
                self::EXPECTED_SCOPE,
                self::EXPECTED_SCOPE_ID
            );

        $this->configuration->setLiveChatGroup($groups);
    }

    /**
     * Tests the setLiveChatParams method to ensure it correctly calls the config writer's 'save' method
     * with the expected parameters.
     *
     * @return void
     */
    public function testSetLiveChatParams(): void
    {
        $params = 'key1=value1, key2=value2';
        $expectedConfigPath = 'livechat/general/params';

        // Expect the save method to be called with correct arguments
        $this->configWriterMock->expects($this->once())
            ->method('save')
            ->with(
                $expectedConfigPath,
                $params,
                self::EXPECTED_SCOPE,
                self::EXPECTED_SCOPE_ID
            );

        $this->configuration->setLiveChatParams($params);
    }

    /**
     * Tests the cacheCleanByTags method to ensure it correctly calls the cache type list's cleanType method
     * with the expected tags.
     *
     * @return void
     */
    public function testCleanConfigCache(): void
    {
        $this->cacheTypeListMock->expects($this->exactly(1))
            ->method('cleanType')
            ->withConsecutive(
                ['config']
            );

        $this->configuration->cleanConfigCache();
    }

    /**
     * Tests the logData method of the liveChatLogger to verify it logs the proper configuration changes
     * made by an admin user in the LiveChat configuration form.
     *
     * @return void
     */
    public function testLogLiveChatConfigChange(): void
    {
        // Mock the User object and its getUsername method
        $userMock = $this->createMock(User::class);
        $userMock->method('getUsername')->willReturn('admin');

        // Set expectation for getUser method in AdminSession mock
        $this->adminSessionMock->method('getUser')->willReturn($userMock);

        // Expect the logger to log the correct data
        $this->liveChatLoggerMock->expects($this->once())
            ->method('logData')
            ->with($this->callback(function ($data) {
                return $data['admin_username'] === 'admin'
                    && isset($data['timestamp'])
                    && $data['livechat_license_number'] === '12345'
                    && $data['livechat_groups'] === 'Support'
                    && $data['livechat_params'] === 'param1';
            }));

        // Call the method being tested
        $this->configuration->setLiveChatConfigurationsFormData([
            'livechat_license_number' => '12345',
            'livechat_groups' => 'Support',
            'livechat_params' => 'param1'
        ]);
    }

    /**
     * Tests the setLiveChatGroup method with a null value.
     *
     * @return void
     */
    public function testSetLiveChatGroupWithNullValue()
    {
        $nullGroups = null;
        $this->configuration->setLiveChatGroup($nullGroups);
        $this->assertTrue(true);
    }

    /**
     * Tests the setLiveChatParams method with an empty string value.
     *
     * @return void
     */
    public function testSetLiveChatParamsWithEmptyValue()
    {
        $emptyParams = '';
        $this->configuration->setLiveChatParams($emptyParams);
        $this->assertTrue(true);
    }
}
