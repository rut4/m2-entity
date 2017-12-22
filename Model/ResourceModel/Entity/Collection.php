<?php

namespace Oggetto\Entities\Model\ResourceModel\Entity;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(\Oggetto\Entities\Model\Entity::class, \Oggetto\Entities\Model\ResourceModel\Entity::class);
    }
}
