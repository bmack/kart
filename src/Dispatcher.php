<?php
declare(strict_types = 1);

namespace Bmack\Kart;

/*
 * This file is part of Benni's Kart.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that is distributed with this source code.
 */

use Psr\EventDispatcher\EventInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\MessageInterface;
use Psr\EventDispatcher\MessageNotifierInterface;
use Psr\EventDispatcher\StoppableTaskInterface;
use Psr\EventDispatcher\TaskInterface;
use Psr\EventDispatcher\TaskProcessorInterface;

/**
 * The main dispatcher, handles everything
 */
class Dispatcher implements MessageNotifierInterface, TaskProcessorInterface
{
    /**
     * @var ListenerProviderInterface[]
     */
    private $listenerProviders;

    public function __construct(array $listenerProviders = null)
    {
        $this->listenerProviders = $listenerProviders ?? [];
    }

    /**
     * NOT PART OF THE SPEC!
     *
     * Convenience, but actually not so useful, as the result type cannot be ensured truly.
     * Also, this gives more brain dump to the author of the library code (the one actually calling "dispatch")
     *
     * It is actually very stupid, the more I think of it:
     * - What if an event is both a message and a task?
     *
     * @param EventInterface $event
     * @return EventInterface
     */
    public function dispatch(EventInterface $event) : EventInterface
    {
        if ($event instanceof TaskInterface) {
            return $this->process($event);
        }
        if ($event instanceof MessageInterface) {
            $this->notify($event);
            return $event;
        }
        throw new \RuntimeException('Meeh, an Event that is neither a task nor message?', 1534141378);
    }

    /**
     * Simple messaging bus. Use this in your library if you want to send out an info "Hey, I just updated a
     * users account" or something a long the lines.
     *
     * @param MessageInterface $message
     */
    public function notify(MessageInterface $message): void
    {
        foreach ($this->listenerProviders as $listenerProvider) {
            foreach ($listenerProvider->getListenersForEvent($message) as $listener) {
                call_user_func($listener, $message);
            }
        }
    }

    /**
     * Process tasks which could be stopped if the StoppableTaskInterface is implemented.
     *
     * @param TaskInterface $task
     * @return TaskInterface
     */
    public function process(TaskInterface $task): TaskInterface
    {
        foreach ($this->listenerProviders as $listenerProvider) {
            foreach ($listenerProvider->getListenersForEvent($task) as $listener) {
                $resultTask = call_user_func($listener, $task);
                if ($resultTask instanceof StoppableTaskInterface && $resultTask->isStopped()) {
                    return $resultTask;
                }
                if (!$resultTask instanceof TaskInterface) {
                    throw new \RuntimeException('Hey, the listener did not return a task object!', 1534141128);
                }
                $task = $resultTask;
            }
        }
        return $task;
    }
}
