<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateRbacUserTable extends Migration
{
    protected $connection = 'default';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rbac_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('account', 30)->default('')->comment('账号')->unique();
            $table->string('password')->default('')->comment('密码');
            $table->tinyInteger('is_allow')->default(\App\Model\RbacUser::ALLOW_YES)->comment('是否允许 是：1，否：2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rbac_user');
    }
}
