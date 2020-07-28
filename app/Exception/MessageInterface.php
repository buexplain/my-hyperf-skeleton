<?php

declare(strict_types=1);

namespace App\Exception;

interface MessageInterface
{
    /**
     * 返回客户端可理解的消息
     * @return string
     */
    public function getClientMessage();

    /**
     * 返回服务端记录日志专用的消息
     * @return string
     */
    public function getLogMessage();
}