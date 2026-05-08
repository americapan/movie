<?php

namespace App\Admin\Controllers;

use App\Models\VisitLog;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class VisitLogController extends AdminController
{
    protected $title = '访问日志';

    protected function grid()
    {
        return Grid::make(new VisitLog, function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id', 'ID')->sortable();
            $grid->column('ip_address', 'IP地址')->display(function ($ip) {
                $parts = [];
                if ($this->country) {
                    $parts[] = $this->country;
                }
                if ($this->region) {
                    $parts[] = $this->region;
                }
                if ($this->city) {
                    $parts[] = $this->city;
                }
                $location = $parts ? '<br><small style="color:#999;">'.implode(' ', $parts).'</small>' : '';

                return $ip.$location;
            })->width(150);
            $grid->column('url', '访问URL')->width(300)->limit(60);
            $grid->column('method', '请求方式')->width(70)->label([
                'GET' => 'success',
                'POST' => 'primary',
                'PUT' => 'warning',
                'DELETE' => 'danger',
                'PATCH' => 'info',
            ]);
            $grid->column('status_code', '状态码')->width(70)->label([
                '200' => 'success',
                '301' => 'info',
                '302' => 'info',
                '304' => 'info',
                '400' => 'warning',
                '401' => 'warning',
                '403' => 'warning',
                '404' => 'danger',
                '500' => 'danger',
                '502' => 'danger',
                '503' => 'warning',
            ]);
            $grid->column('route_name', '路由')->width(120);
            $grid->column('device_type', '设备类型')->width(80)->using([
                'desktop' => '电脑',
                'mobile' => '手机',
                'tablet' => '平板',
                'bot' => '爬虫',
                'unknown' => '未知',
            ])->label([
                'desktop' => 'primary',
                'mobile' => 'success',
                'tablet' => 'warning',
                'bot' => 'danger',
                'unknown' => 'default',
            ]);
            $grid->column('browser', '浏览器')->width(80);
            $grid->column('os', '操作系统')->width(100);
            $grid->column('referer', '来源')->width(200)->limit(40);
            $grid->column('request_duration', '耗时(ms)')->width(80)->sortable()->display(function ($v) {
                if ($v === null) {
                    return '-';
                }
                $color = $v > 1000 ? 'red' : ($v > 500 ? 'orange' : 'green');

                return "<span style='color:{$color};font-weight:bold;'>{$v}</span>";
            });
            $grid->column('created_at', '访问时间')->sortable()->width(150);

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->like('ip_address', 'IP地址');
                $filter->like('url', 'URL');
                $filter->like('country', '国家');
                $filter->like('city', '城市');
                $filter->equal('method', '请求方式')->select([
                    'GET' => 'GET',
                    'POST' => 'POST',
                    'PUT' => 'PUT',
                    'DELETE' => 'DELETE',
                ]);
                $filter->equal('status_code', '状态码')->select([
                    '200' => '200',
                    '301' => '301',
                    '404' => '404',
                    '500' => '500',
                ]);
                $filter->equal('device_type', '设备类型')->select([
                    'desktop' => '电脑',
                    'mobile' => '手机',
                    'tablet' => '平板',
                    'bot' => '爬虫',
                ]);
                $filter->equal('browser', '浏览器')->select([
                    'Chrome' => 'Chrome',
                    'Firefox' => 'Firefox',
                    'Safari' => 'Safari',
                    'Edge' => 'Edge',
                    'Opera' => 'Opera',
                    'IE' => 'IE',
                ]);
                $filter->equal('os', '操作系统')->select([
                    'Windows 10' => 'Windows 10',
                    'Windows 11' => 'Windows 11',
                    'macOS' => 'macOS',
                    'Linux' => 'Linux',
                    'Android' => 'Android',
                    'iOS' => 'iOS',
                ]);
                $filter->like('route_name', '路由');
                $filter->between('created_at', '访问时间')->datetime();
            });

            $grid->disableCreateButton();
            $grid->disableEditButton();
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableEdit();
            });
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new VisitLog, function (Show $show) {
            $show->field('id', 'ID');
            $show->field('ip_address', 'IP地址');
            $show->field('country', '国家');
            $show->field('region', '地区');
            $show->field('city', '城市');
            $show->field('url', '完整URL')->link();
            $show->field('method', '请求方式');
            $show->field('status_code', '状态码');
            $show->field('request_duration', '请求耗时(ms)');
            $show->field('route_name', '路由名称');
            $show->field('device_type', '设备类型')->as(function ($v) {
                $map = ['desktop' => '电脑', 'mobile' => '手机', 'tablet' => '平板', 'bot' => '爬虫', 'unknown' => '未知'];

                return $map[$v] ?? $v;
            });
            $show->field('browser', '浏览器');
            $show->field('browser_version', '浏览器版本');
            $show->field('os', '操作系统');
            $show->field('user_agent', 'UA')->limit(200);
            $show->field('language', '语言');
            $show->field('referer', '来源页面')->link();
            $show->field('session_id', '会话ID');
            $show->field('query_params', 'GET参数')->as(function ($v) {
                if (empty($v)) {
                    return '-';
                }

                return json_encode($v, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            });
            $show->field('created_at', '访问时间');

            $show->panel()->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableDelete();
            });
        });
    }
}
