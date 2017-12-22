<?php

namespace Oggetto\Entities\Model;

use Magento\Framework\Model\AbstractModel;
use Oggetto\Entities\Model\ResourceModel\Entity as EntityResource;

class Entity extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(EntityResource::class);
    }
}
