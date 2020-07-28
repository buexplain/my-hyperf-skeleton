<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use \App\Model\RbacUser;

class RbacUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new RbacUser();
        $user->account = 'admin';
        $user->password = '123456';
        $user->save();
//
//        for($i=0;$i<100;$i++ ) {
//            $user = new RbacUser();
//            $user->account = \Hyperf\Utils\Str::random();
//            $user->password = '123456';
//            $user->save();
//        }
    }
}
