<?php

namespace App\Admin\Metrics\Examples;

use App\Models\Movie;
use Dcat\Admin\Widgets\Metrics\Card;
use Illuminate\Http\Request;

class TotalUsers extends Card
{
    protected $footer;

    protected function init()
    {
        parent::init();

        $this->title('影视总数');
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

        $total = Movie::count();
        $recent = Movie::where('collected_at', '>=', $since)->count();

        $this->content($total);
        $this->up($recent);
    }

    public function up($percent)
    {
        return $this->footer(
            "<i class=\"feather icon-film text-success\"></i> 近期新增 {$percent} 部"
        );
    }

    public function footer($footer)
    {
        $this->footer = $footer;

        return $this;
    }

    public function renderContent()
    {
        $content = parent::renderContent();

        return <<<HTML
<div class="d-flex justify-content-between align-items-center mt-1" style="margin-bottom: 2px">
    <h2 class="ml-1 font-lg-1">{$content}</h2>
</div>
<div class="ml-1 mt-1 font-weight-bold text-80">
    {$this->renderFooter()}
</div>
HTML;
    }

    public function renderFooter()
    {
        return $this->toString($this->footer);
    }
}
