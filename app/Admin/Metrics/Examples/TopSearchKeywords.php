<?php

namespace App\Admin\Metrics\Examples;

use App\Models\SearchLog;
use Dcat\Admin\Widgets\Metrics\Card;
use Illuminate\Http\Request;

class TopSearchKeywords extends Card
{
    protected function init()
    {
        parent::init();

        $this->title('热门搜索 Top 10');
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

        $top = SearchLog::where('created_at', '>=', $since)
            ->selectRaw('keyword, COUNT(*) as count')
            ->groupBy('keyword')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        if ($top->isEmpty()) {
            $this->content('<div class="text-center text-80 py-4">暂无搜索数据</div>');

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

            return "<tr><td style='width:30px;text-align:center'>{$medal}</td><td class='text-truncate' style='max-width:200px'>".e($item->keyword)."</td><td style='width:50px;text-align:right'>{$item->count}</td></tr>";
        })->join("\n");

        $this->content(<<<HTML
<div class="p-1">
    <table class="table table-borderless table-sm mb-0" style="table-layout:fixed;width:100%">
        <thead><tr><th style="width:30px"></th><th>关键词</th><th style="width:50px;text-align:right">次数</th></tr></thead>
        <tbody>{$rows}</tbody>
    </table>
</div>
HTML);
    }

    public function renderContent()
    {
        return parent::renderContent();
    }
}
