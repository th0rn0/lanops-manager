<?php

namespace App\Libraries;

use DB;
use \Carbon\Carbon as Carbon;

class Helpers
{
    // TODO - refactor - eg getGameSelectArray - specifially the selectArray part

    // public static function getGameSelectArray($publicOnly = true)
    // {
    //     $return[0] = 'None';
    //     foreach (Game::where('public', $publicOnly)->orderBy('name', 'ASC')->get() as $game) {
    //         $return[$game->id] = $game->name;
    //     }
    //     return $return;
    // }
    /**
     * Get Venues
     * @param  boolean $obj Return as Object
     * @return Array|Object
     */
    public static function getVenues($obj = false)
    {
        $venues = \App\Models\EventVenue::all();
        $return = array();
        foreach ($venues as $venue) {
                $return[$venue->id] = $venue->display_name;
        }
        if (!$obj) {
            $return[] = 'None';
        }
        if ($obj) {
            return json_decode(json_encode($return), false);
        }
        return $return;
    }

    /**
     * Get Events
     * @param  string  $order
     * @param  integer $limit
     * @param  boolean $obj   Return as Object
     * @return Array|Object
     */
    public static function getEvents($order = 'DESC', $limit = 0, $obj = false)
    {
        $return = array();
        if ($limit != 0) {
            $events = \App\Models\Event::orderBy('start', $order)->paginate($limit);
        } else {
            $events = \App\Models\Event::orderBy('start', 'DESC')->get();
        }
        $return = array();
        foreach ($events as $event) {
            $return[$event->id] = $event;
        }
        if ($obj) {
            return json_decode(json_encode($return), false);
        }
        return $return;
    }

    /**
     * Get Event Names
     * @param  string  $order
     * @param  integer $limit
     * @param  boolean $future
     * @param  boolean $obj   Return as Object
     * @return Array|Object
     */
    public static function getEventNames($order = 'DESC', $limit = 0, $future = false, $obj = false)
    {
        $return = array();
        if ($limit != 0) {
            if ($future) {
                $events = \App\Models\Event::where('end', '>=', date('Y-m-d'))->orderBy('start', $order)->paginate($limit);
            } else {
                $events = \App\Models\Event::orderBy('start', $order)->paginate($limit);
            }
        } else {
            if ($future) {
                $events = \App\Models\Event::where('end', '>=', date('Y-m-d'))->orderBy('start', 'DESC')->get();
            } else {
                $events = \App\Models\Event::orderBy('start', 'DESC')->get();
            }
        }
        if (!$obj) {
            $return[] = 'None';
        }
        foreach ($events as $event) {
            $return[$event->id] = $event->display_name;
        }
        if ($obj) {
            return json_decode(json_encode($return), false);
        }
        return $return;
    }

    /**
     * Get Total Events Count
     * @return Integer
     */
    public static function getEventTotal()
    {
        $events = \App\Models\Event::count();
        // Historical before this site
        return 23 + $events;
    }

    /**
     * Get Next Event Name
     * @return String
     */
    public static function getNextEventName()
    {
        if ($event = \App\Models\Event::where(
            'end',
            '>=',
            Carbon::now()
        )->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
        ) {
            if ($event->status == 'DRAFT' || $event->status == 'PREVIEW') {
                return $event->display_name . ' - ' . $event->status;
            }
            return $event->display_name;
        }
        return 'Coming soon...';
    }

    /**
     * Get Next Event Slug
     * @return String
     */
    public static function getNextEventSlug()
    {
        if ($event = \App\Models\Event::where(
            'end',
            '>=',
            Carbon::now()
        )->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
        ) {
            return $event->slug;
        }
        return '#';
    }


    /**
     * Get Next Event Description
     * @return String
     */
    public static function getNextEventDesc()
    {
        if ($event = \App\Models\Event::where(
            'end',
            '>=',
            Carbon::now()
        )->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
        ) {
            return $event->desc_long;
        }
        return 'Coming soon...';
    }

    /**
     * Get Next Event Start Date
     * @return String
     */
    public static function getNextEventStartDate()
    {
        if ($event = \App\Models\Event::where(
            'end',
            '>=',
            Carbon::now()
        )->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
        ) {
            return date("d-m-Y H:i", strtotime($event->start));
        }
        return 'Coming soon...';
    }

    /**
     * Get Next Event End Date
     * @return String
     */
    public static function getNextEventEndDate()
    {
        if ($event = \App\Models\Event::where(
            'end',
            '>=',
            Carbon::now()
        )->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
        ) {
            return date("d-m-Y H:i", strtotime($event->end));
        }
        return 'Coming soon...';
    }

    /**
     * Get Total Event Participants Count
     * @return Integer
     */
    public static function getEventParticipantTotal()
    {
        $participants = \App\Models\EventParticipant::count();
        // Historical before this site
        return 686 + $participants;
    }

    /**
     * Get Basket Total
     * @param  $basket
     * @return Integer
     */
    public static function getBasketTotal($basket)
    {
        $return = 0;
        foreach ($basket as $ticket_id => $quantity) {
            $ticket = \App\Models\EventTicket::where('id', $ticket_id)->first();
            $return += ($ticket->price * $quantity);
        }
        return $return;
    }

    /**
     * array_key_exists with regex
     * @param  $pattern
     * @param  $array
     * @return Integer
     */
    public static function pregArrayKeyExists($pattern, $array)
    {
        $keys = array_keys($array);
        return (int) preg_grep($pattern, $keys);
    }

    /**
     * Format Shopping Basket into Readable format
     * @param $itemId
     * @return Boolean
     */
    public static function formatBasket($basket)
    {
        if (array_key_exists('tickets', $basket)) {
            $formattedBasket = \App\Models\EventTicket::whereIn('id', array_keys($basket['tickets']))->get();
        }
        if (!$formattedBasket) {
            return false;
        }
        $formattedBasket->total = 0;
        $formattedBasket->total_credit = 0;
        $formattedBasket->allow_payment = true;
        $formattedBasket->allow_credit = true;
        foreach ($formattedBasket as $item) {
            // TODO - REMOVE ME
            if (array_key_exists('shop', $basket)) {
                $item->quantity = $basket['shop'][$item->id];
                if ($item->price != null && $item->price != 0) {
                    $formattedBasket->total += $item->price * $item->quantity;
                }
                if ($item->price_credit != null && $item->price_credit != 0) {
                    $formattedBasket->total_credit += $item->price_credit * $item->quantity;
                }
            } else {
                $item->quantity = $basket['tickets'][$item->id];
                $formattedBasket->total += $item->price * $item->quantity;
                $formattedBasket->total_credit += $item->price_credit * $item->quantity;
            }
            if ($item->price_credit == null || $item->price_credit == 0) {
                $formattedBasket->allow_credit = false;
            }
            if ($item->price == null || $item->price == 0) {
                $formattedBasket->allow_payment = false;
            }
        }
        return $formattedBasket;

    }

    /**
     * Get Card Expiry Month Dates
     * @return array
     */
    public static function getCardExpiryMonthDates()
    {
        $return = array();
        for ($i=1; $i<=12; $i++) {
            $date = $i;
            $return[$date] = $date;
        }
        return $return;
    }

    /**
     * Get Card Expiry Year Dates
     * @return array
     */
    public static function getCardExpiryYearDates()
    {
        $return = array();
        for ($i=(int)date('y'); $i<=99; $i++) {
            $date = $i;
            $return[$date] = $date;
        }
        return $return;
    }

    /**
     * Get Countries for Select
     * @return array
     */
    public static function getSelectCountries()
    {
        $countriesArray = [
            "United Kingdom",
        ];
        return $countriesArray;
    }
}
