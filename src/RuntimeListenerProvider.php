<?php
declare(strict_types = 1);

namespace Bmack\Kart;

/*
 * This file is part of Benni's Kart.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that is distributed with this source code.
 */

use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * Easy listener provider to add custom listeners at any time.
 * No prioritization / sorting is taken care of.
 */
class RuntimeListenerProvider implements ListenerProviderInterface
{
    protected $listeners = [];

    /**
     * @param $listener
     */
    public function addListener($listener)
    {
        $this->listeners[] = $listener;
    }

    /**
     * @inheritdoc
     */
    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->listeners as $listener) {
            $eventType = null;
            if (is_string($listener) || is_callable($listener)) {
                $r = new \ReflectionFunction($listener);
                $eventType = $r->getParameters()[0]->getType()->getName();
            } elseif (is_array($listener)) {
                if (is_string($listener[0])) {
                    $r = new \ReflectionClass($listener[0]);
                    $eventType = $r->getMethod($listener[1])->getParameters()[0]->getType()->getName();
                }
            }
            if ($eventType === null) {
                throw new UnacceptableListenerException(
                    'The listener ' . (string)$listener . ' is not suited for being an event listener.',
                    1534140615
                );
            }
            if ($event instanceof $eventType) {
                yield $listener;
            }
        }
    }
}
