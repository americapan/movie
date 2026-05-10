<?php

namespace App\Admin\Metrics\Examples;

use App\Models\Movie;
use Dcat\Admin\Widgets\Metrics\Round;
use Illuminate\Http\Request;

class ProductOrders extends Round
{
    protected function init()
    {
        parent::init();

        $this->title('影视状态');
        $this->chartLabels(['有详情', '无详情', '无海报']);
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

        $total = Movie::where('collected_at', '>=', $since)->count();

        $withDetail = Movie::where('collected_at', '>=', $since)
            ->whereHas('detail')
            ->count();

        $withoutDetail = Movie::where('collected_at', '>=', $since)
            ->whereDoesntHave('detail')
            ->whereNotNull('poster_url')
            ->where('poster_url', '!=', '')
            ->count();

        $noPoster = $total - $withDetail - $withoutDetail;

        $this->withContent($withDetail, $withoutDetail, $noPoster);
        $total > 0
            ? $this->withChart([round($withDetail / $total * 100), round($withoutDetail / $total * 100), round($noPoster / $total * 100)])
            : $this->withChart([0, 0, 0]);
        $this->chartTotal('总计', $total);
    }

    public function withChart(array $data)
    {
        return $this->chart([
            'series' => $data,
        ]);
    }

    public function withContent($finished, $pending, $rejected)
    {
        return $this->content(
            <<<HTML
<div class="col-12 d-flex flex-column flex-wrap text-center" style="max-width: 220px">
    <div class="chart-info d-flex justify-content-between mb-1 mt-2" >
          <div class="series-info d-flex align-items-center">
              <i class="fa fa-circle-o text-bold-700 text-primary"></i>
              <span class="text-bold-600 ml-50">有详情</span>
          </div>
          <div class="product-result">
              <span>{$finished}</span>
          </div>
    </div>
    <div class="chart-info d-flex justify-content-between mb-1">
          <div class="series-info d-flex align-items-center">
              <i class="fa fa-circle-o text-bold-700 text-warning"></i>
              <span class="text-bold-600 ml-50">无详情</span>
          </div>
          <div class="product-result">
              <span>{$pending}</span>
          </div>
    </div>
     <div class="chart-info d-flex justify-content-between mb-1">
          <div class="series-info d-flex align-items-center">
              <i class="fa fa-circle-o text-bold-700 text-danger"></i>
              <span class="text-bold-600 ml-50">无海报</span>
          </div>
          <div class="product-result">
              <span>{$rejected}</span>
          </div>
    </div>
</div>
HTML
        );
    }
}
