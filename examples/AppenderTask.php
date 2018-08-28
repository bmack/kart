<?php
declare(strict_types = 1);

namespace Bmack\Kart\Examples;

/*
 * This file is part of Benni's Kart.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that is distributed with this source code.
 */

use Psr\EventDispatcher\TaskInterface;

/**
 * Appends arbitrary data
 */
class AppenderTask implements TaskInterface
{
    private $data = [];

    public function __construct(array $data = null)
    {
        $this->data = $data ?? [];
    }

    public function append($value)
    {
        $this->data[] = $value;
    }
}