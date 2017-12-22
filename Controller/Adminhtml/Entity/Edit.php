<?php

namespace Oggetto\Entities\Controller\Adminhtml\Entity;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Oggetto\Entities\Controller\Adminhtml\Entity;

class Edit extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Oggetto_Entities::entities';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;
    /**
     * @var \Oggetto\Entities\Model\Data\EntityFactory
     */
    private $entityDataFactory;
    private $entityRepository;
    private $coreRegistry;

    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Oggetto\Entities\Api\Data\EntityInterfaceFactory $entityDataFactory,
        \Oggetto\Entities\Api\EntityRepositoryInterface $entityRepository,
        Action\Context $context)
    {

        $this->resultPageFactory = $resultPageFactory;
        $this->entityDataFactory = $entityDataFactory;
        $this->entityRepository = $entityRepository;
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $entity = $this->initEntity();
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$entity) {
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
        try {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->setActiveMenu('Oggetto_Entities::entities');
            $resultPage->addBreadcrumb(__('Catalog'), __('Catalog'));
            $resultPage->addBreadcrumb(__('Entities'), __('Entities'));
            $resultPage->getConfig()->getTitle()->prepend(__('Entities'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Exception occurred during entity loading'));
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
        $title = $entity->getEntityId() ? __('Entity "%1"', $entity->getName()) : __('New Entity');
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }

    private function initEntity()
    {
        $id = $this->getRequest()->getParam('entity_id');
        try {
            $entity = $id ? $this->entityRepository->getById($id) : $this->entityDataFactory->create();
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('This entity no longer exists.'));
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            return false;
        } catch (InputException $e) {
            $this->messageManager->addErrorMessage(__('This entity no longer exists.'));
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            return false;
        }
        $this->coreRegistry->register('current_entity', $entity);
        return $entity;
    }
}
