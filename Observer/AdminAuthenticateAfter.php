<?php

namespace SomethingDigital\AdminNotify\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use SomethingDigital\AdminNotify\Api\HistoryManagementInterface;

class AdminAuthenticateAfter implements ObserverInterface
{
    /**
     * @var HistoryManagementInterface
     */
    private $historyManagement;

    /**
     * AdminAuthenticateAfter constructor.
     * @param HistoryManagementInterface $historyManagement
     */
    public function __construct(
        HistoryManagementInterface $historyManagement
    ) {
        $this->historyManagement = $historyManagement;
    }

    /**
     * @param Observer $observer
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(Observer $observer)
    {
        $user = $observer->getUser();

        if ($user->getId()) {
            $this->historyManagement->addActivity($user, $observer->getResult());
        }
    }
}
