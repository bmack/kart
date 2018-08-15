<?php
declare(strict_types = 1);

namespace Bmack\Kart;

/*
 * This file is part of Benni's Kart.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that is distributed with this source code.
 */

use Psr\Event\Dispatcher\StoppableTaskInterface;

/**
 * Can be added to any task which a listener can intercept
 */
trait StoppableTaskTrait
{
    /**
     * @todo debateable if it is private or protected
     * @var bool
     */
    private $isPropagationStopped = false;

    /**
     * @inheritdoc
     */
    public function stopPropagation() : StoppableTaskInterface
    {
        $this->isPropagationStopped = true;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isStopped() : bool
    {
        return $this->isPropagationStopped;
    }
}
