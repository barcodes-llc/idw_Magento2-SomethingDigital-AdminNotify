<?php

namespace SomethingDigital\AdminNotify\Api\Data;

interface HistoryInterface
{
    /**
     * @return string|null
     */
    public function getId();

    /**
     * @return string|null
     */
    public function getUserId();

    /**
     * @return string|null
     */
    public function getIp();

    /**
     * @return string|null
     */
    public function getStatus();

    /**
     * @return string|null
     */
    public function getAttempts();

    /**
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * @param string|int $userId
     * @return $this
     */
    public function setUserId($userId);

    /**
     * @param string $ip
     * @return $this
     */
    public function setIp($ip);

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @param int $attempts
     * @return $this
     */
    public function setAttempts($attempts);

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);
}
