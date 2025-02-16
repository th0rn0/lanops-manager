<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SitemapController extends Controller
{
    /**
     * Generate Sitemap
     *
     * @return \Illuminate\Http\Response
     */
    public function renderSitemap()
    {
        $sitemap = resolve("sitemap");

        $sitemap->add(url('/'), '2012-08-25T20:10:00+02:00', '1.0', 'daily');
        $sitemap->add(url('events'), '2012-08-26T12:30:00+02:00', '0.9', 'monthly');
        $sitemap->add(url('news'), '2012-08-26T12:30:00+02:00', '0.9', 'monthly');
        $sitemap->add(url('gallery'), '2012-08-26T12:30:00+02:00', '0.9', 'monthly');
        $sitemap->add(url('info'), '2012-08-26T12:30:00+02:00', '0.9', 'monthly');

        $events = \DB::table('events')->orderBy('start')->get();

        $sitemap->setCache('laravel.sitemap', 30);
    
        foreach ($events as $event) {
            $sitemap->add(url('/') . '/' . $event->slug, $event->updated_at, '1.0', 'daily');
        }

        $newsPosts = \DB::table('news_feed')->orderBy('updated_at')->get();
    
        foreach ($newsPosts as $newsPost) {
            $sitemap->add(url('/') . '/' . $newsPost->slug, $newsPost->updated_at, '1.0', 'daily');
        }
    
        return $sitemap->render('xml');
    }
}