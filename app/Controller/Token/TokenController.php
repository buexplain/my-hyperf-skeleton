<?php

namespace App\Controller\Token;

use App\Controller\AbstractController;
use App\Services\TokenService;

class TokenController extends AbstractController
{
    /**
     * @throws \Exception
     */
    public function in()
    {
        return $this->success(TokenService::in($this->request));
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Hyperf\Constants\Exception\ConstantsException
     */
    public function out()
    {
        TokenService::out();
        return $this->success();
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Hyperf\Constants\Exception\ConstantsException
     */
    public function refresh()
    {
        return $this->success(TokenService::refresh());
    }
}