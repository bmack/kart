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
use Bmack\Kart\Examples\ExternalFulfillmentTask;
use Bmack\Kart\RuntimeListenerProvider;
use PHPUnit\Framework\TestCase;

class ExternalFulfillmentTaskTest extends TestCase
{
    /**
     * @test
     */
    public function properlyModifyPayload()
    {
        $listener1 = function (ExternalFulfillmentTask $t) {
            if ($t->getJobNumber() > 0) {
                echo 'My listener works.';
            }
            return $t;
        };

        $listener2 = function (ExternalFulfillmentTask $t) {
            if ($t->getJobNumber() === 2) {
                echo 'Running number two and thats the max you can get.';
                return $t->stopPropagation();
            }
            return $t;
        };

        $listener3 = function (ExternalFulfillmentTask $t) {
            if ($t->getJobNumber() === -1) {
                echo 'Never call -1 - this is ugly';
            }
            return $t;
        };

        $listener4 = function (ExternalFulfillmentTask $t) {
            if ($t->getJobNumber() > 100) {
                echo 'Cannot process jobs with a number higher than 100';
            }
            return $t;
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
        $dispatcher->process(new ExternalFulfillmentTask(1));
        $result = ob_get_clean();
        $this->assertEquals($result, 'My listener works.');

        // suppress output
        ob_start(function () {
        });
        $dispatcher->process(new ExternalFulfillmentTask(2));
        $result = ob_get_clean();
        $this->assertEquals($result, 'My listener works.Running number two and thats the max you can get.');

        // suppress output
        ob_start(function () {
        });
        $dispatcher->process(new ExternalFulfillmentTask(0));
        $result = ob_get_clean();
        // No listener responded
        $this->assertEquals($result, '');
    }
}
