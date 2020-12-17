<?php
namespace App\Mail;

use App\User;
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

    public function __construct(User $user)
    {
        $this->firstname = $user->firstname;
        $this->surname = $user->surname;
        $this->email = $user->email;
        $this->username = $user->username_nice;
    }
}
