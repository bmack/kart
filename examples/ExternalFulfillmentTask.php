<?php
declare(strict_types = 1);

namespace Bmack\Kart\Examples;

/*
 * This file is part of Benni's Kart.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that is distributed with this source code.
 */

use Bmack\Kart\StoppableTaskTrait;
use Psr\Event\Dispatcher\StoppableTaskInterface;

/**
 * VERY generic to see if a task can be stopped
 */
class ExternalFulfillmentTask implements StoppableTaskInterface
{
    use StoppableTaskTrait;

    protected $jobNumber = 0;

    public function __construct(int $jobNumber)
    {
        $this->jobNumber = $jobNumber;
    }

    public function getJobNumber() : int
    {
        return $this->jobNumber;
    }
}