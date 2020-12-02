<?php

declare(strict_types=1);

namespace App\Exception;

use App\Constants\ErrorCode;
use Exception;
use Throwable;

/**
 * 第三方接口错误
 * Class CallThirdException
 * @package App\Exceptions
 */
class CallThirdException extends ServerException implements MessageInterface
{
    /**
     * 第三方地址
     * @var string
     */
    protected $third_url = '';

    /**
     * 输出到日志的第三方错误
     * @var string
     */
    protected $log_message = '';

    /**
     * 第三方code
     * @var int|null
     */
    protected $third_code = null;

    /**
     * CallThirdException constructor.
     * @param string $url 第三方的地址
     * @param string|null $message 第三方返回的消息
     * @param int|null $code 第三方的错误码
     * @param Exception|null $previous
     *  @throws \Hyperf\Constants\Exception\ConstantsException
     */
    public function __construct($url, $message=null, $code=null, Throwable $previous = null)
    {
        $this->third_url = $url;
        $this->third_code = $code;

        if (is_null($message)) {
            $message = ErrorCode::getMessage(ErrorCode::SERVER_CALL_THIRD);
        }

        if(!is_null($code)) {
            $this->log_message = sprintf("%s [%s] [%s]", $message, $code, $url);
            parent::__construct(sprintf("%s [%s]", $message, $code), ErrorCode::SERVER_CALL_THIRD, $previous);
        }else{
            $this->log_message = sprintf("%s [%s]", $message, $url);
            parent::__construct($message, ErrorCode::SERVER_CALL_THIRD, $previous);
        }
    }

    /**
     * @return string
     */
    public function getThirdURL()
    {
        return $this->third_url;
    }

    /**
     * @return int|null
     */
    public function getThirdCode()
    {
        return $this->third_code;
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
        return $this->log_message;
    }
}