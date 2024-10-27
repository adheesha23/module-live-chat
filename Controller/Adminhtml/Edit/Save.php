<?php

namespace Aligent\Chat\Controller\Adminhtml\Edit;

use Aligent\Chat\Api\ConfigurationInterface;
use Aligent\Chat\Service\LiveChatValidator;
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
    private LoggerInterface $logger;
    private LiveChatValidator $validator;

    /**
     * @param Context $context
     * @param LiveChatValidator $validator
     * @param ConfigurationInterface $configInterface
     * @param ManagerInterface $messageManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context                $context,
        LiveChatValidator      $validator,
        ConfigurationInterface $configInterface,
        ManagerInterface       $messageManager,
        LoggerInterface        $logger
    )
    {
        $this->validator = $validator;
        $this->configInterface = $configInterface;
        $this->messageManager = $messageManager;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Executes the action of saving live chat settings.
     *
     * @return ResponseInterface
     */
    public function execute(): ResponseInterface
    {
        try {
            $postData = $this->getRequest()->getPostValue();

            $this->validator->validate($postData);
            $this->configInterface->setLiveChatConfigurationsFormData($postData);
            $this->messageManager->addSuccessMessage(__('Live Chat Configurations have been updated'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

        } catch (Exception $e) {
            // Log the exception
            $this->logger->error('Error executing save live chat settings', ['exception' => $e]);
            $this->messageManager->addErrorMessage('Something went wrong. Please try again.');
        }

        return $this->_redirect('*/edit/');
    }
}
