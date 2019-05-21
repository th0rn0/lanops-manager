<?php

namespace App\Http\Controllers;

use QrCode;
use Storage;
use Settings;
use Helpers;
use Auth;

use App\Purchase;
use App\User;
use App\Event;
use App\EventTicket;
use App\EventParticipant;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

use Omnipay\Omnipay as Omnipay;

class PaymentsController extends Controller
{
    protected $sandbox = false;


    /**
     * Checkout Page
     * @return View
     */
    public function checkout()
    {
        if (!$basket = Session::get('basket')) {
            return Redirect::to('/');
        }
        return view('payments.checkout')
            ->withBasketItems(Helpers::getBasketFormat($basket, true))
            ->withBasketTotal(Helpers::getBasketTotal($basket));
    }

    /**
     * Review Terms and Conditions of Purchase Page
     * @return View
     */
    public function review(Request $request)
    {
        if (!$basket = Session::get('basket')) {
            return Redirect::to('/');
        }
        $acceptedPaymentGateways = [
            'paypal_express',
            'stripe',
        ];
        if (!isset($request->gateway)) {
            Session::flash('alert-danger', 'A Payment Gateway is required: ' . implode(" ", $acceptedPaymentGateways));
            return Redirect::back();
        }
        if (in_array(strtolower($request->gateway), $acceptedPaymentGateways)) {
            $paymentGateway = strtolower($request->gateway);
        }
        if (!isset($paymentGateway)) {
            Session::flash('alert-danger', 'A Payment Gateway is required: ' . implode(" ", $acceptedPaymentGateways));
            return Redirect::back();
        }
        $nextEventFlag = true;
        foreach (Session::get('basket') as $ticketId => $quantity) {
            if (EventTicket::where('id', $ticketId)
                ->first()
                ->event
                ->id
                !=
                Event::where('end', '>=', \Carbon\Carbon::now())
                ->orderBy(\DB::raw('ABS(DATEDIFF(events.end, NOW()))'))
                ->first()
                ->id
            ) {
                $nextEventFlag = false;
            }
        }
        return view('payments.review')
            ->withPaymentGateway($request->gateway)
            ->withBasketItems(Helpers::getBasketFormat($basket, true))
            ->withBasketTotal(Helpers::getBasketTotal($basket))
            ->withNextEventFlag($nextEventFlag);
    }

    /**
     * Payment Details Page
     * @param  $paymentGateway
     * @return View
     */
    public function details($paymentGateway)
    {
        if (!$basket = Session::get('basket')) {
            return Redirect::to('/');
        }
        $acceptedPaymentGateways = [
            'paypal_express',
            'stripe',
        ];
        if (!isset($paymentGateway)) {
            Session::flash('alert-danger', 'A Payment Gateway is required: ' . implode(" ", $acceptedPaymentGateways));
            return Redirect::back();
        }
        if (in_array(strtolower($paymentGateway), $acceptedPaymentGateways)) {
            $paymentGateway = strtolower($paymentGateway);
        }
        return view('payments.details')
            ->withPaymentGateway($paymentGateway)
            ->withBasketItems(Helpers::getBasketFormat($basket, true))
            ->withBasketTotal(Helpers::getBasketTotal($basket));
    }
    
    /**
     * Post Payment to Gateway
     * @param  Request $request
     * @return Redirect
     */
    public function post(Request $request)
    {
        if (!$basket = Session::get('basket')) {
            Session::flash('alert-danger', 'No Basket was found. Please try again');
            return Redirect::back();
        }
        foreach ($basket as $ticketId => $quantity) {
            $ticket = EventTicket::where('id', $ticketId)->first();
            if ($ticket->event->capacity <= $ticket->event->EventParticipants->count()) {
                Session::flash('alert-danger', '{{ $ticket->event->display_name }} Has sold out!');
                return Redirect::back();
            }
        }
        $acceptedPaymentGateways = [
            'paypal_express',
            'stripe',
        ];
        if (!isset($request->gateway)) {
            Session::flash('alert-danger', 'A Payment Gateway is required: ' . implode(" ", $acceptedPaymentGateways));
            return Redirect::back();
        }
        if (in_array(strtolower($request->gateway), $acceptedPaymentGateways)) {
            $paymentGateway = strtolower($request->gateway);
        }
        if (!isset($paymentGateway)) {
            Session::flash('alert-danger', 'A Payment Gateway is required: ' . implode(" ", $acceptedPaymentGateways));
            return Redirect::back();
        }

        $offSitePaymentGateways = [
            'paypal_express',
        ];
        // Check if the card details have been submitted but allow off site payment gateways to continue
        if (
            !in_array($paymentGateway, $offSitePaymentGateways) &&
            !isset($request->card_first_name) &&
            !isset($request->card_last_name) &&
            !isset($request->card_number) &&
            !isset($request->card_expiry_month) &&
            !isset($request->card_expiry_year)
        ) {
            return Redirect::to('/payment/details/' . $paymentGateway);
        }

        $requestScheme = 'http';
        if ((! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') ||
                (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ||
                (! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443')
            ) {
            $requestScheme = 'https';
        }

        switch ($paymentGateway) {
            case 'stripe':
                // Stripe Post Params
                $rules = [
                    'card_first_name'   => 'required',
                    'card_last_name'    => 'required',
                    'card_number'       => 'required|integer',
                    'card_expiry_month' => 'required|integer|between:01,12',
                    'card_expiry_year'  => 'required|integer|between:00,99',
                    'card_cvv'          => 'integer|between:000,999',
                    'billing_address_1' => 'required',
                    'billing_postcode'  => 'required',
                ];
                $messages = [
                    'card_first_name.required'      => 'Card First Name is Required',
                    'card_last_name.required'       => 'Card Last Name is Required',
                    'card_number.required'          => 'Card Number is Required',
                    'card_number.integer'           => 'Card Number is invalid',
                    'card_expiry_month.required'    => 'Expiry Month is Required',
                    'card_expiry_month.integer'     => 'Expiry Month Must be a Number',
                    'card_expiry_month.between'        => 'Expiry Month Must in the MM format',
                    'card_expiry_year.required'     => 'Expiry Year is Required',
                    'card_expiry_year.integer'      => 'Expiry Year Must be a Number',
                    'card_expiry_year.between'         => 'Expiry Year Must in the YY format',
                    'card_cvv.integer'              => 'CVV must be a Number',
                    'card_cvv.between'                 => 'CVV must be a 3 Digits long',
                    'billing_address_1.required'    => 'Billing Address Required',
                    'billing_postcode.required'     => 'Billing Postcode Required',
                ];
                $this->validate($request, $rules, $messages);

                $card = array(
                    'firstName'             => $request->card_first_name,
                    'lastName'              => $request->card_last_name,
                    'number'                => $request->card_number,
                    'expiryMonth'           => $request->card_expiry_month,
                    'expiryYear'            => $request->card_expiry_year,
                    'cvv'                   => $request->card_cvv,
                    'billingAddress1'       => $request->billing_address_1,
                    'billingCountry'        => $request->billing_address_2,
                    'billingCity'           => $request->billing_country,
                    'billingPostcode'       => $request->billing_postcode,
                    'billingState'          => $request->billing_state,
                );
                $params = array(
                    'amount' => (float)Helpers::getBasketTotal($basket),
                    'currency' => 'GBP',
                    'card' => $card
                );
                $gateway = Omnipay::create('Stripe');
                $gateway->setApiKey(config('laravel-omnipay.gateways.stripe.credentials.apikey'));
                break;
            case 'paypal_express':
                //Paypal Post Params
                $params = array(
                    'cancelUrl'     => $requestScheme . '://' . $_SERVER['HTTP_HOST'] . '/payment/callback?type=cancel',
                    'returnUrl'     => $requestScheme . '://' . $_SERVER['HTTP_HOST'] . '/payment/callback?type=return',
                    'name'          => Settings::getOrgName() . ' - Tickets Purchase',
                    'description'   => 'Ticket Purchase for ' . Settings::getOrgName(),
                    'amount'        => (float)Helpers::getBasketTotal($basket),
                    'quantity'      => (string)count($basket),
                    'currency'      => Settings::getCurrency(),
                    'user_id'       => Auth::id(),
                );
                $gateway = Omnipay::create('PayPal_Express');
                $gateway->setUsername(config('laravel-omnipay.gateways.paypal.credentials.username'));
                $gateway->setPassword(config('laravel-omnipay.gateways.paypal.credentials.password'));
                $gateway->setSignature(config('laravel-omnipay.gateways.paypal.credentials.signature'));
                break;
        }
        Session::put('params', $params);
        Session::save();

        if (config('app.debug')) {
            $this->sandbox = true;
        }
        $gateway->setTestMode($this->sandbox);

        // Send Payment
        try {
            $response = $gateway->purchase($params)->send();
        } catch (\Exception $e) {
            Session::flash('alert-danger', $e->getMessage());
            return Redirect::back();
        }

        // Process Response
        if ($response->isSuccessful() && $paymentGateway == 'stripe') {
            // payment was successful: update database
            $stripeResponse = $response->getData();
            $purchaseParams = [
                'user_id'           => Auth::id(),
                'type'              => 'Stripe',
                'transaction_id'    => $response->getTransactionReference(),
                'token'             => $response->getBalanceTransactionReference(),
                'status'            => 'Success'
            ];
            $purchase = Purchase::create($purchaseParams);
            foreach (Session::get('basket') as $ticketId => $quantity) {
                $ticket = EventTicket::where('id', $ticketId)->first();
                for ($i = 1; $i <= $quantity; $i++) {
                    //Add Participant to database
                    $participant = [
                        'user_id'       => Auth::id(),
                        'event_id'      => $ticket->event->id,
                        'ticket_id'     => $ticket->id,
                        'purchase_id'   => $purchase->id,
                    ];
                    EventParticipant::create($participant);
                }
            }
            return Redirect::to('/payment/successful/' . $purchase->id);
        } elseif ($response->isRedirect() && $paymentGateway == 'paypal_express') {
            // redirect to offsite payment gateway such as paypal
            try {
                $response->redirect();
            } catch (\Exception $e) {
                Session::flash('alert-danger', $e->getMessage());
                return Redirect::back();
            }
        }
        //Failed transaction
        Session::flash('alert-danger', 'Payment was UNSUCCESSFUL! - Please try again.' . $response->getMessage());
        return Redirect::to('/payment/failed');
    }

    /**
     * Process Callback Payment
     * @param  Request $request
     * @return Redirect
     */
    public function process(Request $request)
    {
        // DEBUG
        $paymentGateway = 'paypal';

        if ($request->input('type') == 'cancel') {
            Session::flash('alert-danger', 'Payment was CANCELLED!');
            return Redirect::to('/payment/cancelled');
        }
        if (!Session::has('params')) {
            Session::flash('alert-danger', 'Payment was UNSUCCESSFUL!');
            return Redirect::to('/payment/failed');
        }
        $params = Session::get('params');
        if (config('app.debug')) {
            $this->sandbox = true;
        }
        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername(config('laravel-omnipay.gateways.paypal.credentials.username'));
        $gateway->setPassword(config('laravel-omnipay.gateways.paypal.credentials.password'));
        $gateway->setSignature(config('laravel-omnipay.gateways.paypal.credentials.signature'));
        $gateway->setTestMode($this->sandbox);
        //Complete Purchase
        $gateway->completePurchase($params)->send();
        $response = $gateway->fetchCheckout($params)->send(); // this is the raw response object
        $paypalResponse = $response->getData();
        if (isset($paypalResponse['ACK']) &&
            $paypalResponse['ACK'] === 'Success' &&
            isset($paypalResponse['PAYMENTREQUEST_0_TRANSACTIONID'])
        ) {
            //Add Purchase to database
            $purchaseParams = [
                'user_id'           => $params['user_id'],
                'type'              => 'PayPal Express',
                'transaction_id'    => $paypalResponse['PAYMENTREQUEST_0_TRANSACTIONID'],
                'token'             => $paypalResponse['TOKEN'],
                'status'            => $paypalResponse['ACK'],
                'paypal_email'      => $paypalResponse['EMAIL'],
            ];
            $purchase = Purchase::create($purchaseParams);
            foreach (Session::get('basket') as $ticketId => $quantity) {
                $ticket = EventTicket::where('id', $ticketId)->first();
                for ($i = 1; $i <= $quantity; $i++) {
                    //Add Participant to database
                    $participant = [
                        'user_id'       => $params['user_id'],
                        'event_id'      => $ticket->event->id,
                        'ticket_id'     => $ticket->id,
                        'purchase_id'   => $purchase->id,
                    ];
                    EventParticipant::create($participant);
                }
            }
            return Redirect::to('/payment/successful/' . $purchase->id);
        }
        //Failed transaction
        Session::flash('alert-danger', 'Payment was UNSUCCESSFUL! - Please try again.');
        return Redirect::to('/payment/failed');
    }

    /**
     * Successful Payment Page
     * @param  Purchase $purchase
     * @return View
     */
    public function successful(Purchase $purchase)
    {
        if (!Session::has('params')) {
            return Redirect::to('/');
        }
        $basket = Helpers::getBasketFormat(Session::get('basket'), true);
        Session::forget('params');
        Session::forget('basket');
        return view('payments.successful')->withBasketItems($basket)->withPurchase($purchase);
    }

    /**
     * Failed Payment Page
     * @param  Purchase $purchase
     * @return View
     */
    public function failed()
    {
        return view('payments.failed');
    }

    /**
     * Cancelled Payment Page
     * @param  Purchase $purchase
     * @return View
     */
    public function cancelled()
    {
        return view('payments.cancelled');
    }
}
