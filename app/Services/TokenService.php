<?php

declare(strict_types=1);

namespace App\Services;

use App\Exception\NoDataFoundException;
use App\Exception\UnauthorizedException;
use App\Model\RegisterUser;
use App\Services\OpLock\LimitException;
use App\Services\OpLock\LimitWaringException;
use App\Services\OpLock\OpLockService;
use Token\TokenManager;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\ApplicationContext;
use Token\Contract\TokenInterface;

class TokenService
{
    const TOKEN_KEY = 'userInfo';

    private static function secondFormat(int $s): string
    {
        if($s < 60) {
            return "{$s} 秒";
        }
        return sprintf('%d 分钟 %d 秒', (int) $s/60, (int) $s%60);
    }

    /**
     * @param RequestInterface $request
     * @return array
     * @throws \Exception
     */
    public static function in(RequestInterface $request)
    {
        $account = $request->input('account', '');
        $password = $request->input('password', '');

        //3次密码错误则锁定15分钟
        $opLock = new OpLockService($account, 3, 15*60, 'token');

        $s = $opLock->getLastLockSecond();
        if($s > 0) {
            throw new LimitException('连续输入错误密码，锁定 ' . self::secondFormat($s), 1);
        }

        /**
         * @var $result RegisterUser
         */
        $result = RegisterUser::query()->where('account', $account)->first();
        if(empty($result)) {
            throw new NoDataFoundException('账号错误');
        }

        if($result->is_allow == RegisterUser::ALLOW_NO) {
            throw new UnauthorizedException('账号已禁用');
        }

        if(!password_verify($password, $result->password)) {
            $n = $opLock->getLastOpNum();
            if($n == 0) {
                throw new LimitException('连续输入错误密码，锁定 ' . self::secondFormat($opLock->getLastLockSecond()), 1);
            }
            throw new LimitWaringException('密码错误，您还有 '.$n.' 次尝试机会', 2);
        }

        $opLock->release();

        /**
         * @var $token TokenInterface
         */
        $token = ApplicationContext::getContainer()->get(TokenManager::class)->start($request);
        $token->setId(md5((string)$result->id));
        $token->set(self::TOKEN_KEY, $result->toArray());
        $result = ['token'=>$token->getName(), 'expire_time'=>$token->getName()->expire(), 'server_time'=>time()];
        $token->save();
        return $result;
    }

    /**
     * 销毁指定用户id的token
     * @param int $id
     */
    public static function destroyById(int $id)
    {
        $container = ApplicationContext::getContainer();
        /**
         * @var $token TokenInterface
         */
        $token = $container->get(TokenManager::class)->start($container->get(RequestInterface::class));
        $token->setId(md5((string)$id));
        if($token->load()) {
            $token->destroyAll();
            $token->save();
        }
    }

    /**
     * 返回用户信息
     * @return array|null
     */
    public static function userInfo()
    {
        return ApplicationContext::getContainer()->get(TokenInterface::class)->get(self::TOKEN_KEY, null);
    }

    /**
     * 销毁token
     */
    public static function out()
    {
        ApplicationContext::getContainer()->get(TokenInterface::class)->destroyAll();
    }

    /**
     * 刷新token
     * @return array
     */
    public static function refresh()
    {
        /**
         * @var $token TokenInterface
         */
        $token = ApplicationContext::getContainer()->get(TokenInterface::class);
        return ['token'=>$token->getName()->refresh(), 'expire_time'=>$token->getName()->expire(), 'server_time'=>time()];
    }
}