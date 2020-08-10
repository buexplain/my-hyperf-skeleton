<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateRbacNodeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rbac_node', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->bigInteger('pid')->default(0)->comment('父节点');
            $table->string('name')->default('')->comment('节点名称');
            $table->string('url')->default('')->comment('节点路径');
            $table->tinyInteger('is_menu')->default(\App\Model\RbacNode::MENU_NO)->comment('是否为菜单 是：1，否：2');
            $table->tinyInteger('is_parent')->default(\App\Model\RbacNode::PARENT_NO)->comment('是否为父节点 是：1，否：2');
            $table->integer('sort_at')->default(1991)->comment('排序');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rbac_node');
    }
}
