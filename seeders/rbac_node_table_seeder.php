<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use App\Model\RbacNode;

class RbacNodeTableSeeder extends Seeder
{

    public static $nodes = [
        [
            'url'=>'/backend/home/home/index',
            'name'=>'我的桌面',
            'is_menu'=>RbacNode::MENU_YES,
            'children' => [],
        ],
        [
            'url'=>'javascript:;',
            'name'=>'权限管理',
            'is_menu'=>RbacNode::MENU_YES,
            'children' => [
                [
                    'url'=>'/backend/rbac/node/index',
                    'name'=>'节点管理',
                    'is_menu'=>RbacNode::MENU_YES,
                    'children' => [
                        [
                            'url'=>'/backend/home/node/create',
                            'name'=>'新增',
                            'is_menu'=>RbacNode::MENU_NO,
                        ],
                        [
                            'url'=>'/backend/home/node/store',
                            'name'=>'保存',
                            'is_menu'=>RbacNode::MENU_NO,
                        ],
                        [
                            'url'=>'/backend/home/node/edit',
                            'name'=>'编辑',
                            'is_menu'=>RbacNode::MENU_NO,
                        ],
                        [
                            'url'=>'/backend/home/node/update',
                            'name'=>'更新',
                            'is_menu'=>RbacNode::MENU_NO,
                        ],
                        [
                            'url'=>'/backend/home/node/destroy',
                            'name'=>'删除',
                            'is_menu'=>RbacNode::MENU_NO,
                        ],
                        [
                            'url'=>'/backend/home/node/lists',
                            'name'=>'返回父级节点',
                            'is_menu'=>RbacNode::MENU_NO,
                        ],
                        [
                            'url'=>'/backend/home/node/move',
                            'name'=>'移动节点',
                            'is_menu'=>RbacNode::MENU_NO,
                        ],
                    ],
                ],
                [
                    'url'=>'/backend/rbac/user/index',
                    'name'=>'用户管理',
                    'is_menu'=>RbacNode::MENU_YES,
                    'children' => [
                        [
                            'url'=>'/backend/rbac/user/create',
                            'name'=>'新增',
                            'is_menu'=>RbacNode::MENU_NO,
                        ],
                        [
                            'url'=>'/backend/rbac/user/store',
                            'name'=>'保存',
                            'is_menu'=>RbacNode::MENU_NO,
                        ],
                        [
                            'url'=>'/backend/rbac/user/edit',
                            'name'=>'编辑',
                            'is_menu'=>RbacNode::MENU_NO,
                        ],
                        [
                            'url'=>'/backend/rbac/user/update',
                            'name'=>'更新',
                            'is_menu'=>RbacNode::MENU_NO,
                        ],
                    ],
                ],
            ],
        ],
        [
            'url'=>'/backend/register_user/register_user/index',
            'name'=>'用户管理',
            'is_menu'=>RbacNode::MENU_YES,
            'children' => [
                [
                    'url'=>'/backend/register_user/register_user/create',
                    'name'=>'新增',
                    'is_menu'=>RbacNode::MENU_NO,
                ],
                [
                    'url'=>'/backend/register_user/register_user/store',
                    'name'=>'保存',
                    'is_menu'=>RbacNode::MENU_NO,
                ],
                [
                    'url'=>'/backend/register_user/register_user/edit',
                    'name'=>'编辑',
                    'is_menu'=>RbacNode::MENU_NO,
                ],
                [
                    'url'=>'/backend/register_user/register_user/update',
                    'name'=>'更新',
                    'is_menu'=>RbacNode::MENU_NO,
                ],
            ],
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->handleNodes(self::$nodes);
    }

    public function handleNodes($nodes, $pid=0)
    {
        foreach ($nodes as $node) {
            $children = [];
            if(isset($node['children'])) {
                $children = $node['children'];
                unset($node['children']);
            }
            $mod = new RbacNode();
            $mod->fill($node);
            $mod->pid = $pid;
            if(!empty($children)) {
                $mod->is_parent = RbacNode::PARENT_YES;
            }
            $mod->save();
            if(is_array($children) && count($children) > 0) {
                $this->handleNodes($children, $mod->id);
            }
        }
    }
}
