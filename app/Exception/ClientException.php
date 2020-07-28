<?php

declare(strict_types=1);

namespace App\Exception;

use App\Constants\ErrorCode;
use Exception;
use Throwable;

/**
 * 客户端错误
 * Class ClientException
 * @package App\Exception
 */
class ClientException extends Exception implements MessageInterface
{
    /**
     * ClientException constructor.
     * @param null $message
     * @param int $code
     * @param Throwable|null $previous
     * @throws \Hyperf\Constants\Exception\ConstantsException
     */
    public function __construct($message = null, $code = ErrorCode::CLIENT_ERROR, Throwable $previous = null)
    {
        if (is_null($message)) {
            $message = ErrorCode::getMessage($code);
        }
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getClientMessage()
    {
        return $this->getMessage();
    }

    /**
     * @return string
     */
    public function getLogMessage()
    {
        return $this->getMessage();
    }
}
