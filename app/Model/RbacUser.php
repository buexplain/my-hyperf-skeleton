<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $account 账号
 * @property string $password 密码
 * @property int $is_allow 是否允许 是：1，否：2
 */
class RbacUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rbac_user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'created_at', 'updated_at', 'account', 'password', 'is_allow'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'is_allow' => 'integer'];
    /**
     * 钩子，数据创建或更新时
     */
    public function saving()
    {
        //加密密码
        $l = strlen($this->password);
        if ($l > 0 && $l < 32) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
    }
    //是否允许
    const ALLOW_YES = 1;
    const ALLOW_NO = 2;
    public static $isAllow = [self::ALLOW_YES => '是', self::ALLOW_NO => '否'];
}