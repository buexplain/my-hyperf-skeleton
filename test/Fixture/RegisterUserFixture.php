<?php

declare(strict_types=1);

namespace HyperfTest\Fixture;

use App\Model\RegisterUser;
use Hyperf\Utils\Str;

trait RegisterUserFixture
{
    /**
     * @var RegisterUser
     */
    public static $registerUserMod;
    public static $registerUserAccount = 'localTest20200610';
    public static $registerUserPassword = '123456';

    public static function setAdminUser()
    {
        self::$registerUserPassword = Str::random(16);
        $user = RegisterUser::query()->where('account', '=',self::$registerUserAccount)->first();
        if(empty($user)) {
            $user = new RegisterUser();
            $user->account = self::$registerUserAccount;
            $user->password = self::$registerUserPassword;
            $user->save();
        }else{
            $user->password = self::$registerUserPassword;
            $user->update();
        }
        self::$registerUserMod = $user;
    }

    /**
     * @throws \Exception
     */
    public static function tearRegisterUser()
    {
        if(!empty(self::$registerUserMod)) {
            self::$registerUserMod->delete();
        }
    }
}