<?php

namespace App\Http\Controllers\Adminapi;

use Mail;

use App\User;
use App\Purchase;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Mail\EventulaTicketOrderPaymentFinishedMail;


class PurchaseController extends Controller
{
    /**
     * set Purchase Success
     * @param Purchase $purchase
     * @return View
     */
    public function setSuccess(Purchase $purchase)
    {
        if ($purchase->status != "Pending")
        {
            return [
                'successful' => 'false',
                'reason' => 'purchase status not pending',
                'purchase' => $purchase,
            ];
        }
        if (!$purchase->setSuccess()) {
            return [
                'successful' => 'false',
                'reason' => 'purchase update failed',
                'purchase' => $purchase,
            ];
        }

        Mail::to($purchase->user)->queue(new EventulaTicketOrderPaymentFinishedMail($purchase->user, $purchase));

        return [
            'successful' => 'true',
            'reason' => '',
            'purchase' => $purchase,
        ];
    }
}
