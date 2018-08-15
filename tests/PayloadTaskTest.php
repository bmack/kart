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
use Bmack\Kart\Examples\AppenderTask;
use Bmack\Kart\Examples\PayloadTask;
use Bmack\Kart\RuntimeListenerProvider;
use PHPUnit\Framework\TestCase;

class PayloadTaskTest extends TestCase
{
    /**
     * @test
     */
    public function properlyModifyPayload()
    {
        $randomListener = function (PayloadTask $task) {
            $task->setPayload(['firstname' => 'michael', 'lastname' => 'jackson']);
            return $task;
        };
        $randomSecondListener = function (PayloadTask $task) {
            $payload = $task->getPayload();
            $payload['lastname'] = 'jordan';
            $task->setPayload($payload);
            return $task;
        };

        $runtimeProvider = new RuntimeListenerProvider();
        $runtimeProvider->addListener($randomListener);
        $runtimeProvider->addListener($randomSecondListener);

        $dispatcher = new Dispatcher([$runtimeProvider]);

        // Nothing has changed, as no appender task had a listener registered
        $originalTask = new AppenderTask(['begins']);
        $resultTask = $dispatcher->process($originalTask);
        $this->assertEquals($resultTask, $originalTask);

        // Output should be "michael" and "jordan"
        $originalTask = new PayloadTask(['firstname' => 'John']);
        $resultTask = $dispatcher->process($originalTask);
        $this->assertEquals(['firstname' => 'michael', 'lastname' => 'jordan'], $originalTask->getPayload());
    }
}
