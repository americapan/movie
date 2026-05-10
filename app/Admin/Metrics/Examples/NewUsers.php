<?php

namespace App\Admin\Metrics\Examples;

use App\Models\Movie;
use Dcat\Admin\Widgets\Metrics\Line;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewUsers extends Line
{
    protected function init()
    {
        parent::init();

        $this->title('新增影视');
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

        $daily = Movie::where('collected_at', '>=', $since)
            ->select(DB::raw('DATE(collected_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $data = [];
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $data[] = $daily[$date] ?? 0;
        }

        $total = array_sum($data);

        $this->withContent($total);
        $this->withChart($data);
    }

    public function withChart(array $data)
    {
        return $this->chart([
            'series' => [
                [
                    'name' => $this->title,
                    'data' => $data,
                ],
            ],
        ]);
    }

    public function withContent($content)
    {
        return $this->content(
            <<<HTML
<div class="d-flex justify-content-between align-items-center mt-1" style="margin-bottom: 2px">
    <h2 class="ml-1 font-lg-1">{$content}</h2>
    <span class="mb-0 mr-1 text-80">{$this->title}</span>
</div>
HTML
        );
    }
}
