<?php

namespace Oggetto\Entities\Controller\Adminhtml\Entity;

use Magento\Backend\App\Action;
use Magento\Cms\Controller\Adminhtml\Page\PostDataProcessor;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Oggetto\Entities\Api\Data\EntityInterface;

class Save extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Oggetto_Entities::entities';

    private $dataProcessor;
    private $entityRepository;
    private $entityDataFactory;
    private $dataObjectHelper;
    private $dataPersistor;
    private $coreRegistry;

    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Oggetto\Entities\Api\Data\EntityInterfaceFactory $entityDataFactory,
        \Oggetto\Entities\Api\EntityRepositoryInterface $entityRepository,
        DataPersistorInterface $dataPersistor,
        DataObjectHelper $dataObjectHelper,
        PostDataProcessor $dataProcessor,
        Action\Context $context)
    {
        $this->entityDataFactory = $entityDataFactory;
        $this->entityRepository = $entityRepository;
        $this->coreRegistry = $coreRegistry;
        $this->dataPersistor = $dataPersistor;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context);
        $this->dataProcessor = $dataProcessor;
    }


    /**
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        $data = $this->dataProcessor->filter($data);

        if (!$data['entity_id']) {
            $data['entity_id'] = null;
        }

        $entity = $this->initEntity();
        $this->dataObjectHelper->populateWithArray($entity, $data, EntityInterface::class);

        if (!$this->dataProcessor->validate($data)) {
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $entity->getEntityId(), '_current' => true]);
        }

        try {
            $this->entityRepository->save($entity);
            $this->messageManager->addSuccessMessage(__('You saved the entity.'));
            $this->dataPersistor->clear('entities_entity');
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $entity->getEntityId(), '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the page.'));
        }

        $this->dataPersistor->set('entities_entity', $data);
        return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
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
