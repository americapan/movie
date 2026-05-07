<?php

use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Layout\Menu;

/**
 * Dcat-admin - admin builder based on Laravel.
 *
 * @author jqh <https://github.com/jqhph>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 *
 * extend custom field:
 * Dcat\Admin\Form::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Column::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Filter::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 */
Admin::menu(function (Menu $menu) {
    $menu->add([
        [
            'id' => 1,
            'title' => '用户中心',
            'icon' => 'feather icon-users',
            'uri' => '',
            'parent_id' => 0,
        ],
        [
            'id' => 2,
            'title' => '用户管理',
            'icon' => 'feather icon-user',
            'uri' => 'member-user',
            'parent_id' => 1,
        ],
        [
            'id' => 3,
            'title' => '网站配置',
            'icon' => 'feather icon-settings',
            'uri' => 'web-config',
            'parent_id' => 0,
        ],
        [
            'id' => 4,
            'title' => '开放接口文档',
            'icon' => 'feather icon-layers',
            'uri' => '/openapi-docs',
            'parent_id' => 0,
        ],
        [
            'id' => 5,
            'title' => '影视管理',
            'icon' => 'feather icon-film',
            'uri' => 'movie',
            'parent_id' => 0,
        ],
    ]);
});
