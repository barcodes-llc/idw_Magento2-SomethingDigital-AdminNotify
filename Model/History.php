<?php

namespace SomethingDigital\AdminNotify\Model;

use Magento\Framework\Model\AbstractModel;
use SomethingDigital\AdminNotify\Api\Data\HistoryInterface;

class History extends AbstractModel implements HistoryInterface
{
    const KEY_HISTORY_ID = 'history_id';
    const KEY_USER_ID = 'user_id';
    const KEY_IP = 'ip';
    const KEY_STATUS = 'status';
    const KEY_ATTEMPTS = 'attempts';
    const KEY_CREATED_AT = 'created_at';
    const KEY_UPDATED_AT = 'updated_at';

    protected $_eventPrefix = 'sd_adminnotify_history';

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(static::KEY_HISTORY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserId()
    {
        return $this->getData(static::KEY_USER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function getIp()
    {
        return $this->getData(static::KEY_IP);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->getData(static::KEY_STATUS);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttempts()
    {
        return $this->getData(static::KEY_ATTEMPTS);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->getData(static::KEY_CREATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->getData(static::KEY_UPDATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setUserId($userId)
    {
        $this->setData(static::KEY_USER_ID, $userId);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setIp($ip)
    {
        $this->setData(static::KEY_IP, $ip);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($status)
    {
        $this->setData(static::KEY_STATUS, $status);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttempts($attempts)
    {
        $this->setData(static::KEY_ATTEMPTS, $attempts);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($createdAt)
    {
        $this->setData(static::KEY_CREATED_AT, $createdAt);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->setData(static::KEY_UPDATED_AT, $updatedAt);
        return $this;
    }
}
