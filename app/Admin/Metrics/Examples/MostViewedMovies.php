<?php

namespace App\Admin\Metrics\Examples;

use App\Models\Movie;
use App\Models\VisitLog;
use Dcat\Admin\Widgets\Metrics\Card;
use Illuminate\Http\Request;

class MostViewedMovies extends Card
{
    protected function init()
    {
        parent::init();

        $this->title('热门影视 Top 10');
        $this->dropdown([
            '7' => '最近7天',
            '28' => '最近28天',
            '30' => '最近30天',
            '365' => '最近一年',
        ]);
    }

    public function handle(Request $request)
    {
        $days = (int) ($request->input('option', 7));
        $since = now()->subDays($days);

        $top = VisitLog::where('created_at', '>=', $since)
            ->where('route_name', 'movies.show')
            ->selectRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(url, '/', -1), '.', 1) as movie_id, COUNT(*) as views")
            ->groupBy('movie_id')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        if ($top->isEmpty()) {
            $this->content('<div class="text-center text-80 py-4">暂无数据</div>');

            return;
        }

        $rows = $top->map(function ($item, $index) {
            $rank = $index + 1;
            $medal = match ($rank) {
                1 => '🥇',
                2 => '🥈',
                3 => '🥉',
                default => "<span class='text-80'>{$rank}</span>",
            };

            $movie = Movie::find($item->movie_id);
            $title = $movie ? e($movie->title) : 'ID:'.$item->movie_id;

            return "<tr><td style='width:30px;text-align:center'>{$medal}</td><td class='text-truncate' style='max-width:200px' title='{$title}'>{$title}</td><td style='width:50px;text-align:right'>{$item->views}</td></tr>";
        })->join("\n");

        $this->content(<<<HTML
<div class="p-1">
    <table class="table table-borderless table-sm mb-0" style="table-layout:fixed;width:100%">
        <thead><tr><th style="width:30px"></th><th>影片</th><th style="width:50px;text-align:right">点击</th></tr></thead>
        <tbody>{$rows}</tbody>
    </table>
</div>
HTML);
    }

    public function renderContent()
    {
        $content = parent::renderContent();

        return $content;
    }
}
