<?php

namespace SomethingDigital\AdminNotify\Test\Integration\Observer;

use PHPUnit\Framework\TestCase;
use Magento\TestFramework\Helper\Bootstrap;
use SomethingDigital\AdminNotify\Observer\AdminAuthenticateAfter;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;

class AdminAuthenticateAfterTest extends TestCase
{
    const OBSERVER_NAME = 'sd_adminnotify';

    /**
     * @dataProvider observerRegistrationDataProvider
     * @param $area
     * @param $expected
     */
    public function testObserverRegistration($area)
    {
        $objectManager = Bootstrap::getObjectManager();
        /** @var State $state */
        $state = $objectManager->get(State::class);
        $state->setAreaCode($area);

        /** @var \Magento\Framework\Event\Config $eventConfig */
        $eventConfig = $objectManager->get(\Magento\Framework\Event\Config::class);

        $observers = $eventConfig->getObservers('admin_user_authenticate_after');

        $this->assertArrayHasKey(static::OBSERVER_NAME, $observers);
        $this->assertEquals(
            AdminAuthenticateAfter::class,
            $observers[static::OBSERVER_NAME]['instance']
        );
    }

    public function observerRegistrationDataProvider()
    {
        return [
            'admin' => [Area::AREA_ADMINHTML],
            'rest' => [Area::AREA_WEBAPI_REST],
        ];
    }
}
