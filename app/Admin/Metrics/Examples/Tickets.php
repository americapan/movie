<?php

namespace App\Admin\Metrics\Examples;

use App\Models\Movie;
use Dcat\Admin\Widgets\Metrics\RadialBar;
use Illuminate\Http\Request;

class Tickets extends RadialBar
{
    protected function init()
    {
        parent::init();

        $this->title('豆瓣评分');
        $this->height(400);
        $this->chartHeight(300);
        $this->chartLabels('有评分率');
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

        $withRating = Movie::where('collected_at', '>=', $since)
            ->whereNotNull('douban_rating')
            ->where('douban_rating', '>', 0)
            ->count();

        $total = Movie::where('collected_at', '>=', $since)->count();
        $pct = $total > 0 ? round($withRating / $total * 100) : 0;

        $this->withContent($withRating);
        $this->withFooter(
            Movie::where('collected_at', '>=', now()->subDay())->count(),
            Movie::where('collected_at', '<', now()->subDay())->where('collected_at', '>=', now()->subDays(2))->count(),
            '1d'
        );
        $this->withChart($pct);
    }

    public function withChart(int $data)
    {
        return $this->chart([
            'series' => [$data],
        ]);
    }

    public function withContent($content)
    {
        return $this->content(
            <<<HTML
<div class="d-flex flex-column flex-wrap text-center">
    <h1 class="font-lg-2 mt-2 mb-0">{$content}</h1>
    <small>有豆瓣评分影视</small>
</div>
HTML
        );
    }

    public function withFooter($new, $open, $response)
    {
        return $this->footer(
            <<<HTML
<div class="d-flex justify-content-between p-1" style="padding-top: 0!important;">
    <div class="text-center">
        <p>昨日新增</p>
        <span class="font-lg-1">{$new}</span>
    </div>
    <div class="text-center">
        <p>前日新增</p>
        <span class="font-lg-1">{$open}</span>
    </div>
    <div class="text-center">
        <p>环比变化</p>
        <span class="font-lg-1">{$response}</span>
    </div>
</div>
HTML
        );
    }
}
