<?php

namespace App\Console\Commands;

use App\Models\Movie;
use App\Models\MovieDetail;
use DOMDocument;
use Illuminate\Console\Command;

class ScrapeMoviesCommand extends Command
{
    protected $signature = 'scrape:movies';

    protected $description = 'Scrape movie data from rrdynb.com';

    private $baseUrl = 'https://www.rrdynb.com';

    private $listUrl = 'https://www.rrdynb.com/movie/';

    public function handle()
    {
        $this->info('Starting movie scraping...');

        for ($page = 1; $page <= 5; $page++) {
            $url = $page === 1 ? $this->listUrl : "https://www.rrdynb.com/movie/list_2_{$page}.html";
            $this->info("Scraping list page {$page}: {$url}");

            $movies = $this->scrapeListPage($url);
            $this->info('Found '.count($movies)." movies on page {$page}");

            foreach ($movies as $movieData) {
                $movie = $this->saveOrUpdateMovie($movieData);
                if ($movie && ! $movie->detail) {
                    $this->info("Scraping detail for: {$movie->title}");
                    $detailData = $this->scrapeDetailPage($movieData['source_url']);
                    if ($detailData) {
                        $this->saveOrUpdateDetail($movie, $detailData);
                    }
                    usleep(500000);
                }
            }

            usleep(500000);
        }

        $this->info('Movie scraping completed.');
    }

    private function fetchPage(string $url): string
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36\r\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\nAccept-Language: zh-CN,zh;q=0.9,en;q=0.8\r\n",
                'timeout' => 30,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        return @file_get_contents($url, false, $context) ?: '';
    }

    private function scrapeListPage(string $url): array
    {
        $html = $this->fetchPage($url);
        if (empty($html)) {
            return [];
        }

        $encoding = mb_detect_encoding($html, ['UTF-8', 'GBK', 'GB2312'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            $html = mb_convert_encoding($html, 'UTF-8', $encoding);
        }

        libxml_use_internal_errors(true);
        $dom = new DOMDocument;
        @$dom->loadHTML('<?xml encoding="UTF-8">'.$html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $links = $xpath->query('//a[contains(@href, "/movie/")]');

        $movies = [];
        $seen = [];

        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            if (! preg_match('#^/movie/\d{4}/\d{4}/\d+\.html$#', $href)) {
                continue;
            }
            if (isset($seen[$href])) {
                continue;
            }
            $seen[$href] = true;

            $itemNode = $link;
            for ($i = 0; $i < 5 && $itemNode; $i++) {
                $itemNode = $itemNode->parentNode;
            }

            $title = trim($link->textContent);
            if (empty($title)) {
                $img = $link->getElementsByTagName('img')->item(0);
                $title = $img ? trim($img->getAttribute('alt')) : '';
            }

            $posterUrl = '';
            $img = $link->getElementsByTagName('img')->item(0);
            if ($img) {
                $posterUrl = $img->getAttribute('data-original') ?: $img->getAttribute('src') ?: $img->getAttribute('data-src') ?: '';
            }

            $sourceUrl = $this->baseUrl.$href;

            $publishDate = '';
            $doubanRating = null;
            $imdbRating = null;
            $description = '';

            $parentText = '';
            if ($itemNode) {
                $parentText = $itemNode->textContent;
            }
            if (empty($parentText) && $link->parentNode) {
                $parentText = $link->parentNode->textContent;
            }

            if (preg_match('/(\d{4}-\d{2}-\d{2})/', $parentText, $m)) {
                $publishDate = $m[1];
            }
            if (preg_match('/豆瓣[：:]\s*[\*\*]*([\d\.]+)/u', $parentText, $m)) {
                $doubanRating = (float) $m[1];
            }
            if (preg_match('/IMDB[：:]\s*[\*\*]*([\d\.]+)/u', $parentText, $m)) {
                $imdbRating = (float) $m[1];
            }

            $descText = strip_tags($parentText);
            $descText = preg_replace('/资源下载：.*$/u', '', $descText);
            $descText = preg_replace('/豆瓣[：:].*$/u', '', $descText);
            $descText = preg_replace('/IMDB[：:].*$/u', '', $descText);
            $descText = preg_replace('/\d{4}-\d{2}-\d{2}/', '', $descText);
            $descLines = explode("\n", trim($descText));
            foreach ($descLines as $line) {
                $line = trim($line);
                if (! empty($line) && mb_strlen($line) > 5) {
                    $description = mb_substr($line, 0, 500);
                    break;
                }
            }

            $movies[] = [
                'title' => $title ?: 'Untitled',
                'poster_url' => $posterUrl,
                'source_url' => $sourceUrl,
                'publish_date' => $publishDate ?: null,
                'douban_rating' => $doubanRating,
                'imdb_rating' => $imdbRating,
                'description' => $description,
            ];
        }

        return $movies;
    }

    private function scrapeDetailPage(string $url): ?array
    {
        $html = $this->fetchPage($url);
        if (empty($html)) {
            return null;
        }

        $encoding = mb_detect_encoding($html, ['UTF-8', 'GBK', 'GB2312'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            $html = mb_convert_encoding($html, 'UTF-8', $encoding);
        }

        libxml_use_internal_errors(true);
        $dom = new DOMDocument;
        @$dom->loadHTML('<?xml encoding="UTF-8">'.$html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);

        $contentText = '';
        $contentNodes = $xpath->query('//div[contains(@class, "movie-txt")]');
        if ($contentNodes->length > 0) {
            $contentText = $contentNodes->item(0)->textContent;
        }
        if (empty(trim($contentText))) {
            $bodyNodes = $xpath->query('//body');
            if ($bodyNodes->length > 0) {
                $contentText = $bodyNodes->item(0)->textContent;
            }
        }

        $data = [
            'director' => $this->extractField($contentText, '导演'),
            'writers' => $this->extractField($contentText, '编剧'),
            'casts' => $this->extractField($contentText, '主演'),
            'genre' => $this->extractField($contentText, '类型'),
            'country' => $this->extractField($contentText, '制片国家\/地区'),
            'language' => $this->extractField($contentText, '语言'),
            'release_date' => $this->extractField($contentText, '上映日期'),
            'runtime' => $this->extractField($contentText, '片长'),
            'imdb_id' => $this->extractField($contentText, 'IMDb'),
            'synopsis' => $this->extractSynopsis($contentText),
            'download_resources' => $this->extractDownloadResources($html, $xpath, $contentText),
        ];

        if (empty($data['director']) && empty($data['casts']) && empty($data['synopsis']) && empty($data['download_resources'])) {
            return null;
        }

        return $data;
    }

    private function extractField(string $text, string $field): string
    {
        if (preg_match('/'.$field.'[：:\s]*\n?(.+?)(?:\n|$)/u', $text, $m)) {
            $val = trim($m[1]);
            $val = preg_replace('/^[\s\x{00a0}]+|[\s\x{00a0}]+$/u', '', $val);
            if ($val !== '' && $val !== '内详' && $val !== '未知' && $val !== '不详') {
                return $val;
            }
            if (in_array($field, ['导演', '编剧', '主演', '制片国家\/地区'])) {
                return $val;
            }
        }

        return '';
    }

    private function extractSynopsis(string $text): string
    {
        if (preg_match('/剧情简介[：:\s]*\n*/us', $text, $mHeader, PREG_OFFSET_CAPTURE)) {
            $startPos = $mHeader[0][1] + strlen($mHeader[0][0]);
            $afterHeader = substr($text, $startPos);

            $stopPatterns = [
                '(?:^|\n)\s*资源[：:]',
                '(?:^|\n)\s*提取码[：:]',
                '(?:^|\n)\s*\S+\.(?:mp4|mkv|avi|rmvb|ts|wmv)[\s（(]',
                '(?:^|\n)\s*ed2k://',
                '(?:^|\n)\s*magnet:',
                '(?:^|\n)\s*资源下载[：:]',
            ];
            $stopPattern = '#'.implode('|', $stopPatterns).'#us';

            if (preg_match($stopPattern, $afterHeader, $mStop, PREG_OFFSET_CAPTURE)) {
                $afterHeader = substr($afterHeader, 0, $mStop[0][1]);
            }

            $synopsis = trim($afterHeader);
            $synopsis = preg_replace('/\n{2,}/', "\n", $synopsis);
            $synopsis = preg_replace('/^[\s\x{00a0}]+|[\s\x{00a0}]+$/u', '', $synopsis);

            if (mb_strlen(strip_tags($synopsis)) > 20) {
                return $synopsis;
            }
        }

        return '';
    }

    private function extractDownloadResources(string $html, \DOMXPath $xpath, string $contentText): array
    {
        $resources = [];

        $links = $xpath->query('//a[contains(@href, "aliyundrive.com") or contains(@href, "quark.cn") or contains(@href, "115.com") or contains(@href, "xunlei.com") or contains(@href, "pan.baidu.com/s/")]');

        $seen = [];
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            if (empty($href) || $href === '#' || strpos($href, 'javascript:') === 0) {
                continue;
            }
            if (isset($seen[$href])) {
                continue;
            }
            $seen[$href] = true;

            $name = trim($link->textContent);
            if (mb_strlen($name) > 50) {
                $name = mb_substr($name, 0, 50);
            }

            if (empty($name)) {
                if (strpos($href, 'aliyundrive.com') !== false) {
                    $name = '阿里网盘';
                } elseif (strpos($href, 'quark.cn') !== false) {
                    $name = '夸克网盘';
                } elseif (strpos($href, '115.com') !== false) {
                    $name = '115云盘';
                } elseif (strpos($href, 'xunlei.com') !== false) {
                    $name = '迅雷云盘';
                } elseif (strpos($href, 'pan.baidu.com') !== false) {
                    $name = '百度网盘';
                } else {
                    $name = parse_url($href, PHP_URL_HOST) ?: '下载链接';
                }
            }

            $resources[] = [
                'name' => $this->normalizeResourceName($name),
                'url' => $href,
            ];
        }

        if (empty($resources)) {
            $resources = $this->extractResourcesFromText($contentText);
        }

        return $resources;
    }

    private function normalizeResourceName(string $name): string
    {
        $name = trim($name);
        if (preg_match('/^资源[：:]\s*/u', $name)) {
            return $name;
        }
        $map = [
            '阿里' => '阿里网盘', 'aliyun' => '阿里网盘', 'aliyundrive' => '阿里网盘',
            '夸克' => '夸克网盘', 'quark' => '夸克网盘',
            '115' => '115云盘', '115网盘' => '115云盘',
            '迅雷' => '迅雷云盘', 'xunlei' => '迅雷云盘',
            '百度' => '百度网盘', 'baidu' => '百度网盘',
        ];
        foreach ($map as $key => $label) {
            if (mb_stripos($name, $key) !== false) {
                return $label;
            }
        }

        return $name;
    }

    private function extractResourcesFromText(string $text): array
    {
        $resources = [];
        if (preg_match_all('/资源[：:]\s*([^\n]+)/u', $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $line = trim($m[1]);
                $url = '';
                if (preg_match('/(https?:\/\/[^\s]+)/u', $line, $urlMatch)) {
                    $url = $urlMatch[1];
                    $line = trim(str_replace($urlMatch[0], '', $line));
                }
                $name = $line;
                if (preg_match('/提取码[：:]\s*([^\s]+)/u', $line, $codeMatch)) {
                    $name = trim(str_replace($codeMatch[0], '', $line));
                }
                if (empty($name)) {
                    continue;
                }
                $name = $this->normalizeResourceName($name);
                $seenNames = array_column($resources, 'name');
                if (! in_array($name, $seenNames)) {
                    $resources[] = ['name' => $name, 'url' => $url];
                }
            }
        }

        return $resources;
    }

    private function saveOrUpdateMovie(array $data): ?Movie
    {
        $movie = Movie::where('source_url', $data['source_url'])->first();

        if ($movie) {
            $movie->update(array_merge($data, ['collected_at' => now()]));
        } else {
            $movie = Movie::create(array_merge($data, ['collected_at' => now()]));
        }

        return $movie;
    }

    private function saveOrUpdateDetail(Movie $movie, array $data): void
    {
        $detailData = array_merge($data, [
            'collected_at' => now(),
        ]);

        MovieDetail::updateOrCreate(
            ['movie_id' => $movie->id],
            $detailData
        );
    }
}
