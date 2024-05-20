<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
                // modify this to your own needs
        // SitemapGenerator::create(config('app.url'))
        //     ->writeToFile(public_path('sitemap.xml'));

        $sitemap = resolve("sitemap");

        $sitemap->add(url('/'), '2012-08-25T20:10:00+02:00', '1.0', 'daily');
        $sitemap->add(url('events'), '2012-08-26T12:30:00+02:00', '0.9', 'monthly');
        $sitemap->add(url('news'), '2012-08-26T12:30:00+02:00', '0.9', 'monthly');
        $sitemap->add(url('gallery'), '2012-08-26T12:30:00+02:00', '0.9', 'monthly');

    
        $events = \DB::table('events')->orderBy('start')->get();

        $sitemap->setCache('laravel.sitemap', 60);
    
        foreach ($events as $event) {
            $sitemap->add(url('/') . '/' . $event->slug, $event->updated_at, '1.0', 'daily');
        }

        $newsPosts = \DB::table('news_feed')->orderBy('updated_at')->get();
    
        foreach ($newsPosts as $newsPost) {
            $sitemap->add(url('/') . '/' . $newsPost->slug, $newsPost->updated_at, '1.0', 'daily');
        }
    
        $sitemap->store('xml', 'sitemap');
        
        return Command::SUCCESS;
    }
}
