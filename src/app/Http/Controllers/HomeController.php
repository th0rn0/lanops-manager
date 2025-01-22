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
        // TODO - TEMP FIX
        // Setup Slider Images
        $sliderImages = array(
            "/images/frontpage/slider/1.jpg",
            "/images/frontpage/slider/3.jpg",
            "/images/frontpage/slider/2.jpg",
            "/images/frontpage/slider/4.jpg",
            "/images/frontpage/slider/5.jpg"
        );
        shuffle($sliderImages);
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
            ->withSliderImages($sliderImages)
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
}
