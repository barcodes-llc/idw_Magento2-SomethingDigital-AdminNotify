<?php

namespace SomethingDigital\AdminNotify\Test\Unit\Model;

use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use SomethingDigital\AdminNotify\Model\HistoryManagement;
use SomethingDigital\AdminNotify\Model\ResourceModel\History;
use Magento\User\Model\User;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Store\Api\Data\StoreInterface;
use SomethingDigital\AdminNotify\Model\Notification;

class HistoryManagementTest extends TestCase
{
    /** @var HistoryManagement $historyManagement */
    private $historyManagement;

    /** @var RemoteAddress|MockObject $remoteAddressMock */
    private $remoteAddressMock;

    /** @var History|MockObject $resourceMock */
    private $resourceMock;

    /** @var DateTime|MockObject $dateTimeMock */
    private $dateTimeMock;

    /** @var StoreManagerInterface|MockObject $storeManagerMock */
    private $storeManagerMock;

    /** @var User|MockObject $userMock */
    private $userMock;

    /** @var AdapterInterface|MockObject $adapterMock */
    private $adapterMock;

    /** @var StoreInterface|MockObject $storeMock */
    private $storeMock;

    /** @var Notification|MockObject $notificationMock */
    private $notificationMock;

    protected function setUp()
    {
        $this->remoteAddressMock = $this->createMock(RemoteAddress::class);
        $this->resourceMock = $this->createMock(History::class);
        $this->dateTimeMock = $this->createMock(DateTime::class);
        $this->storeManagerMock = $this->createMock(StoreManagerInterface::class);
        $this->userMock = $this->createMock(User::class);
        $this->adapterMock = $this->createMock(AdapterInterface::class);
        $this->storeMock = $this->getMockBuilder(StoreInterface::class)
            ->setMethods(['getBaseUrl'])
            ->getMockForAbstractClass();
        $this->notificationMock = $this->createMock(Notification::class);

        $this->resourceMock->method('getConnection')
            ->willReturn($this->adapterMock);

        $this->storeManagerMock->method('getStore')
            ->willReturn($this->storeMock);

        $this->historyManagement = new HistoryManagement(
            $this->remoteAddressMock,
            $this->resourceMock,
            $this->dateTimeMock,
            $this->storeManagerMock,
            $this->notificationMock
        );
    }

    public function testAddActivity()
    {
        $this->adapterMock->method('insertOnDuplicate')->willReturn(1);
        $this->storeMock->method('getBaseUrl')->willReturn('https://mystore.com/');

        $this->notificationMock->expects($this->once())
            ->method('sendActivityEmail');

        $this->historyManagement->addActivity($this->userMock, true);
    }

    public function testAddActivityUpdateHistory()
    {
        $this->adapterMock->method('insertOnDuplicate')->willReturn(2);

        $this->notificationMock->expects($this->never())
            ->method('sendActivityEmail');

        $this->historyManagement->addActivity($this->userMock, true);
    }

    public function testAddActivityDevHost()
    {
        $this->adapterMock->method('insertOnDuplicate')->willReturn(1);
        $this->storeMock->method('getBaseUrl')->willReturn('https://mystore.test/');

        $this->notificationMock->expects($this->never())
            ->method('sendActivityEmail');
        
        $this->historyManagement->addActivity($this->userMock, true);
    }
}
