<?php

namespace SomethingDigital\AdminNotify\Api;

interface HistoryManagementInterface
{
    /**
     * @param \Magento\User\Model\User $user
     * @param bool $result
     * @return void
     */
    public function addActivity(\Magento\User\Model\User $user, $result);
}
