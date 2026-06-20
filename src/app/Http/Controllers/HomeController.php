<?php

namespace App\Http\Controllers;

use DB;

use App\Models\Event;
use App\Models\NewsArticle;

class HomeController extends Controller
{
    /**
     * Show Index Page
     * @return View
     */
    public function index()
    {
        $sliderDir = public_path('videos/slider/');
        $sliderFiles = glob($sliderDir . '*.{mp4,webm}', GLOB_BRACE) ?: [];
        $sliderVideos = array_map(
            fn($f) => '/videos/slider/' . basename($f),
            $sliderFiles
        );

        $nextLan = Event::where('end', '>=', \Carbon\Carbon::now())
            ->where('type', Event::$typeLan)
            ->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))
            ->first();

        $seoTitle       = config('app.tagline');
        $seoDescription = config('app.seo_description');

        if ($nextLan) {
            $eventDate      = date('jS F Y', strtotime($nextLan->start));
            $seoTitle       = 'Next LAN: ' . $nextLan->display_name . ' — ' . $eventDate;
            $seoDescription = 'Join us for ' . $nextLan->display_name . ' on ' . $eventDate
                . ($nextLan->venue ? ' at ' . $nextLan->venue->display_name : '')
                . '. ' . config('app.seo_description');
        }

        seo()
            ->title($seoTitle)
            ->description($seoDescription)
            ->url(config('app.url') . '/')
            ->image(config('app.url') . '/images/og-image.jpg');

        return view("home")
            ->withNextEvent(
                Event::where('end', '>=', \Carbon\Carbon::now())
                    ->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
            )
            ->withNextEventLan(
                Event::where('end', '>=', \Carbon\Carbon::now())
                    ->where('type', Event::$typeLan)
                    ->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
            )
            ->withNextEventTabletop(
                Event::where('end', '>=', \Carbon\Carbon::now())
                    ->where('type', Event::$typeTabletop)
                    ->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
            )
            ->withNewsArticles(NewsArticle::limit(4)->orderBy('created_at', 'desc')->get())
            ->withEvents(Event::orderBy('created_at', 'DESC')->get())
            ->withSliderVideos($sliderVideos)
        ;
    }
    
    /**
     * Show About us Page
     * @return View
     */
    public function about()
    {
        return view("about");
    }
    
    /**
     * Show Terms and Conditions Page
     * @return View
     */
    public function terms()
    {
        return view("terms");
    }

    /**
     * Show Contact Page
     * @return View
     */
    public function contact()
    {
        return view("contact");
    }

    /**
     * Show Information Page
     * @return View
     */
    public function info()
    {
        return view("info");
    }
}
