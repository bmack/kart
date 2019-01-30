<?php
declare(strict_types = 1);

namespace Bmack\Kart\Tests;

/*
 * This file is part of Benni's Kart.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that is distributed with this source code.
 */

use Bmack\Kart\Dispatcher;
use Bmack\Kart\Examples\ExternalFulfillmentEvent;
use Bmack\Kart\RuntimeListenerProvider;
use PHPUnit\Framework\TestCase;

class ExternalFulfillmentEventTest extends TestCase
{
    /**
     * @test
     */
    public function properlyModifyPayload()
    {
        $listener1 = function (ExternalFulfillmentEvent $e) {
            if ($e->getJobNumber() > 0) {
                echo 'My listener works.';
            }
            return $e;
        };

        $listener2 = function (ExternalFulfillmentEvent $e) {
            if ($e->getJobNumber() === 2) {
                echo 'Running number two and thats the max you can get.';
                return $e->stopPropagation();
            }
            return $e;
        };

        $listener3 = function (ExternalFulfillmentEvent $e) {
            if ($e->getJobNumber() === -1) {
                echo 'Never call -1 - this is ugly';
            }
            return $e;
        };

        $listener4 = function (ExternalFulfillmentEvent $e) {
            if ($e->getJobNumber() > 100) {
                echo 'Cannot process jobs with a number higher than 100';
            }
            return $e;
        };

        $runtimeProvider = new RuntimeListenerProvider();
        $runtimeProvider->addListener($listener1);
        $runtimeProvider->addListener($listener2);
        $runtimeProvider->addListener($listener3);
        $runtimeProvider->addListener($listener4);

        $dispatcher = new Dispatcher([$runtimeProvider]);

        // suppress output
        ob_start(function () {
        });
        $dispatcher->dispatch(new ExternalFulfillmentEvent(1));
        $result = ob_get_clean();
        $this->assertEquals($result, 'My listener works.');

        // suppress output
        ob_start(function () {
        });
        $dispatcher->dispatch(new ExternalFulfillmentEvent(2));
        $result = ob_get_clean();
        $this->assertEquals($result, 'My listener works.Running number two and thats the max you can get.');

        // suppress output
        ob_start(function () {
        });
        $dispatcher->dispatch(new ExternalFulfillmentEvent(0));
        $result = ob_get_clean();
        // No listener responded
        $this->assertEquals($result, '');
    }
}
