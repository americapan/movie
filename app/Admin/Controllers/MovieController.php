<?php

namespace App\Admin\Controllers;

use App\Models\Movie;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class MovieController extends AdminController
{
    protected function grid()
    {
        return Grid::make(new Movie, function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('poster_url', '海报')->image('', 80, 80);
            $grid->column('title', '标题')->editable();
            $grid->column('douban_rating', '豆瓣评分');
            $grid->column('imdb_rating', 'IMDB评分');
            $grid->column('publish_date', '发布日期')->sortable();
            $grid->column('collected_at', '采集时间')->display(function ($v) {
                return $v ? $v->format('Y-m-d H:i:s') : '';
            });
            $grid->column('created_at', '创建时间')->display(function ($v) {
                return $v ? $v->format('Y-m-d H:i:s') : '';
            });

            $grid->quickSearch('title')->placeholder('请输入影视名称');

            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('title', '影视名称');
                $filter->between('publish_date', '发布日期')->date();
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
        return Show::make($id, Movie::with('detail'), function (Show $show) {
            $show->field('id');
            $show->field('title', '标题');
            $show->field('poster_url', '海报')->image();
            $show->field('source_url', '源地址')->link();
            $show->field('publish_date', '发布日期');
            $show->field('douban_rating', '豆瓣评分');
            $show->field('imdb_rating', 'IMDB评分');
            $show->field('description', '简介摘要');
            $show->field('collected_at', '采集时间');

            $show->field('detail.director', '导演');
            $show->field('detail.writers', '编剧');
            $show->field('detail.casts', '演员');
            $show->field('detail.genre', '类型');
            $show->field('detail.country', '制片国家/地区');
            $show->field('detail.language', '语言');
            $show->field('detail.release_date', '上映日期');
            $show->field('detail.runtime', '片长');
            $show->field('detail.imdb_id', 'IMDb ID');
            $show->field('detail.synopsis', '剧情简介');
            $show->field('detail.download_resources', '下载资源')->as(function ($resources) {
                if (empty($resources)) {
                    return '';
                }
                if (is_string($resources)) {
                    $resources = json_decode($resources, true);
                }

                return json_encode($resources, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            });
        });
    }

    protected function form()
    {
        return Form::make(new Movie, function (Form $form) {
            $form->display('id');
            $form->text('title', '标题')->required();
            $form->url('poster_url', '海报URL');
            $form->url('source_url', '源地址')->required();
            $form->date('publish_date', '发布日期');
            $form->number('douban_rating', '豆瓣评分');
            $form->number('imdb_rating', 'IMDB评分');
            $form->textarea('description', '简介摘要');
        });
    }
}
