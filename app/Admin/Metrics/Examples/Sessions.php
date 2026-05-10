<?php

namespace App\Admin\Metrics\Examples;

use App\Models\VisitLog;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Metrics\Bar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Sessions extends Bar
{
    protected function init()
    {
        parent::init();

        $color = Admin::color();
        $dark35 = $color->dark35();

        $this->contentWidth(5, 7);
        $this->title('访问量');
        $this->dropdown([
            '7' => '最近7天',
            '28' => '最近28天',
            '30' => '最近30天',
            '365' => '最近一年',
        ]);
        $this->chartColors([
            $dark35,
            $dark35,
            $color->primary(),
            $dark35,
            $dark35,
            $dark35,
        ]);
    }

    public function handle(Request $request)
    {
        $days = (int) ($request->input('option', 7));
        $since = now()->subDays($days);

        $daily = VisitLog::where('created_at', '>=', $since)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $data = [];
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $data[] = $daily[$date] ?? 0;
        }

        $total = array_sum($data);

        $this->withContent(number_format($total), '+'.($data[count($data) - 1] - $data[count($data) - 2]).' vs 昨日');
        $this->withChart([
            [
                'name' => '访问量',
                'data' => $data,
            ],
        ]);
    }

    public function withChart(array $data)
    {
        return $this->chart([
            'series' => $data,
        ]);
    }

    public function withContent($title, $value, $style = 'success')
    {
        $label = strtolower(
            $this->dropdown[request()->option] ?? '最近7天'
        );

        $minHeight = '183px';

        return $this->content(
            <<<HTML
<div class="d-flex p-1 flex-column justify-content-between" style="padding-top: 0;width: 100%;height: 100%;min-height: {$minHeight}">
    <div class="text-left">
        <h1 class="font-lg-2 mt-2 mb-0">{$title}</h1>
        <h5 class="font-medium-2" style="margin-top: 10px;">
            <span class="text-{$style}">{$value} </span>
            <span>vs {$label}</span>
        </h5>
    </div>
    <a href="#" class="btn btn-primary shadow waves-effect waves-light">查看详情 <i class="feather icon-chevrons-right"></i></a>
</div>
HTML
        );
    }
}
