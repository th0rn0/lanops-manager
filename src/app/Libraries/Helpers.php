<?php

namespace App\Libraries;

use DB;
use Auth;
use App\Models\User;
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
    public static function formatBasket($basket, User $user = null, $referralDiscountAmountOverride = null, $skipAvailableReferralsCheck = false)
    {
        if(!$user) {
            $user = Auth::user();
        }
        if (!$referralDiscountAmountOverride) {
            $referralDiscountAmountOverride = config('app.refer_a_friend_discount');
        }
        if (array_key_exists('tickets', $basket)) {
            $formattedBasket = \App\Models\EventTicket::whereIn('id', array_keys($basket['tickets']))->get();
        }
        if (!$formattedBasket) {
            return false;
        }
        $formattedBasket->total = 0;
        $formattedBasket->total_before_discounts = 0;
        $formattedBasket->referral_discount_total = 0;
        $formattedBasket->allow_payment = true;
        $formattedBasket->referral_code = null;
        $formattedBasket->referral_used = false;
        foreach ($formattedBasket as $item) {
            if (array_key_exists('tickets', $basket)) {
                $item->quantity = $basket['tickets'][$item->id];
                $formattedBasket->total += $item->price * $item->quantity;
                $formattedBasket->total_before_discounts += $item->price * $item->quantity;
            }
            if ($item->price == null || $item->price < 0) {
                $formattedBasket->allow_payment = false;
            }
        }
        if (array_key_exists('referral_discount', $basket) && $basket['referral_discount'] && ($user->getAvailableReferralPurchase() || $skipAvailableReferralsCheck)) {
            $formattedBasket->total -= $referralDiscountAmountOverride;
            $formattedBasket->referral_discount_total += $referralDiscountAmountOverride;
            $formattedBasket->referral_used = true;
        }
        if (array_key_exists('codes', $basket)) {
            if (array_key_exists('referral', $basket['codes']) && User::isValidReferralCode($basket['codes']['referral'], Auth::user())) {
                $formattedBasket->referral_code = $basket['codes']['referral'];
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
            // if ($date <= 9) {
            //     $date = '0' . $i;
            // }
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
            // if ($date <= 9) {
            //     $date = '0' . $i;
            // }
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
            "Afghanistan",
            "Åland Islands",
            "Albania",
            "Algeria",
            "American Samoa",
            "Andorra",
            "Angola",
            "Anguilla",
            "Antarctica",
            "Antigua and Barbuda",
            "Argentina",
            "Armenia",
            "Aruba",
            "Australia",
            "Austria",
            "Azerbaijan",
            "Bahamas",
            "Bahrain",
            "Bangladesh",
            "Barbados",
            "Belarus",
            "Belgium",
            "Belize",
            "Benin",
            "Bermuda",
            "Bhutan",
            "Bolivia",
            "Bosnia and Herzegovina",
            "Botswana",
            "Bouvet Island",
            "Brazil",
            "British Indian Ocean Territory",
            "Brunei Darussalam",
            "Bulgaria",
            "Burkina Faso",
            "Burundi",
            "Cambodia",
            "Cameroon",
            "Canada",
            "Cape Verde",
            "Cayman Islands",
            "Central African Republic",
            "Chad",
            "Chile",
            "China",
            "Christmas Island",
            "Cocos (Keeling) Islands",
            "Colombia",
            "Comoros",
            "Congo",
            "Congo, The Democratic Republic of The",
            "Cook Islands",
            "Costa Rica",
            "Cote D'ivoire",
            "Croatia",
            "Cuba",
            "Cyprus",
            "Czech Republic",
            "Denmark",
            "Djibouti",
            "Dominica",
            "Dominican Republic",
            "Ecuador",
            "Egypt",
            "El Salvador",
            "Equatorial Guinea",
            "Eritrea",
            "Estonia",
            "Ethiopia",
            "Falkland Islands (Malvinas)",
            "Faroe Islands",
            "Fiji",
            "Finland",
            "France",
            "French Guiana",
            "French Polynesia",
            "French Southern Territories",
            "Gabon",
            "Gambia",
            "Georgia",
            "Germany",
            "Ghana",
            "Gibraltar",
            "Greece",
            "Greenland",
            "Grenada",
            "Guadeloupe",
            "Guam",
            "Guatemala",
            "Guernsey",
            "Guinea",
            "Guinea-bissau",
            "Guyana",
            "Haiti",
            "Heard Island and Mcdonald Islands",
            "Holy See (Vatican City State)",
            "Honduras",
            "Hong Kong",
            "Hungary",
            "Iceland",
            "India",
            "Indonesia",
            "Iran, Islamic Republic of",
            "Iraq",
            "Ireland",
            "Isle of Man",
            "Israel",
            "Italy",
            "Jamaica",
            "Japan",
            "Jersey",
            "Jordan",
            "Kazakhstan",
            "Kenya",
            "Kiribati",
            "Korea, Democratic People's Republic of",
            "Korea, Republic of",
            "Kuwait",
            "Kyrgyzstan",
            "Lao People's Democratic Republic",
            "Latvia",
            "Lebanon",
            "Lesotho",
            "Liberia",
            "Libyan Arab Jamahiriya",
            "Liechtenstein",
            "Lithuania",
            "Luxembourg",
            "Macao",
            "Macedonia, The Former Yugoslav Republic of",
            "Madagascar",
            "Malawi",
            "Malaysia",
            "Maldives",
            "Mali",
            "Malta",
            "Marshall Islands",
            "Martinique",
            "Mauritania",
            "Mauritius",
            "Mayotte",
            "Mexico",
            "Micronesia, Federated States of",
            "Moldova, Republic of",
            "Monaco",
            "Mongolia",
            "Montenegro",
            "Montserrat",
            "Morocco",
            "Mozambique",
            "Myanmar",
            "Namibia",
            "Nauru",
            "Nepal",
            "Netherlands",
            "Netherlands Antilles",
            "New Caledonia",
            "New Zealand",
            "Nicaragua",
            "Niger",
            "Nigeria",
            "Niue",
            "Norfolk Island",
            "Northern Mariana Islands",
            "Norway",
            "Oman",
            "Pakistan",
            "Palau",
            "Palestinian Territory, Occupied",
            "Panama",
            "Papua New Guinea",
            "Paraguay",
            "Peru",
            "Philippines",
            "Pitcairn",
            "Poland",
            "Portugal",
            "Puerto Rico",
            "Qatar",
            "Reunion",
            "Romania",
            "Russian Federation",
            "Rwanda",
            "Saint Helena",
            "Saint Kitts and Nevis",
            "Saint Lucia",
            "Saint Pierre and Miquelon",
            "Saint Vincent and The Grenadines",
            "Samoa",
            "San Marino",
            "Sao Tome and Principe",
            "Saudi Arabia",
            "Senegal",
            "Serbia",
            "Seychelles",
            "Sierra Leone",
            "Singapore",
            "Slovakia",
            "Slovenia",
            "Solomon Islands",
            "Somalia",
            "South Africa",
            "South Georgia and The South Sandwich Islands",
            "Spain",
            "Sri Lanka",
            "Sudan",
            "Suriname",
            "Svalbard and Jan Mayen",
            "Swaziland",
            "Sweden",
            "Switzerland",
            "Syrian Arab Republic",
            "Taiwan, Province of China",
            "Tajikistan",
            "Tanzania, United Republic of",
            "Thailand",
            "Timor-leste",
            "Togo",
            "Tokelau",
            "Tonga",
            "Trinidad and Tobago",
            "Tunisia",
            "Turkey",
            "Turkmenistan",
            "Turks and Caicos Islands",
            "Tuvalu",
            "Uganda",
            "Ukraine",
            "United Arab Emirates",
            "United Kingdom",
            "United States",
            "United States Minor Outlying Islands",
            "Uruguay",
            "Uzbekistan",
            "Vanuatu",
            "Venezuela",
            "Viet Nam",
            "Virgin Islands, British",
            "Virgin Islands, U.S.",
            "Wallis and Futuna",
            "Western Sahara",
            "Yemen",
            "Zambia",
            "Zimbabwe",
        ];
        return $countriesArray;
    }
}
