<?php

declare(strict_types=1);

namespace App\Services\Rbac;

use App\Exception\NoDataFoundException;
use App\Exception\UnauthorizedException;
use App\Model\RbacUser;
use App\Services\OpLock\LimitException;
use App\Services\OpLock\LimitWaringException;
use App\Services\OpLock\OpLockService;
use Hyperf\Contract\SessionInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\ApplicationContext;

class SignService
{
    const SESSION_KEY = 'userInfo';

    /**
     * 返回用户信息
     * @return array|null
     */
    public static function userInfo()
    {
        return ApplicationContext::getContainer()->get(SessionInterface::class)->get(self::SESSION_KEY, null);
    }

    private static function secondFormat(int $s): string
    {
        if($s < 60) {
            return "{$s} 秒";
        }
        return sprintf('%d 分钟 %d 秒', (int) $s/60, (int) $s%60);
    }

    public static function in(RequestInterface $request)
    {
        $account = $request->input('account', '');
        $password = $request->input('password', '');

        //3次密码错误则锁定15分钟
        $opLock = new OpLockService($account, 3, 15*60, 'backend');

        $s = $opLock->getLastLockSecond();
        if($s > 0) {
            throw new LimitException('连续输入错误密码，锁定 ' . self::secondFormat($s), 1);
        }

        /**
         * @var $result RbacUser
         */
        $result = RbacUser::query()->where('account', $account)->first();
        if(empty($result)) {
            throw new NoDataFoundException('账号错误');
        }

        if($result->is_allow == RbacUser::ALLOW_NO) {
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

        //写入到session
        ApplicationContext::getContainer()->get(SessionInterface::class)->set(self::SESSION_KEY, $result->toArray());

        return $result;
    }

    /**
     * 退出登录
     */
    public static function out()
    {
        ApplicationContext::getContainer()->get(SessionInterface::class)->clear();
    }
}