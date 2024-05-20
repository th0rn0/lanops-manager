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

        $sitemap->add(\URL::to(), '2012-08-25T20:10:00+02:00', '1.0', 'daily');
        $sitemap->add(\URL::to('page'), '2012-08-26T12:30:00+02:00', '0.9', 'monthly');
    
        $posts = \DB::table('posts')->orderBy('created_at', 'desc')->get();
    
        foreach ($posts as $post) {
            $sitemap->add($post->slug, $post->modified, $post->priority, $post->freq);
        }
    
        // generate (format, filename)
        // sitemap.xml is stored within the public folder
        $sitemap->store('xml', 'sitemap');
        
        return Command::SUCCESS;
    }
}
