<?php

namespace SomethingDigital\AdminNotify\Test\Integration\Model;

use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use SomethingDigital\AdminNotify\Model\History;
use SomethingDigital\AdminNotify\Model\HistoryManagement;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use SomethingDigital\AdminNotify\Model\Notification;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Store;
use Magento\User\Model\User;
use SomethingDigital\AdminNotify\Model\ResourceModel\History as HistoryResource;

class HistoryManagementTest extends TestCase
{
    /** @var HistoryManagement */
    private $historyManagement;

    /** @var Notification|MockObject */
    private $notificationMock;

    /** @var StoreManagerInterface|MockObject */
    private $storeManagerMock;

    /** @var Store|MockObject */
    private $storeMock;

    /** @var RemoteAddress|MockObject */
    private $remoteAddressMock;

    /** @var User|MockObject */
    private $userMock;

    protected function setUp()
    {
        $objectManager = Bootstrap::getObjectManager();

        $this->notificationMock = $this->createMock(Notification::class);
        $this->storeManagerMock = $this->createMock(StoreManagerInterface::class);
        $this->storeMock = $this->createMock(Store::class);
        $this->remoteAddressMock = $this->createMock(RemoteAddress::class);
        $this->userMock = $this->createMock(User::class);

        $this->storeManagerMock->method('getStore')
            ->willReturn($this->storeMock);

        $this->remoteAddressMock->method('getRemoteAddress')
            ->willReturn('127.0.0.1');

        $this->userMock->method('getId')
            ->willReturn(1);

        $this->historyManagement = $objectManager->create(
            HistoryManagement::class,
            [
                'notification' => $this->notificationMock,
                'remoteAddress' => $this->remoteAddressMock,
                'storeManager' => $this->storeManagerMock,
            ]
        );
    }

    /**
     * @magentoDbIsolation enabled
     */
    public function testAddActivity()
    {
        $this->storeMock->method('getBaseUrl')
            ->willReturn('https://mystore.com');

        $this->notificationMock->expects($this->once())
            ->method('sendActivityEmail');
        
        $this->historyManagement->addActivity($this->userMock, true);
    }

    /**
     * @magentoDbIsolation enabled
     */
    public function testAddActivityDevTld()
    {
        $this->storeMock->method('getBaseUrl')
            ->willReturn('https://mystore.test');

        $this->notificationMock->expects($this->never())
            ->method('sendActivityEmail');

        $this->historyManagement->addActivity($this->userMock, true);
    }

    /**
     * @magentoDbIsolation enabled
     */
    public function testAddActivityMultipleAttempts()
    {
        $this->storeMock->method('getBaseUrl')
            ->willReturn('https://mystore.com');

        $this->notificationMock->expects($this->once())
            ->method('sendActivityEmail');

        for ($i = 0; $i < 3; $i++) {
            $this->historyManagement->addActivity($this->userMock, true);
        }
    }
}
