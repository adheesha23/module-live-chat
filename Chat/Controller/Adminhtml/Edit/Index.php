<?php
namespace Aligent\Chat\Controller\Adminhtml\Edit;

use Aligent\Chat\Api\ConfigurationInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected PageFactory $resultPageFactory;
    private ConfigurationInterface $liveChatConfigInterface;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ConfigurationInterface $liveChatConfigInterface
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->liveChatConfigInterface = $liveChatConfigInterface;
    }

    /**
     * Executes the main function to generate the result page
     * for managing default chat configurations.
     *
     * @return Page The created result page.
     * @throws NotFoundException If the live chat is not enabled.
     */
    public function execute(): Page
    {
        if (!$this->liveChatConfigInterface->isEnabled()) {
            throw new NotFoundException(__('Page not found.'));
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Default Chat Configurations'));
        return $resultPage;
    }
}
