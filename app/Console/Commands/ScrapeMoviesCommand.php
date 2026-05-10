<?php

namespace App\Console\Commands;

use App\Services\MovieScraperService;
use Illuminate\Console\Command;

class ScrapeMoviesCommand extends Command
{
    protected $signature = 'scrape:movies';

    protected $description = 'Scrape movie data from rrdynb.com';

    public function handle(MovieScraperService $scraper)
    {
        $this->info('Starting movie scraping...');

        $scraper->scrapeList();

        $this->info('Movie scraping completed.');
    }
}
