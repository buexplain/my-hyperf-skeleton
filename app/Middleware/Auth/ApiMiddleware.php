<?php

declare(strict_types=1);

namespace App\Middleware\Auth;

use App\Exception\UnauthorizedException;
use Hyperf\Utils\ApplicationContext;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Token\Contract\TokenInterface;
use Token\Middleware\AuthMiddleware;

/**
 * token的权限校验中间件
 * @package App\Middleware\Auth
 */
class ApiMiddleware extends AuthMiddleware
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws UnauthorizedException
     * @throws \Hyperf\Constants\Exception\ConstantsException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /**
         * @var $token TokenInterface
         */
        $token = ApplicationContext::getContainer()->get(TokenInterface::class);
        if(!$token->isValid()) {
            throw new UnauthorizedException();
        }
        return $handler->handle($request);
    }
}