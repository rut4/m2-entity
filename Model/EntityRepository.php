<?php

namespace Oggetto\Entities\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Oggetto\Entities\Api\Data\EntityInterface;
use Oggetto\Entities\Api\Data\EntityInterfaceFactory;
use Oggetto\Entities\Model\EntityFactory;
use Oggetto\Entities\Model\ResourceModel\Entity as EntityResource;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class EntityRepository implements \Oggetto\Entities\Api\EntityRepositoryInterface
{
    private $entityFactory;
    private $entityResource;
    private $entityDataFactory;
    private $dataObjectHelper;
    private $extensibleDataObjectConverter;

    public function __construct(
        EntityFactory $objectFactory,
        EntityResource $entityResource,
        EntityInterfaceFactory $entityDataFactory,
        DataObjectHelper $dataObjectHelper,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->entityFactory = $objectFactory;
        $this->entityResource = $entityResource;
        $this->entityDataFactory = $entityDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    public function save(EntityInterface $entity)
    {
        try {
            $entityData = $this->extensibleDataObjectConverter->toNestedArray($entity, [], EntityInterface::class);
            $entityModel = $this->entityFactory->create(['data' => $entityData])
                ->setDataChanges(true);
            $this->entityResource->save($entityModel);
            $entity->setEntityId($entityModel->getId());
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $entity;
    }

    public function getById($id)
    {
        $entity = $this->entityFactory->create();
        $this->entityResource->load($entity, $id);
        if (!$entity->getId()) {
            throw new NoSuchEntityException(__('Object with id "%1" does not exist.', $id));
        }
        $dataObject = $this->entityDataFactory->create();
        $this->dataObjectHelper->populateWithArray($dataObject, $entity->getData(), EntityInterface::class);
        return $dataObject;
    }

    public function delete(EntityInterface $dataObject)
    {
        try {
            $entity = $this->entityFactory->create();
            $entity->setId($dataObject->getEntityId());
            $this->entityResource->delete($entity);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }
}
