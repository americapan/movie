<?php

namespace App\Admin\Controllers;

use App\Admin\Metrics\Examples;
use App\Http\Controllers\Controller;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Alert;
use Dcat\Admin\Widgets\ListGroup;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $alert = Alert::make('影视数据采集系统 · 数据每日自动更新 · <a href="/admin/movie">管理影视</a> | <a href="/admin/visit-log">访问日志</a>', '数据看板');

        $content = $content
            ->header('数据看板')
            ->description('影视资源聚合站数据概览')
            ->row($alert->info())
            ->row(function (Row $row) {
                $row->column(4, new Examples\TotalUsers);
                $row->column(4, new Examples\NewUsers);
                $row->column(4, new Examples\Sessions);
            })
            ->row(function (Row $row) {
                $row->column(4, new Examples\Tickets);
                $row->column(4, new Examples\NewDevices);
                $row->column(4, new Examples\ProductOrders);
            })
            ->row(function (Row $row) {
                $row->column(6, new Examples\MostViewedMovies);
                $row->column(6, new Examples\TopSearchKeywords);
            })
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $group = ListGroup::make();

                    $packageName = 'dcat-plus/laravel-admin';
                    $installedPackages = json_decode(file_get_contents(base_path('vendor/composer/installed.json')), true);
                    $packageVersion = '--';
                    if (! empty($installedPackages['packages'])) {
                        foreach ($installedPackages['packages'] as $package) {
                            if ($package['name'] === $packageName) {
                                $packageVersion = $package['version'];
                                break;
                            }
                        }
                    }

                    $group->add('Dcat-plus Admin', $packageVersion, '#');
                    $group->add('PHP', phpversion(), '#');
                    $group->add('Laravel', app()->version(), '#');

                    $dbType = config('database.default', env('DB_CONNECTION', 'unknown'));
                    $dbVersion = '--';
                    try {
                        if ($dbType === 'sqlite') {
                            $dbVersion = DB::selectOne('select sqlite_version() as version')->version ?? '--';
                        } elseif ($dbType === 'pgsql') {
                            $dbVersion = DB::selectOne('show server_version')->server_version ?? '--';
                        } elseif ($dbType === 'sqlsrv') {
                            $dbVersion = DB::selectOne("SELECT CAST(SERVERPROPERTY('ProductVersion') AS VARCHAR(50)) AS version")->version ?? '--';
                        } else {
                            $dbVersion = DB::selectOne('select version() as version')->version ?? '--';
                        }
                        if ($dbVersion !== '--' && preg_match('/\d+(?:\.\d+){1,3}/', (string) $dbVersion, $matches)) {
                            $dbVersion = $matches[0];
                        }
                    } catch (\Throwable $e) {
                        $dbVersion = '--';
                    }

                    $group->add('数据库', $dbType.' '.$dbVersion, '#');

                    $column->row($group);
                });
            });

        return $content;
    }
}
