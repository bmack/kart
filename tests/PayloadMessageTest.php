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
use Bmack\Kart\Examples\PayloadMessage;
use Bmack\Kart\RuntimeListenerProvider;
use PHPUnit\Framework\TestCase;

class PayloadMessageTest extends TestCase
{
    /**
     * @test
     */
    public function readPayload()
    {
        $randomListener = function (PayloadMessage $message) {
            echo implode(',', $message->getPayload());
        };

        $runtimeProvider = new RuntimeListenerProvider();
        $runtimeProvider->addListener($randomListener);

        $dispatcher = new Dispatcher([$runtimeProvider]);

        $message = new PayloadMessage(['SEND']);
        // suppress output
        ob_start(function () {
        });
        $dispatcher->notify($message);
        $result = ob_get_flush();
        $this->assertEquals($result, 'SEND');
    }
}
