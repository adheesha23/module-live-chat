<?php

namespace Aligent\Chat\Model;

use Aligent\Chat\Api\ConfigurationInterface;
use Aligent\Chat\Logger\LiveChatLogger;
use Magento\Backend\Model\Auth\Session as AdminSession;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Configuration class that manages settings and cache related to LiveChat functionality.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var WriterInterface
     */
    private WriterInterface $configWriter;

    /**
     * @var TypeListInterface
     */
    private TypeListInterface $cacheTypeList;

    /**
     * @var AdminSession
     */
    private AdminSession $adminSession;

    /**
     * @var LiveChatLogger
     */
    private LiveChatLogger $logger;

    const string CONFIG_PATH_GENERAL_ENABLED = 'livechat/general/enabled';
    const string CONFIG_PATH_GENERAL_LICENCE = 'livechat/general/license';
    const string CONFIG_PATH_GENERAL_GROUP = 'livechat/general/groups';
    const string CONFIG_PATH_GENERAL_PARAMS = 'livechat/general/params';
    const string CONFIG_SCOPE_STORE = ScopeInterface::SCOPE_STORE;
    private const string DEFAULT_SCOPE = ScopeConfigInterface::SCOPE_TYPE_DEFAULT;
    private const int DEFAULT_SCOPE_ID = 0;

    /**
     * Constructor for initializing the configuration and cache mechanisms.
     *
     * @param ScopeConfigInterface $scopeConfig Configuration scope for retrieving settings.
     * @param WriterInterface $configWriter Writer interface for updating configuration.
     * @param TypeListInterface $cacheTypeList List of cache types for clearing cache.
     * @param AdminSession $adminSession
     * @param LiveChatLogger $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface      $configWriter,
        TypeListInterface    $cacheTypeList,
        AdminSession         $adminSession,
        LiveChatLogger           $logger
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->cacheTypeList = $cacheTypeList;
        $this->adminSession = $adminSession;
        $this->logger = $logger;
    }

    /**
     * Checks whether the general configuration is enabled in the store's configuration scope.
     *
     * @return bool True if the configuration is enabled, false otherwise.
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::CONFIG_PATH_GENERAL_ENABLED,
            self::CONFIG_SCOPE_STORE
        );
    }

    /**
     * Sets the form data for live chat configurations.
     *
     * @param array $liveChatFormData The form data containing live chat configuration settings.
     *
     * @return void
     */
    public function setLiveChatConfigurationsFormData(array $liveChatFormData): void
    {
        $liveChatLicenseNumber = $liveChatFormData['livechat_license_number'] ?? '';
        $liveChatGroups = $liveChatFormData['livechat_groups'] ?: 0;
        $liveChatParams = $liveChatFormData['livechat_params'] ?? '';

        $this->setLiveChatConfigurations($liveChatLicenseNumber, $liveChatGroups, $liveChatParams);
        $this->logLiveChatConfigChange($liveChatLicenseNumber, $liveChatGroups, $liveChatParams);
        $this->cleanConfigCache();
    }

    /**
     * Set the configurations for live chat based on the provided parameters.
     *
     * @param string $licenseNumber The license number for the live chat service.
     * @param string $groups The groups to configure in the live chat.
     * @param string $params Additional parameters for live chat configuration.
     * @return void
     */
    private function setLiveChatConfigurations(string $licenseNumber, string $groups, string $params): void
    {
        $this->setLiveChatLicense($licenseNumber);
        $this->setLiveChatGroup($groups);
        $this->setLiveChatParams($params);
    }

    /**
     * Logs changes made to the live chat configuration.
     *
     * This method collects the current admin username, the timestamp, and various live chat configuration
     * details, then logs this data using the live chat logger.
     *
     * @param string $licenseNumber
     * @param string $groups
     * @param string $params
     * @return void
     */
    private function logLiveChatConfigChange(string $licenseNumber, string $groups, string $params): void
    {
        $adminUsername = $this->getLoggedInAdminUsername();
        $dataToLog = [
            'admin_username' => $adminUsername,
            'timestamp' => date('Y-m-d H:i:s'),
            'livechat_license_number' => $licenseNumber,
            'livechat_groups' => $groups,
            'livechat_params' => $params,
        ];

        $this->logger->logData($dataToLog);
    }

    /**
     * Set live chat license configuration value
     *
     * @param string $licenseNumber
     * @return void
     */
    public function setLiveChatLicense(string $licenseNumber): void
    {
        $this->saveConfigData(self::CONFIG_PATH_GENERAL_LICENCE, $licenseNumber);
    }

    /**
     * Set live chat group configuration
     *
     * @param mixed $groups
     * @return void
     */
    public function setLiveChatGroup(mixed $groups): void
    {
        $this->saveConfigData(self::CONFIG_PATH_GENERAL_GROUP, $groups);
    }

    /**
     * Set live chat parameters config value
     *
     * @param string $params
     * @return void
     */
    public function setLiveChatParams(string $params): void
    {
        $this->saveConfigData(self::CONFIG_PATH_GENERAL_PARAMS, $params);
    }

    /**
     * Saves configuration data for a given path and value.
     *
     * @param string $path The configuration path where the value will be saved.
     * @param mixed $value The value to be saved at the specified path.
     * @return void
     */
    private function saveConfigData(string $path, mixed $value): void
    {
        $this->configWriter->save($path, $value, self::DEFAULT_SCOPE, self::DEFAULT_SCOPE_ID);
    }

    /**
     * Retrieves the live chat license key.
     *
     * @return string The live chat license key.
     */
    public function getLiveChatLicense(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_PATH_GENERAL_LICENCE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieves the live chat group configuration value.
     *
     * @return string The configured live chat group for the current store scope.
     */
    public function getLiveChatGroup(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_PATH_GENERAL_GROUP,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get advanced params config value
     *
     * @return string
     */
    public function getLiveChatParams(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_PATH_GENERAL_PARAMS,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Cleans the configuration cache.
     *
     * @return void
     */
    public function cleanConfigCache(): void
    {
        $this->cacheTypeList->cleanType('config');
    }

    /**
     * Retrieves the username of the currently logged-in admin.
     *
     * @return string|null The username of the logged-in admin, or null if no admin is logged in.
     */
    private function getLoggedInAdminUsername(): ?string
    {
        $user = $this->adminSession->getUser();
        return $user?->getUsername();
    }


}
