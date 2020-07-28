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
     * 第三方消息
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
     * @param string $message 第三方返回的消息
     * @param int $code 第三方的错误码
     * @param Exception|null $previous
     *  @throws \Hyperf\Constants\Exception\ConstantsException
     */
    public function __construct($url, $message, $code, Throwable $previous = null)
    {
        $this->log_message = sprintf("%s [%s] [%s]", $message, $code, $url);
        parent::__construct(sprintf("%s [%s]", $message, $code), ErrorCode::SERVER_CALL_THIRD, $previous);
    }

    public function getThirdURL()
    {
        return $this->third_url;
    }

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