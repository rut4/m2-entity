<?php

namespace Oggetto\Entities\Controller\Adminhtml\Entity;

use Magento\Backend\App\Action;

class Index extends \Magento\Backend\App\Action
{
    private $resultPageFactory;

    public function __construct(\Magento\Framework\View\Result\PageFactory $resultPageFactory, Action\Context $context)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Oggetto_Entities::entities');
        $resultPage->addBreadcrumb(__('Catalog'), __('Catalog'));
        $resultPage->addBreadcrumb(__('Entities'), __('Entities'));
        $resultPage->getConfig()->getTitle()->prepend(__('Entities'));
        return $resultPage;
    }
}
