<?php
declare(strict_types = 1);

namespace Bmack\Kart\Examples;

/*
 * This file is part of Benni's Kart.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that is distributed with this source code.
 */

use Psr\Event\Dispatcher\MessageInterface;

/**
 * A generic message with some payload
 */
class PayloadMessage implements MessageInterface
{
    /**
     * @var mixed
     */
    private $payload;

    public function __construct($payload = null)
    {
        $this->payload = $payload;
    }

    public function getPayload()
    {
        return $this->payload;
    }
}