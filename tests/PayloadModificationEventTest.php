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
use Bmack\Kart\Examples\AppenderEvent;
use Bmack\Kart\Examples\PayloadModificationEvent;
use Bmack\Kart\RuntimeListenerProvider;
use PHPUnit\Framework\TestCase;

class PayloadModificationEventTest extends TestCase
{
    /**
     * @test
     */
    public function properlyModifyPayload()
    {
        $randomListener = function (PayloadModificationEvent $evt) {
            $evt->setPayload(['firstname' => 'michael', 'lastname' => 'jackson']);
            return $evt;
        };
        $randomSecondListener = function (PayloadModificationEvent $evt) {
            $payload = $evt->getPayload();
            $payload['lastname'] = 'jordan';
            $evt->setPayload($payload);
            return $evt;
        };

        $runtimeProvider = new RuntimeListenerProvider();
        $runtimeProvider->addListener($randomListener);
        $runtimeProvider->addListener($randomSecondListener);

        $dispatcher = new Dispatcher([$runtimeProvider]);

        // Nothing has changed, as no appender task had a listener registered
        $originalEvent = new AppenderEvent(['begins']);
        $resultEvent = $dispatcher->dispatch($originalEvent);
        $this->assertEquals($resultEvent, $originalEvent);

        // Output should be "michael" and "jordan"
        $originalEvent = new PayloadModificationEvent(['firstname' => 'John']);
        $resultEvent = $dispatcher->dispatch($originalEvent);
        $this->assertEquals(['firstname' => 'michael', 'lastname' => 'jordan'], $originalEvent->getPayload());
        $this->assertEquals(['firstname' => 'michael', 'lastname' => 'jordan'], $resultEvent->getPayload());
    }
}
