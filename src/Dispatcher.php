<?php
declare(strict_types = 1);

namespace Bmack\Kart;

/*
 * This file is part of Benni's Kart.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that is distributed with this source code.
 */

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * The main dispatcher, handles everything
 */
class Dispatcher implements EventDispatcherInterface
{
    /**
     * @var ListenerProviderInterface[]
     */
    private $listenerProviders;

    /**
     * @param array|null $listenerProviders
     */
    public function __construct(array $listenerProviders = null)
    {
        $this->listenerProviders = $listenerProviders ?? [];
    }

    /**
     * @inheritdoc
     */
    public function dispatch($event)
    {
        foreach ($this->listenerProviders as $listenerProvider) {
            foreach ($listenerProvider->getListenersForEvent($event) as $listener) {
                if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                    break;
                }
                $resultingEvent = call_user_func($listener, $event);
                if ($resultingEvent !== $event) {
                    throw new \RuntimeException('Hey, the listener did not return the same event object!', 1534141128);
                }
                $event = $resultingEvent;
            }
        }
        return $event;
    }
}
