<?php

declare(strict_types=1);

namespace App\Middleware\HttpServer;

use App\Constants\ErrorCode;
use App\Exception\NoRouteFoundException;
use Hyperf\Utils\Contracts\Arrayable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * 自定义 CoreMiddleWare 的行为
 * Class CoreMiddleware
 * @package App\Middleware\HttpServer
 */
class CoreMiddleware extends \Hyperf\HttpServer\CoreMiddleware
{
    /**
     * Handle the response when cannot found any routes.
     *
     * @return array|Arrayable|mixed|ResponseInterface|string
     */
    protected function handleNotFound(ServerRequestInterface $request)
    {
        throw new NoRouteFoundException();
    }

    /**
     * Handle the response when the routes found but doesn't match any available methods.
     *
     * @return array|Arrayable|mixed|ResponseInterface|string
     */
    protected function handleMethodNotAllowed(array $methods, ServerRequestInterface $request)
    {
        $message = ErrorCode::getMessage(ErrorCode::CLIENT_ERROR_NOT_FOUND_ROUTE).'，允许：'.implode('、', $methods);
        throw new NoRouteFoundException($message);
    }
}