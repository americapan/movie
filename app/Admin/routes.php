<?php

use App\Admin\Controllers\MovieController;
use App\Admin\Controllers\VisitLogController;
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
    // * 视影管理
    $router->resource('/movie', MovieController::class);

    // * 用户管理

    // 开放接口
    $router->get('openapi-docs', 'OpenApiDocsController@index');

    // 全局配置
    $router->get('web-config', 'WebConfigController@index');
    $router->post('web-config/save', 'WebConfigController@saveData');

    // 访问日志
    $router->resource('/visit-log', VisitLogController::class);
});
