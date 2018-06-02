<?php

namespace SomethingDigital\AdminNotify\Model;

use SomethingDigital\AdminNotify\Api\HistoryManagementInterface;
use SomethingDigital\AdminNotify\Model\ResourceModel\History as HistoryResource;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\DB\Sql\ExpressionFactory;
use Magento\Framework\UrlInterface;
use Magento\User\Model\User;
use Magento\Store\Model\StoreManagerInterface;

class HistoryManagement implements HistoryManagementInterface
{
    const RESTRICTED_TLDS = [
        '.test',
        '.dev',
        '.docker',
    ];

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * @var HistoryResource
     */
    private $resource;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var ExpressionFactory
     */
    private $expressionFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Notification
     */
    private $notification;

    /**
     * HistoryManagement constructor.
     * @param RemoteAddress $remoteAddress
     * @param HistoryResource $resource
     * @param DateTime $dateTime
     * @param ExpressionFactory $expressionFactory
     * @param StoreManagerInterface $storeManager
     * @param \SomethingDigital\AdminNotify\Model\Notification $notification
     */
    public function __construct(
        RemoteAddress $remoteAddress,
        HistoryResource $resource,
        DateTime $dateTime,
        ExpressionFactory $expressionFactory,
        StoreManagerInterface $storeManager,
        Notification $notification
    ) {
        $this->remoteAddress = $remoteAddress;
        $this->resource = $resource;
        $this->dateTime = $dateTime;
        $this->expressionFactory = $expressionFactory;
        $this->storeManager = $storeManager;
        $this->notification = $notification;
    }

    /**
     * {@inheritdoc}
     */
    public function addActivity(User $user, $result)
    {
        $row = $this->rowFromActivity($user, $result);
        $created = $this->updateHistoryRow($row);

        if ($created && $this->shouldSendEmail()) {
            $this->notification->sendActivityEmail($user, $result, $this->getIp());
        }
    }

    /**
     * @param array $row
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function updateHistoryRow($row)
    {
        $updates = [
            History::KEY_ATTEMPTS => $this->expressionFactory->create(['expression' => History::KEY_ATTEMPTS . ' + 1']),
            History::KEY_UPDATED_AT => History::KEY_UPDATED_AT,
        ];

        $affected = $this->resource->getConnection()->insertOnDuplicate(
            $this->resource->getMainTable(),
            [$row],
            $updates
        );

        return $affected === 1;
    }

    /**
     * @param User $user
     * @param bool $result
     * @return array
     */
    private function rowFromActivity(User $user, $result)
    {
        $now = $this->dateTime->gmtDate();
        return [
            History::KEY_USER_ID => $user->getId(),
            History::KEY_IP => $this->getIp(),
            History::KEY_STATUS => $result,
            History::KEY_ATTEMPTS => 1,
            History::KEY_CREATED_AT => $now,
            History::KEY_UPDATED_AT => $now,
        ];
    }

    /**
     * @return bool
     */
    private function shouldSendEmail()
    {
        $url = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_WEB, true);
        $parts = parse_url($url);
        $host = $parts['host'];

        foreach (static::RESTRICTED_TLDS as $tld) {
            if (substr($host, -strlen($tld)) === $tld) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    private function getIp()
    {
        return $this->remoteAddress->getRemoteAddress();
    }
}
