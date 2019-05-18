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
	protected $sandbox = FALSE;

	/**
	 * Review Payment Page
	 * @return View
	 */
	public function review()
	{
	  	if (!$basket = Session::get('basket')) {
			return Redirect::to('/');
	  	}
	  	$nextEventFlag = true;
		foreach (Session::get('basket') as $ticketId => $quantity) {
			if (EventTicket::where('id', $ticketId)->first()->event->id != Event::where('end', '>=', \Carbon\Carbon::now())->orderBy(\DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()->id) {
				$nextEventFlag = false;
			}
		}
	  	return view('payments.review')
	  		->withBasketItems(Helpers::getBasketFormat($basket, true))
	  		->withBasketTotal(Helpers::getBasketTotal($basket))
	  		->withNextEventFlag($nextEventFlag);
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
	  	if (config('app.debug')) {
			$this->sandbox = TRUE;
	  	}
	  	$requestScheme = 'http';
		if ( 
				(! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') ||
				(! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ||
				(! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') 
			) {
			$requestScheme = 'https';
		}
		//Paypal Post Params
		$params = array(
			'cancelUrl'		=> $requestScheme . '://' . $_SERVER['HTTP_HOST'] . '/payment/callback?type=cancel',
			'returnUrl'		=> $requestScheme . '://' . $_SERVER['HTTP_HOST'] . '/payment/callback?type=return', 
			'name'			=> Settings::getOrgName() . ' - Tickets Purchase',
			'description'	=> 'Ticket Purchase for ' . Settings::getOrgName(), 
			'amount'		=> (float)Helpers::getBasketTotal($basket),
			'quantity'		=> (string)count($basket),
			'currency'		=> Settings::getCurrency(),
			'user_id'		=> Auth::id(),
		);

	  	Session::put('params', $params);
	  	Session::save();  
	  	$gateway = Omnipay::create('PayPal_Express');
	  	$gateway->setUsername(config('laravel-omnipay.gateways.paypal.credentials.username'));
	  	$gateway->setPassword(config('laravel-omnipay.gateways.paypal.credentials.password'));
	  	$gateway->setSignature(config('laravel-omnipay.gateways.paypal.credentials.signature'));

	  	if ($this->sandbox) {
			$gateway->setTestMode(true);
	  	} else {
			$gateway->setTestMode(false);
	  	}
	  	$response = $gateway->purchase($params)->send();
	  	if ($response->isSuccessful()) {
			// payment was successful: update database
			print_r($response);
	  	} elseif ($response->isRedirect()) {
			// redirect to offsite payment gateway
			$response->redirect();
	  	}
		// payment failed: display message to customer
		echo $response->getMessage();
	}

	/**
	 * Process Callback Payment
	 * @param  Request $request
	 * @return Redirect
	 */
	public function process(Request $request)
	{
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
			$this->sandbox = TRUE;
	  	}
	  	$gateway = Omnipay::create('PayPal_Express');
	  	$gateway->setUsername(config('laravel-omnipay.gateways.paypal.credentials.username'));
	  	$gateway->setPassword(config('laravel-omnipay.gateways.paypal.credentials.password'));
	  	$gateway->setSignature(config('laravel-omnipay.gateways.paypal.credentials.signature'));
	  	if ($this->sandbox) {
			$gateway->setTestMode(true);
	  	} else {
			$gateway->setTestMode(false);
	  	}
	  	//Complete Purchase
	  	$gateway->completePurchase($params)->send();
	  	$response = $gateway->fetchCheckout($params)->send(); // this is the raw response object
 	 	$paypalResponse = $response->getData();
	  	if (isset($paypalResponse['ACK']) && $paypalResponse['ACK'] === 'Success' && isset($paypalResponse['PAYMENTREQUEST_0_TRANSACTIONID'])) {
			//Add Purchase to database
			$purchase 					= new Purchase;
			$purchase->user_id 			= $params['user_id'];
			$purchase->type 			= 'PayPal Express';
			$purchase->transaction_id 	= $paypalResponse['PAYMENTREQUEST_0_TRANSACTIONID'];
			$purchase->token 			= $paypalResponse['TOKEN'];
			$purchase->status 			= $paypalResponse['ACK'];
			$purchase->paypal_email 	= $paypalResponse['EMAIL'];
			$purchase->save();
			foreach (Session::get('basket') as $ticketId => $quantity) {
				$ticket = EventTicket::where('id', $ticketId)->first();
		  		for ($i=1; $i <= $quantity; $i++) { 
					//Add Participant to databade
					$participant 				= new EventParticipant;
					$participant->user_id 		= $params['user_id'];
					$participant->event_id 		= $ticket->event->id;
					$participant->ticket_id 	= $ticket->id;
					$participant->purchase_id 	= $purchase->id;
					$participant->generateQRCode();
					$participant->save();
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
