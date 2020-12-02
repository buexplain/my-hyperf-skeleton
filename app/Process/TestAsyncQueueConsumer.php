<?php

declare(strict_types=1);

namespace App\Process;

use Hyperf\AsyncQueue\Process\ConsumerProcess;

/**
 * Class AsyncQueueConsumer
 * @package App\Process
 */
class TestAsyncQueueConsumer extends ConsumerProcess
{
    protected $queue = 'test';
}