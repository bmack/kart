<?php
declare(strict_types = 1);

namespace Bmack\Kart;

/*
 * This file is part of Benni's Kart.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that is distributed with this source code.
 */

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * Can be added to any event which a listener can intercept
 */
trait StoppableEventTrait
{
    /**
     * @var bool
     */
    private $isPropagationStopped = false;

    /**
     * @inheritdoc
     */
    public function stopPropagation() : StoppableEventInterface
    {
        $this->isPropagationStopped = true;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isPropagationStopped() : bool
    {
        return $this->isPropagationStopped;
    }
}
