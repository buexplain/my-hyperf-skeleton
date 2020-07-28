<?php

declare(strict_types=1);

namespace App\Exception;

use App\Constants\ErrorCode;
use Exception;
use Throwable;

/**
 *  客户端参数错误
 * Class InvalidArgumentException
 * @package App\Exception
 */
class InvalidArgumentException extends ClientException
{
    /**
     * InvalidArgumentException constructor.
     * @param null|string $message 参数名称或自定义提示信息
     * @param int $code
     * @param Exception|null $previous
     *  @throws \Hyperf\Constants\Exception\ConstantsException
     */
    public function __construct($message=null, $code = ErrorCode::CLIENT_INVALID_ARGUMENT, Throwable $previous = null)
    {
        if(is_null($message)) {
            $message = ErrorCode::getMessage($code);
        }elseif(ctype_alnum($message)) {
            $message = ErrorCode::getMessage($code).'：'.$message;
        }
        parent::__construct($message, $code, $previous);
    }
}
