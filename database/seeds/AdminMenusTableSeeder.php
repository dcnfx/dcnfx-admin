<?php

use Illuminate\Database\Seeder;

class AdminMenusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('admin_menus')->delete();
        \DB::table('admin_menus')->insert(array (
            0 => 
                array (
                    'id'            => 1,
                    'parent_id'     => 0,
                    'order'         => 4,
                    'show'          => 1,
                    'title'         => '权限设置',
                    'icon'          => 'layui-icon-set-fill',
                    'uri'           => '/admin/qx',
                    'routes'        => 'url:',
                    'created_at'    => date('Y-m-d H:i:s',time()),
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ),
            1 =>
                array (
                    'id'            => 2,
                    'parent_id'     => 1,
                    'order'         => 5,
                    'show'          => 1,
                    'title'         => '用户管理',
                    'icon'          => 'layui-icon-friends',
                    'uri'           => '/admin/users',
                    'routes'        => 'url:/users',
                    'created_at'    => date('Y-m-d H:i:s',time()),
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ),
            2 =>
                array (
                    'id'            => 3,
                    'parent_id'     => 1,
                    'order'         => 6,
                    'show'          => 1,
                    'title'         => '角色管理',
                    'icon'          => 'layui-icon-util',
                    'uri'           => '/admin/roles',
                    'routes'        => 'url:/roles',
                    'created_at'    => date('Y-m-d H:i:s',time()),
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ),
            3 =>
                array (
                    'id'            => 4,
                    'parent_id'     => 1,
                    'order'         => 7,
                    'show'          => 1,
                    'title'         => '权限管理',
                    'icon'          => 'layui-icon-chart-screen',
                    'uri'           => '/admin/permissions',
                    'routes'        => 'url:/permissions',
                    'created_at'    => date('Y-m-d H:i:s',time()),
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ),
            4 =>
                array (
                    'id'            => 5,
                    'parent_id'     => 1,
                    'order'         => 8,
                    'show'          => 1,
                    'title'         => '菜单管理',
                    'icon'          => 'layui-icon-tabs',
                    'uri'           => '/admin/menus',
                    'routes'        => 'url:/menus',
                    'created_at'    => date('Y-m-d H:i:s',time()),
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ),
            5 =>
                array (
                    'id'            => 6,
                    'parent_id'     => 0,
                    'order'         => 1,
                    'show'          => 1,
                    'title'         => '日志设置',
                    'icon'          => 'layui-icon-layouts',
                    'uri'           => '/admin/log',
                    'routes'        => 'url:',
                    'created_at'    => date('Y-m-d H:i:s',time()),
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ),
            6 =>
                array (
                    'id'            => 7,
                    'parent_id'     => 6,
                    'order'         => 2,
                    'show'          => 1,
                    'title'         => '日志管理',
                    'icon'          => 'layui-icon-file',
                    'uri'           => '/admin/logs',
                    'routes'        => 'url:/logs',
                    'created_at'    => date('Y-m-d H:i:s',time()),
                    'updated_at'    => date('Y-m-d H:i:s',time()),
                ),
            )
        );
    }
}