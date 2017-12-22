<?php

namespace Oggetto\Entities\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Entity extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('oggetto_entities', 'entity_id');
    }
}
