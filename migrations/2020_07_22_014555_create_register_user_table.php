<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateRegisterUserTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('register_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('account', 30)->default('')->comment('账号')->unique();
            $table->string('password')->default('')->comment('密码');
            $table->tinyInteger('is_allow')->default(\App\Model\RegisterUser::ALLOW_YES)->comment('是否允许 是：1，否：2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('register_user');
    }
}
