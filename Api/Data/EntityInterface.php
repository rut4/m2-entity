<?php

namespace Oggetto\Entities\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface EntityInterface extends ExtensibleDataInterface
{
    const ENTITY_ID = 'entity_id';
    const NAME = 'name';

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $entityId
     * @return EntityInterface
     */
    public function setEntityId($entityId);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return EntityInterface
     */
    public function setName($name);
}
