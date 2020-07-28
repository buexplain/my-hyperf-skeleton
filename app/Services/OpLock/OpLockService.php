<?php

declare(strict_types=1);

namespace App\Services\OpLock;

use Hyperf\Redis\RedisFactory;
use Hyperf\Utils\ApplicationContext;

/**
 * 操作锁
 * Class OpLockService
 * @package App\Services\OpLock
 */
class OpLockService
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $opNum;

    /**
     * @var int
     */
    protected $lockSecond;

    /**
     * @var int
     */
    protected $stats;

    /**
     * @var int
     */
    protected $time;

    /**
     * @var bool
     */
    protected $hasRecord = false;

    /**
     * @var \Redis
     */
    protected $redis;

    public function __construct(string $id, int $opNum=3, int $lockSecond=900, $prefix='sign')
    {
        $this->id = 'opLock:'.$prefix.':'.$id;
        $this->opNum = $opNum;
        $this->lockSecond = $lockSecond;
        $this->time = time();
        $this->redis = ApplicationContext::getContainer()->get(RedisFactory::class)->get('default');
        $this->load();
    }

    protected function load()
    {
        $stats = $this->redis->get($this->id);
        if(empty($stats)) {
            $this->stats = 0;
        }else{
            $this->hasRecord = true;
            $this->stats = (int) $stats;
            if($this->stats >= $this->opNum && ($this->lockSecond - ($this->time - $this->stats)) < 0) {
                $this->stats = 0;
            }
        }
    }

    /**
     * 返回可用的次数
     * @return int
     */
    public function getLastOpNum(): int
    {
        if($this->stats >= $this->opNum -1) {
            $this->stats = $this->time;
        }else{
            $this->stats += 1;
        }
        $this->hasRecord = true;
        $this->redis->setEx($this->id, $this->lockSecond, $this->stats);
        if($this->stats > $this->opNum) {
            return 0;
        }
        return $this->opNum - $this->stats;
    }

    /**
     * 返回被锁定的秒数
     * @return int
     */
    public function getLastLockSecond(): int
    {
        if($this->stats > $this->opNum) {
            $result = $this->lockSecond - ($this->time - $this->stats);
            if($result < 0) {
                $this->stats = 0;
                return 0;
            }
            return $result;
        }else{
            return 0;
        }
    }

    /**
     * 释放
     */
    public function release()
    {
        if($this->hasRecord) {
            $this->redis->del($this->id);
            $this->hasRecord = false;
        }
    }
}