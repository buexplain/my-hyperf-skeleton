<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use \App\Model\RegisterUser;

class RegisterUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new RegisterUser();
        $user->account = 'test';
        $user->password = '123456';
        $user->save();
    }
}
