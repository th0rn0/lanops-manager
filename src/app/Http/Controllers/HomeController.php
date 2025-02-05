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
            array(
                "path" => "/images/frontpage/slider/1.jpg"
            ),
            array (
                "path" => "/images/frontpage/slider/2.jpg"
            )
        );

        return view("home")
            ->withNextEvent(
                Event::where('end', '>=', \Carbon\Carbon::now())
                    ->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
            )
            ->withNewsArticles(NewsArticle::limit(4)->orderBy('created_at', 'desc')->get())
            ->withEvents(Event::orderBy('created_at', 'DESC')->get())
            ->withSliderImages(json_decode(json_encode($sliderImages), FALSE))
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
