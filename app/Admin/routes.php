<?php

use App\Admin\Controllers\MovieController;
use Dcat\Admin\Admin;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Admin::routes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('/member-user', MemberUserController::class);
    $router->resource('/movie', MovieController::class);

    // 开放接口
    $router->get('openapi-docs', 'OpenApiDocsController@index');

    // 全局配置
    $router->get('web-config', 'WebConfigController@index');
    $router->post('web-config/save', 'WebConfigController@saveData');
});
