<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Exception\Handler;

use App\Constants\ErrorCode;
use App\Exception\ClientException;
use App\Exception\InvalidArgumentException;
use App\Exception\NoDataFoundException;
use App\Exception\ServerException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Request;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Codec\Json;
use Hyperf\Utils\Str;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * 当前运行的环境
     * @var string
     */
    protected $env = 'prod';

    public function __construct()
    {
        $this->env = env('APP_ENV', 'prod');
    }

    /**
     * 无须记录日志的异常的名单
     * @var array
     */
    protected $dontReport = [
        ClientException::class,
        \Hyperf\Database\Model\ModelNotFoundException::class,
        \Hyperf\Validation\ValidationException::class,
    ];

    /**
     * 不必记录日志的异常
     * @param Throwable $throwable
     * @return bool
     */
    protected function shouldNtReport(Throwable $throwable)
    {
        foreach ($this->dontReport as $type) {
            if ($throwable instanceof $type) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Throwable $throwable
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \Hyperf\Constants\Exception\ConstantsException
     */
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $logger = ApplicationContext::getContainer()->get(LoggerFactory::class)->get();

        //判断是否为服务端错误与客户端错误
        $notServerAndClient = !($throwable instanceof ServerException) && !($throwable instanceof ClientException);

        //判断日志是否需要记录
        $shouldNtReport = $this->shouldNtReport($throwable);

        //记录错误日志
        if (!$shouldNtReport) {
            if($notServerAndClient) {
                //不再规划范围内的异常，记录整个栈信息
                $formatter = ApplicationContext::getContainer()->get(\Hyperf\ExceptionHandler\Formatter\FormatterInterface::class);
                $logger->error($formatter->format($throwable));
            }else{
                $message =  trim($throwable->getLogMessage());
                if(strlen($message) == 0) {
                    $message = get_class($throwable);
                }
                $logger->error(sprintf('%s in %s on line %d', $message, $throwable->getFile(), $throwable->getLine()));
            }
        }

        //将不再规划范围内的异常转为服务端异常或是客户端异常
        if($notServerAndClient) {
            //无需上报日志的错误，视为客户端错误
            if($shouldNtReport) {
                //转换为客户端异常
                if($throwable instanceof \Hyperf\Database\Model\ModelNotFoundException) {
                    //转换查询异常
                    $throwable = new NoDataFoundException();
                }elseif($throwable instanceof \Hyperf\Validation\ValidationException) {
                    //转换验证异常
                    $throwable = new InvalidArgumentException($throwable->validator->errors()->first());
                }else {
                    $message =  trim($throwable->getMessage());
                    if(strlen($message) == 0) {
                        $message = get_class($throwable);
                    }
                    $throwable = new ClientException($message, ErrorCode::CLIENT_ERROR, $throwable);
                }
            }else{
                //需要上报日志的错误，转为服务端错误
                if($this->env == 'dev') {
                    //开发环境，输出原始的错误信息
                    $message =  trim($throwable->getMessage());
                    if(strlen($message) == 0) {
                        $message = get_class($throwable);
                    }
                    $throwable = new ServerException($message, ErrorCode::SERVER_ERROR, $throwable);
                }else{
                    //其它环境，直接屏蔽掉原始的错误信息
                    $throwable = new ServerException(ErrorCode::getMessage(ErrorCode::SERVER_ERROR), ErrorCode::SERVER_ERROR, $throwable);
                }
            }
        }

        /**
         * @var $request Request
         */
        $request = ApplicationContext::getContainer()->get(RequestInterface::class);
        $wantsHtml = Str::contains($request->getHeaderLine('Accept'), ['text/html', 'text/plain']);

        //响应json
        if(!$wantsHtml) {
            loop:
            try {
                $data = Json::encode([
                    'code' => $throwable->getCode(),
                    'message' => $throwable->getMessage(),
                    'data' => (object)[],
                ]);
                return $response->withHeader('content-type', 'application/json; charset=utf-8')->withBody(new SwooleStream($data));
            } catch (Throwable $exception) {
                //响应失败，再次响应
                $throwable = new ServerException(ErrorCode::getMessage(ErrorCode::SERVER_ERROR), ErrorCode::SERVER_ERROR, $exception);
                $logger->error(sprintf('%s in %s on line %d', $exception->getMessage(), $exception->getFile(), $exception->getLine()));
                goto loop;
            }
        }

        //响应网页
        if($throwable instanceof ServerException) {
            return $response->withHeader('content-type', 'text/html; charset=utf-8')->withStatus(500)->withBody(new SwooleStream($throwable->getMessage()));
        }

        return $response->withHeader('content-type', 'text/html; charset=utf-8')->withStatus(400)->withBody(new SwooleStream($throwable->getMessage()));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
