<?php

namespace App\Admin\Controllers;

use App\Models\SearchLog;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

class SearchLogController extends AdminController
{
    protected $title = '搜索日志';

    protected function grid()
    {
        return Grid::make(new SearchLog, function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id', 'ID')->sortable();
            $grid->column('keyword', '搜索关键词')->width(300);
            $grid->column('ip_address', 'IP地址')->width(140);
            $grid->column('created_at', '搜索时间')->sortable()->width(160);

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->like('keyword', '关键词');
                $filter->like('ip_address', 'IP地址');
                $filter->between('created_at', '搜索时间')->datetime();
            });

            $grid->disableCreateButton();
            $grid->disableEditButton();
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableEdit();
                $actions->disableView();
            });
        });
    }
}
