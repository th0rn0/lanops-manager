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
use App\ShopItem;
use App\ShopOrder;
use App\ShopOrderItem;
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
        if (!Session::has(Settings::getOrgName() . '-basket')) {
            return Redirect::to('/');
        }
        return view('payments.checkout')
            ->withBasket(Helpers::formatBasket(Session::get(Settings::getOrgName() . '-basket')))
            ->withActivePaymentGateways(Settings::getPaymentGateways())
        ;
    }

    /**
     * Review Terms and Conditions of Purchase Page
     * @return View
     */
    public function review($paymentGateway)
    {
        if (!$basket = Session::get(Settings::getOrgName() . '-basket')) {
            return Redirect::to('/');
        }
        $acceptedPaymentGateways = Settings::getPaymentGateways();
        if (!isset($paymentGateway)) {
            Session::flash('alert-danger', 'A Payment Gateway is required: ' . implode(" ", $acceptedPaymentGateways));
            return Redirect::back();
        }
        if (in_array(strtolower($paymentGateway), $acceptedPaymentGateways)) {
            $paymentGateway = strtolower($paymentGateway);
        }
        if (!isset($paymentGateway)) {
            Session::flash('alert-danger', 'A Payment Gateway is required: ' . implode(" ", $acceptedPaymentGateways));
            return Redirect::back();
        }
        $nextEventFlag = true;
        if (array_key_exists('tickets', $basket)) {
            foreach ($basket['tickets'] as $ticketId => $quantity) {
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
        }
        return view('payments.review')
            ->withPaymentGateway($paymentGateway)
            ->withBasket(Helpers::formatBasket($basket))
            ->withNextEventFlag($nextEventFlag)
        ;
    }

    /**
     * Payment Details Page
     * @param  $paymentGateway
     * @return View
     */
    public function details($paymentGateway)
    {
        if (!$basket = Session::get(Settings::getOrgName() . '-basket')) {
            Session::flash('alert-danger', 'No Basket was found. Please try again');
            return Redirect::back();
        }
        $acceptedPaymentGateways = Settings::getPaymentGateways();
        if (!isset($paymentGateway)) {
            Session::flash('alert-danger', 'A Payment Gateway is required: ' . implode(" ", $acceptedPaymentGateways));
            return Redirect::back();
        }
        if (in_array(strtolower($paymentGateway), $acceptedPaymentGateways)) {
            $paymentGateway = strtolower($paymentGateway);
        }
        return view('payments.details')
            ->withPaymentGateway($paymentGateway)
            ->withBasket(Helpers::formatBasket($basket, true))
        ;
    }
    
    /**
     * Post Payment to Gateway
     * @param  Request $request
     * @return Redirect
     */
    public function post(Request $request)
    {
        if (!$basket = Session::get(Settings::getOrgName() . '-basket')) {
            Session::flash('alert-danger', 'No Basket was found. Please try again');
            return Redirect::back();
        }
        if (array_key_exists('tickets', $basket)) {
            foreach ($basket['tickets'] as $ticketId => $quantity) {
                $ticket = EventTicket::where('id', $ticketId)->first();
                if ($ticket->event->capacity <= $ticket->event->EventParticipants->count()) {
                    Session::flash('alert-danger', '{{ $ticket->event->display_name }} Has sold out!');
                    return Redirect::back();
                }
            }
        }
        if (array_key_exists('shop', $basket)) {
            foreach ($basket['shop'] as $itemId => $quantity) {
                if (!ShopItem::hasStockByItemId($itemId)) {
                    $itemName = ShopItem::where('id', $itemId)->first()->name;
                    Session::flash('alert-danger', $itemName . ' basket has Sold Out!');
                    return Redirect::to('/payment/checkout');
                }
            }
        }
        $acceptedPaymentGateways = Settings::getPaymentGateways();
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
                    'card_expiry_month.integer'     => 'Expiry Month must be a Number',
                    'card_expiry_month.between'     => 'Expiry Month must in the Numeric MM format',
                    'card_expiry_year.required'     => 'Expiry Year is Required',
                    'card_expiry_year.integer'      => 'Expiry Year must be a Number',
                    'card_expiry_year.between'      => 'Expiry Year must in the Numeric YY format',
                    'card_cvv.integer'              => 'CVV must be a Number',
                    'card_cvv.between'              => 'CVV must be a 3 Digits long',
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
                    'amount' => (float)Helpers::formatBasket($basket)->total,
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
                    'amount'        => (float)Helpers::formatBasket($basket)->total,
                    'quantity'      => (string)count($basket),
                    'currency'      => Settings::getCurrency(),
                    'user_id'       => Auth::id(),
                );
                $gateway = Omnipay::create('PayPal_Express');
                $gateway->setUsername(config('laravel-omnipay.gateways.paypal_express.credentials.username'));
                $gateway->setPassword(config('laravel-omnipay.gateways.paypal_express.credentials.password'));
                $gateway->setSignature(config('laravel-omnipay.gateways.paypal_express.credentials.signature'));
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
            $this->processBasket($basket, $purchase->id);
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
        if (!$basket = Session::get(Settings::getOrgName() . '-basket')) {
            Session::flash('alert-danger', 'No Basket was found. Please try again');
            return Redirect::back();
        }

        // Currently only PayPal Express
        $paymentGateway = 'paypal_express';

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
        $gateway->setUsername(config('laravel-omnipay.gateways.paypal_express.credentials.username'));
        $gateway->setPassword(config('laravel-omnipay.gateways.paypal_express.credentials.password'));
        $gateway->setSignature(config('laravel-omnipay.gateways.paypal_express.credentials.signature'));
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
            $this->processBasket($basket, $purchase->id);
            return Redirect::to('/payment/successful/' . $purchase->id);
        }
        //Failed transaction
        Session::flash('alert-danger', 'Payment was UNSUCCESSFUL! - Please try again.');
        return Redirect::to('/payment/failed');
    }

    /**
     * Process Basket for Successful Order
     * @param  $basket
     * @param  $purchaseId
     */
    private function processBasket($basket, $purchaseId)
    {
        if (array_key_exists('tickets', $basket)) {
            foreach ($basket['tickets'] as $ticketId => $quantity) {
                $ticket = EventTicket::where('id', $ticketId)->first();
                for ($i = 1; $i <= $quantity; $i++) {
                    //Add Participant to database
                    $participant = [
                        'user_id'       => Auth::id(),
                        'event_id'      => $ticket->event->id,
                        'ticket_id'     => $ticket->id,
                        'purchase_id'   => $purchaseId,
                    ];
                    EventParticipant::create($participant);
                }
            }
        } elseif(array_key_exists('shop', $basket)) {
            $formattedBasket = Helpers::formatBasket($basket);
            $orderParams = [
                'total'         => (float)$formattedBasket->total,
                'total_credit'  => $formattedBasket->total_credit,
                'purchase_id'   => $purchaseId,
                'status'        => 'EVENT'
            ];
            $order = ShopOrder::create($orderParams);
            foreach ($basket['shop'] as $itemId => $quantity) {
                $item = ShopItem::where('id', $itemId)->first();
                $item->updateStock($quantity);
                $order->updateOrder($itemId, $quantity);
            }
        }
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
        $basket = Session::get(Settings::getOrgName() . '-basket');
        $type = 'tickets';
        if (array_key_exists('shop', $basket)) {
            $type = 'shop';
        }
        $basket = Helpers::formatBasket($basket);
        Session::forget('params');
        Session::forget(Settings::getOrgName() . '-basket');
        return view('payments.successful')
            ->withType($type)
            ->withBasket($basket)
            ->withPurchase($purchase)
        ;
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
