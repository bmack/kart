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
use Bmack\Kart\Examples\PayloadMessageEvent;
use Bmack\Kart\RuntimeListenerProvider;
use PHPUnit\Framework\TestCase;

/**
 * Test case for handing an event in and reading data out of it, no chance for modifications etc.
 * But we want to make sure that this is actually dealing with non-modified objects.
 * The caller/library author does not need to care about the resulting object, however the dispatcher does.
 */
class PayloadMessageEventTest extends TestCase
{
    /**
     * @test
     */
    public function readPayload()
    {
        $randomListener = function (PayloadMessageEvent $event) {
            echo implode(',', $event->getPayload());
            return $event;
        };

        $runtimeProvider = new RuntimeListenerProvider();
        $runtimeProvider->addListener($randomListener);

        $dispatcher = new Dispatcher([$runtimeProvider]);

        $event = new PayloadMessageEvent(['SEND']);
        // suppress output
        ob_start(function () {
        });
        $dispatcher->dispatch($event);
        $result = ob_get_flush();
        $this->assertEquals($result, 'SEND');
    }

    /**
     * @test
     */
    public function listenerReturnsCloneOfEvent()
    {
        $this->expectExceptionCode(1534141128);
        $randomListener = function (PayloadMessageEvent $event) {
            return clone $event;
        };

        $runtimeProvider = new RuntimeListenerProvider();
        $runtimeProvider->addListener($randomListener);

        $dispatcher = new Dispatcher([$runtimeProvider]);

        $event = new PayloadMessageEvent(['SEND']);
        $dispatcher->dispatch($event);
    }
}
