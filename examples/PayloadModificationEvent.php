<?php
declare(strict_types = 1);

namespace Bmack\Kart\Examples;

/*
 * This file is part of Benni's Kart.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that is distributed with this source code.
 */

/**
 * Modifies payload shipped with a task. VERY generic
 */
class PayloadModificationEvent
{
    /**
     * @var array
     */
    private $payload;

    public function __construct(array $payload = null)
    {
        $this->payload = $payload ?? [];
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function setPayload(array $payload)
    {
        $this->payload = $payload;
    }
}