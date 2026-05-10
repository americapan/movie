<?php

namespace Tests\Unit;

use App\Models\Movie;
use App\Models\MovieDetail;
use App\Services\MovieScraperService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MovieScraperServiceTest extends TestCase
{
    use RefreshDatabase;

    private MovieScraperService $scraper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scraper = new MovieScraperService;
    }

    public function test_scrape_list_page_parses_movie_links(): void
    {
        Http::fake([
            'example.com/movie/' => Http::response(<<<'HTML'
<html><body>
<div class="movie-list">
    <a href="/movie/2025/0501/1234.html"><img src="https://example.com/poster1.jpg" alt="测试电影一">测试电影一</a>
    <span>豆瓣：8.5 IMDB：7.2 2025-01-15</span>
</div>
<div class="movie-list">
    <a href="/movie/2025/0502/5678.html"><img src="https://example.com/poster2.jpg" alt="测试电影二">测试电影二</a>
    <span>豆瓣：9.0 2025-03-20</span>
</div>
</body></html>
HTML),
        ]);

        $result = $this->scraper->scrapeListPage('https://example.com/movie/');

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('测试电影一', $result[0]['title']);
        $this->assertEquals('https://www.rrdynb.com/movie/2025/0501/1234.html', $result[0]['source_url']);
        $this->assertEquals(8.5, $result[0]['douban_rating']);
    }

    public function test_scrape_list_page_skips_invalid_hrefs(): void
    {
        Http::fake([
            'example.com/movie/' => Http::response(<<<'HTML'
<html><body>
<a href="/movie/2025/0601/123.html">有效链接</a>
<a href="/other/">无效链接</a>
<a href="javascript:void(0)">JS链接</a>
</body></html>
HTML),
        ]);

        $result = $this->scraper->scrapeListPage('https://example.com/movie/');

        $this->assertCount(1, $result);
    }

    public function test_scrape_list_page_returns_empty_for_failed_request(): void
    {
        Http::fake([
            'example.com/404' => Http::response('', 404),
        ]);

        $result = $this->scraper->scrapeListPage('https://example.com/404');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test_scrape_detail_page_parses_fields(): void
    {
        Http::fake([
            'example.com/movie/1' => Http::response(<<<'HTML'
<html><body>
<div class="movie-txt">
导演：张三
编剧：李四
主演：王五 / 赵六
类型：动作 / 科幻
制片国家/地区：中国大陆
语言：汉语普通话
上映日期：2025-06-01
片长：120分钟
IMDb：tt1234567
剧情简介：这是一部很精彩的测试电影，讲述了一个关于勇气和希望的故事。
</div>
</body></html>
HTML),
        ]);

        $result = $this->scraper->scrapeDetailPage('https://example.com/movie/1');

        $this->assertIsArray($result);
        $this->assertEquals('张三', $result['director']);
        $this->assertEquals('李四', $result['writers']);
        $this->assertEquals('王五 / 赵六', $result['casts']);
        $this->assertEquals('动作 / 科幻', $result['genre']);
        $this->assertStringContainsString('精彩的测试电影', $result['synopsis']);
    }

    public function test_scrape_detail_page_extracts_download_resources(): void
    {
        Http::fake([
            'example.com/movie/2' => Http::response(<<<'HTML'
<html><body>
<div class="movie-txt">
导演：导演名
剧情简介：测试剧情内容，这是一个足够长的描述来确保通过字数检查。额外文字填充到超过二十个字。
<a href="https://pan.baidu.com/s/abc123">百度网盘</a>
<a href="https://pan.quark.cn/s/xyz789">夸克网盘</a>
</div>
</body></html>
HTML),
        ]);

        $result = $this->scraper->scrapeDetailPage('https://example.com/movie/2');

        $this->assertIsArray($result);
        $this->assertNotEmpty($result['download_resources']);
        $names = array_column($result['download_resources'], 'name');
        $this->assertContains('百度网盘', $names);
    }

    public function test_movie_saved_to_database(): void
    {
        $this->assertDatabaseCount('movies', 0);

        Movie::create([
            'title' => '测试电影',
            'source_url' => 'https://example.com/movie/test1',
            'publish_date' => '2025-05-01',
            'douban_rating' => 8.0,
            'collected_at' => now(),
        ]);

        $this->assertDatabaseCount('movies', 1);
        $this->assertDatabaseHas('movies', ['title' => '测试电影']);
    }

    public function test_movie_detail_cascade_deletes_with_movie(): void
    {
        $movie = Movie::factory()->create();
        MovieDetail::factory()->create(['movie_id' => $movie->id]);

        $this->assertDatabaseCount('movie_details', 1);

        $movie->delete();

        $this->assertDatabaseCount('movie_details', 0);
    }
}
