<?php

namespace App\Http\Controllers;

use DB;

use App\Models\Event;
use App\Models\NewsArticle;

class BigScreenController extends Controller
{
    /**
     * Show Index Page
     * @return View
     */
    public function timetable()
    {
        return view("bigscreen.timetable")
            ->withEvent(
                Event::where('end', '>=', \Carbon\Carbon::now())
                    ->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
            )
        ;
    }

    public function seating()
    {
        return view("bigscreen.seating")
            ->withEvent(
                Event::where('end', '>=', \Carbon\Carbon::now())
                    ->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
            )
        ;
    }

}
