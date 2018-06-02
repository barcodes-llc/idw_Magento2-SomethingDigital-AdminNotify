<?php

namespace SomethingDigital\AdminNotify\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class History extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('sd_adminnotify_history', 'history_id');
    }
}
