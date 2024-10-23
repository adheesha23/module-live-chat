<?php

namespace Aligent\Chat\Controller\Adminhtml\Edit;

use Aligent\Chat\Api\ConfigurationInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;

class Save extends Action
{
    private ConfigurationInterface $configInterface;
    protected $messageManager;
    protected LoggerInterface $logger;

    /**
     * @param Context $context
     * @param ConfigurationInterface $configInterface
     * @param ManagerInterface $messageManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context                $context,
        ConfigurationInterface $configInterface,
        ManagerInterface       $messageManager,
        LoggerInterface        $logger
    )
    {
        $this->configInterface = $configInterface;
        $this->messageManager = $messageManager;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Executes the live chat settings update process. Retrieves POST data from the request, validates it,
     * and updates live chat configurations accordingly. Displays success or error messages based on the result.
     *
     * @return ResponseInterface Redirects to the edit page after processing.
     */
    public function execute(): ResponseInterface
    {
        try {
            $postData = $this->getRequest()->getPostValue();

            if ($this->isPostDataAvailable($postData) && $this->isValidLiveChatData($postData)) {
                $this->updateLiveChatSettings($postData);
                $this->showSuccessMessage();
            } else {
                $this->showErrorMessage('Invalid live chat data.');
            }
        } catch (LocalizedException $e) {
            $this->showErrorMessage($e->getMessage());

        } catch (Exception $e) {
            // Log the exception
            $this->logger->error('Error executing save live chat settings', ['exception' => $e]);
            $this->showErrorMessage('Something went wrong. Please try again.');
        }

        return $this->_redirect('*/edit/');
    }

    protected function isPostDataAvailable(array $postData): bool
    {
        return !empty($postData);
    }

    /**
     * Validates the live chat data.
     *
     * @param array $data The live chat data to validate.
     * @return bool Returns true if all necessary fields are present and non-empty.
     * @throws LocalizedException if any required field is empty.
     */
    protected function isValidLiveChatData(array $data): bool
    {
        $this->validateNotEmpty($data['livechat_license_number'], 'Livechat License Number');
        $this->validateNotEmpty($data['livechat_groups'], 'Livechat Groups');
        $this->validateNotEmpty($data['livechat_params'], 'Livechat Params');

        return true;
    }

    /**
     * Checks if a field is not empty and throws an exception if it is.
     *
     * @param string $value The value to check.
     * @param string $fieldName The name of the field.
     * @throws LocalizedException if the field is empty.
     */
    private function validateNotEmpty(string $value, string $fieldName): void
    {
        if (empty(trim($value))) {
            throw new LocalizedException(__("Please Enter the $fieldName and try again."));
        }
    }

    private function updateLiveChatSettings(array $data): void
    {
        $this->configInterface->setLiveChatConfigurationsFormData($data);
    }

    private function showSuccessMessage(): void
    {
        $this->messageManager->addSuccessMessage(__('Live Chat Configurations have been updated'));
    }

    private function showErrorMessage(string $message): void
    {
        $this->messageManager->addErrorMessage(__($message));
    }
}
