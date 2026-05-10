<?php

namespace App\Admin\Metrics\Examples;

use App\Models\VisitLog;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Metrics\Donut;

class NewDevices extends Donut
{
    protected $labels = ['桌面端', '移动端'];

    protected function init()
    {
        parent::init();

        $color = Admin::color();
        $colors = [$color->primary(), $color->alpha('blue2', 0.5)];

        $this->title('设备分布');
        $this->subTitle('最近30天');
        $this->chartLabels($this->labels);
        $this->chartColors($colors);
    }

    public function render()
    {
        $this->fill();

        return parent::render();
    }

    public function fill()
    {
        $since = now()->subDays(30);
        $total = VisitLog::where('created_at', '>=', $since)->count();

        $desktop = $total > 0
            ? round(VisitLog::where('created_at', '>=', $since)->where('device_type', 'desktop')->count() / $total * 100, 1)
            : 0;
        $mobile = $total > 0 ? round(100 - $desktop, 1) : 0;

        $this->withContent($desktop, $mobile);
        $this->withChart([$desktop, $mobile]);
    }

    public function withChart(array $data)
    {
        return $this->chart([
            'series' => $data,
        ]);
    }

    protected function withContent($desktop, $mobile)
    {
        $blue = Admin::color()->alpha('blue2', 0.5);

        $style = 'margin-bottom: 8px';
        $labelWidth = 120;

        return $this->content(
            <<<HTML
<div class="d-flex pl-1 pr-1 pt-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle text-primary"></i> {$this->labels[0]}
    </div>
    <div>{$desktop}%</div>
</div>
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle" style="color: $blue"></i> {$this->labels[1]}
    </div>
    <div>{$mobile}%</div>
</div>
HTML
        );
    }
}
