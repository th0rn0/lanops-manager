<?php
namespace App\Mail;

use App\User;
use App\Event;
use Spatie\MailTemplates\TemplateMailable;

class EventulaMailingMail extends TemplateMailable
{
    /** @var string */
    public $firstname;

    /** @var string */
    public $surname;

    /** @var string */
    public $username;
    
    /** @var string */
    public $email;    
    
    /** @var string */
    public $nextevent_display_name;   
    
    /** @var string */
    public $nextevent_capacity;

    /** @var string */
    public $nextevent_desc_long;

    /** @var string */
    public $nextevent_desc_short;

    /** @var string */
    public $nextevent_essential_info;

    /** @var string */
    public $nextevent_start;

    /** @var string */
    public $nextevent_end;

    /** @var string */
    public $nextevent_venue_display_name;

    /** @var string */
    public $nextevent_venue_address_1;

    /** @var string */
    public $nextevent_venue_address_2;

    /** @var string */
    public $nextevent_venue_address_street;

    /** @var string */
    public $nextevent_venue_address_city;

    /** @var string */
    public $nextevent_venue_address_postcode;

    /** @var string */
    public $nextevent_venue_address_country;

    


    public function __construct(User $user, ?Event $nextevent)
    {
        $this->firstname = $user->firstname;
        $this->surname = $user->surname;
        $this->email = $user->email;
        $this->username = $user->username_nice;

        if (isset($nextevent)) 
        {
            $this->nextevent_display_name = $nextevent->display_name;
            $this->nextevent_capacity = $nextevent->capacity;
            $this->nextevent_desc_long = $nextevent->desc_long;
            $this->nextevent_desc_short = $nextevent->desc_short;
            $this->nextevent_essential_info = $nextevent->essential_info;
            $this->nextevent_start = $nextevent->start;
            $this->nextevent_end = $nextevent->end;
            $this->nextevent_venue_display_name = $nextevent->venue->display_name;
            $this->nextevent_venue_address_1 = $nextevent->venue->address_1;
            $this->nextevent_venue_address_2 = $nextevent->venue->address_2;
            $this->nextevent_venue_address_street = $nextevent->venue->address_street;
            $this->nextevent_venue_address_city = $nextevent->venue->address_city;
            $this->nextevent_venue_address_postcode = $nextevent->venue->address_postcode;
            $this->nextevent_venue_address_country = $nextevent->venue->address_country;
        }


    }
}
