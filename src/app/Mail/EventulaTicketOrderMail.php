<?php
namespace App\Mail;
use Storage; 
use App\User;
use App\Event;
use App\Purchase;
use App\EventParticipant;
use Spatie\MailTemplates\TemplateMailable;
use Spatie\MailTemplates\Interfaces\MailTemplateInterface;
use Spatie\MailTemplates\Models\MailTemplate;


class EventulaTicketOrderMail extends TemplateMailable
{
    /** @var string */
    public const staticname = "Ticket Order";
   
    /** @var string */
    public $firstname;

    /** @var string */
    public $surname;

    /** @var string */
    public $username;
    
    /** @var string */
    public $email;    

    /** @var string */
    public $url;    
    
    /** @var int */
    public $purchase_id;    
    
    /** @var string */
    public $purchase_payment_method;    

    /** @var array */
    public array $purchase_participants;   

    /** @var array */
    public array $basket;      
    


    public function __construct(User $user, Purchase $purchase, Array $basket)
    {
        $this->firstname = $user->firstname;
        $this->surname = $user->surname;
        $this->email = $user->email;
        $this->username = $user->username_nice;
        $this->url = config('app.url');


        // $this->purchase_participants = array(
        //                         array(
        //                             'qrcode'  => "testknaddle",
        //                             'event' => array(
        //                                 'display_name' => 'EventNameHere 3',
        //                             ),                                    
        //                             'ticket' => array(
        //                                 'id' => 2,
        //                             ),

        //                         ),                                
                                
        //                     );

        if (isset($purchase))
        {
            $this->purchase_id = $purchase->id;
            $this->purchase_payment_method = $purchase->getPurchaseType();
            $this->purchase_participants = array();
            Storage::append('filez.log', '______________________________________________________________________________________________');

            Storage::append('filez.log', 'create purchase object');
            Storage::append('filez.log', "json: " . json_encode($this->purchase_participants));


            foreach($purchase->participants as $participant)
            {
                Storage::append('filez.log', "participant: " . json_encode($participant));

                Storage::append('filez.log', "eventparticipant: " . json_encode(EventParticipant::with('event','ticket')->where('id', $participant->id)->get()));


               
                $tempparticipant = EventParticipant::with('event','ticket')->where('id', $participant->id)->first();
                
                Storage::append('filez.log', "participant: " . print_r((array)$tempparticipant, true));
                Storage::append('filez.log', "participant: " . print_r($tempparticipant, true));
            
                $this->purchase_participants[]=($tempparticipant);
            }


            Storage::append('filez.log', 'finished');
            Storage::append('filez.log', "json: " . json_encode($this->purchase_participants));


        }
        if (isset($basket))
        {
            Storage::append('filez.log', 'basket');
            Storage::append('filez.log', "basket: " . json_encode($basket));
            $this->basket = $basket;
        }


    }

    function object_to_array_recursive($object, $assoc=TRUE, $empty='')
    {
        $res_arr = array();

        if (!empty($object)) {

            $arrObj = is_object($object) ? get_object_vars($object) : $object;

            $i=0;
            foreach ($arrObj as $key => $val) {
                $akey = ($assoc !== FALSE) ? $key : $i;
                if (is_array($val) || is_object($val)) {
                    $res_arr[$akey] = (empty($val)) ? $empty : object_to_array_recursive($val);
                }
                else {
                    $res_arr[$akey] = (empty($val)) ? $empty : (string)$val;
                }
                $i++;
            }
        }
        return $res_arr;
    }

}
