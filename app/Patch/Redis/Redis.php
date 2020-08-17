<?php

declare(strict_types=1);

namespace App\Patch\Redis;

use Hyperf\Redis\RedisFactory;
use Hyperf\Utils\ApplicationContext;

class Redis
{
    /**
     * @return \Redis
     */
    public static function getInstance($poolName='default')
    {
        $redis = ApplicationContext::getContainer()->get(RedisFactory::class)->get($poolName);
        return $redis;
    }
}

