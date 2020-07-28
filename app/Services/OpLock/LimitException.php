<?php

declare(strict_types=1);

namespace App\Services\OpLock;

use App\Exception\ClientException;

class LimitException extends ClientException
{
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
    }
}