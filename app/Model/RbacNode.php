<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property int $pid 父节点
 * @property string $name 节点名称
 * @property string $url 节点路径
 * @property int $is_menu 是否为菜单 是：1，否：2
 * @property int $is_parent 是否为父节点 是：1，否：2
 * @property int $sort_by 排序
 */
class RbacNode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rbac_node';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'created_at', 'updated_at', 'pid', 'name', 'url', 'is_menu', 'is_parent', 'sort_by'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'pid' => 'integer', 'is_menu' => 'integer', 'is_parent' => 'integer', 'sort_by' => 'integer'];
    //是否为菜单
    const MENU_YES = 1;
    const MENU_NO = 2;
    public static $isMenu = [self::MENU_YES => '是', self::MENU_NO => '否'];
    //是否父节点
    const PARENT_YES = 1;
    const PARENT_NO = 2;
    public static $isParent = [self::PARENT_YES => '是', self::PARENT_NO => '否'];
}