<?php

declare(strict_types=1);

namespace App\Controller\Backend\Rbac;

use App\Controller\AbstractController;
use App\Exception\InvalidArgumentException;
use App\Services\Rbac\SignService;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Session\Middleware\SessionMiddleware;
use Hyperf\View\RenderInterface;

/**
 * 登录与登出
 * @AutoController()
 * @Middleware(SessionMiddleware::class)
 */
class SignController extends AbstractController
{
    const VIEW = 'Backend.Rbac.Sign.';

    /**
     * 登录界面
     * @param RenderInterface $render
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(RenderInterface $render)
    {
        if(!empty(SignService::userInfo())) {
            return $this->response->redirect('/backend/rbac/skeleton/index');
        }
        return $render->render(self::VIEW.'index');
    }

    /**
     * 登入
     * @return \Psr\Http\Message\ResponseInterface
     * @throws InvalidArgumentException
     * @throws \Hyperf\Constants\Exception\ConstantsException
     */
    public function in()
    {
        $rules = [
            'account' => 'required',
            'password' => 'required',
        ];

        $messages = [
            'account.required' => '请输入账号',
            'password.required' => '请输入密码',
        ];

        $validator = $this->validationFactory->make($this->request->all(), $rules, $messages);

        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            throw new InvalidArgumentException($errorMessage);
        }

        try {
            if(!empty(SignService::userInfo())) {
                return $this->response->redirect('/backend/rbac/skeleton/index');
            }
            SignService::in($this->request);
            return $this->response->redirect('/backend/rbac/skeleton/index');
        }catch (\Throwable $exception) {
            return $this->jump($exception->getMessage(), '/backend/rbac/sign/index');
        }
    }

    /**
     * 登出
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function out()
    {
        SignService::out();
        return $this->response->redirect('/backend/rbac/sign/index');
    }
}