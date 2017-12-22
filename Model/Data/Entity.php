<?php

namespace Oggetto\Entities\Model\Data;

use Magento\Framework\Api\AbstractSimpleObject;
use Oggetto\Entities\Api\Data\EntityInterface;

class Entity extends AbstractSimpleObject
    implements EntityInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return $this->_get(self::NAME);
    }

    /**
     * @param string $name
     * @return EntityInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @return int
     */
    public function getEntityId()
    {
        return $this->_get(self::ENTITY_ID);
    }

    /**
     * @param int $entityId
     * @return EntityInterface
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }
}
