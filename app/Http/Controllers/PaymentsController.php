<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

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

use App\Http\Controllers\Controller;
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
	  return view('payments.review')->withBasketItems(Helpers::getBasketFormat($basket, true))->withBasketTotal(Helpers::getBasketTotal($basket));
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
	  	if(env('APP_DEBUG')){
			$this->sandbox = TRUE;
	  	}

		//Paypal Post Params
		$params = array(
			'cancelUrl'     => 'https://' . $_SERVER['SERVER_NAME'] . '/payment/callback?type=cancel',
			'returnUrl'     => 'https://' . $_SERVER['SERVER_NAME'] . '/payment/callback?type=return', 
			'name'          => Settings::getOrgName() . ' - Tickets Purchase',
			'description'   => 'Ticket Purchase for ' . Settings::getOrgName(), 
			'amount'        => (float)Helpers::getBasketTotal($basket),
			'quantity'      => (string)count($basket),
			'currency'      => Settings::getCurrency(),
			'user_id'       => Auth::id(),
		);

	  	Session::put('params', $params);
	  	Session::save();  
	  
	  	$gateway = Omnipay::create('PayPal_Express');
	  	$gateway->setUsername(env('PAYPAL_USERNAME'));
	  	$gateway->setPassword(env('PAYPAL_PASSWORD'));
	  	$gateway->setSignature(env('PAYPAL_SIGNATURE'));

	  	if($this->sandbox){
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
	  	} else {
			// payment failed: display message to customer
			echo $response->getMessage();
	  	}
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

	  	if(env('APP_DEBUG')){
			$this->sandbox = TRUE;
	  	}

	  	$gateway = Omnipay::create('PayPal_Express');
	  	$gateway->setUsername(env('PAYPAL_USERNAME'));
	  	$gateway->setPassword(env('PAYPAL_PASSWORD'));
	  	$gateway->setSignature(env('PAYPAL_SIGNATURE'));

	  	if($this->sandbox){
			$gateway->setTestMode(true);
	  	} else {
			$gateway->setTestMode(false);
	  	}

	  	//Complete Purchase
	  	$gateway->completePurchase($params)->send();
	  	$response = $gateway->fetchCheckout($params)->send(); // this is the raw response object
 	 	$paypal_response = $response->getData();
	  	if(isset($paypal_response['ACK']) && $paypal_response['ACK'] === 'Success' && isset($paypal_response['PAYMENTREQUEST_0_TRANSACTIONID'])) {
			//Add Purchase to database
			$purchase = new Purchase;
			$purchase->user_id = $params['user_id'];
			$purchase->type = 'PayPal Express';
			$purchase->transaction_id = $paypal_response['PAYMENTREQUEST_0_TRANSACTIONID'];
			$purchase->token = $paypal_response['TOKEN'];
			$purchase->status = $paypal_response['ACK'];
			$purchase->paypal_email = $paypal_response['EMAIL'];
			$purchase->save();
		
			foreach (Session::get('basket') as $ticket_id => $quantity) {
				$ticket = EventTicket::where('id', $ticket_id)->first();
		  		for ($i=1; $i <= $quantity; $i++) { 
					//Add Participant to databade
					$participant = new EventParticipant;
					$participant->user_id = $params['user_id'];
					$participant->event_id = $ticket->event->id;
					$participant->ticket_id = $ticket->id;
					$participant->purchase_id = $purchase->id;
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
