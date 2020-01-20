<?php

namespace App;

use DB;
use Storage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ApiKey extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'api_keys';

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
        'updated_at'
    );

    /**
     * Set Paypal Username
     * @param String $username
     * @return Boolean
     */
    public static function setPayPalUsername($username)
    {
 		$key = self::where('key', 'paypal_username')->first();
        $key->value = encrypt($username);
        if (!$key->save()) {
            return false;
        }
        return true;
    }

    /**
     * Set Paypal Password
     * @param String $password
     * @return Boolean
     */
	public static function setPayPalPassword($password)
    {
 		$key = self::where('key', 'paypal_password')->first();
        $key->value = encrypt($password);
        if (!$key->save()) {
            return false;
        }
        return true;
    }
 
 	/**
     * Set Paypal Signature
     * @param String $signature
     * @return Boolean
     */	
 	public static function setPayPalSignature($signature)
    {
 		$key = self::where('key', 'paypal_signature')->first();
        $key->value = encrypt($signature);
        if (!$key->save()) {
            return false;
        }
        return true;
    }    

	/**
     * Set Stripe Public Key
     * @param String $publicKey
     * @return Boolean
     */	
 	public static function setStripePublicKey($publicKey)
    {
 		$key = self::where('key', 'stripe_public_key')->first();
        $key->value = encrypt($publicKey);
        if (!$key->save()) {
            return false;
        }
        return true;
    }    

	/**
     * Set Stripe Private Key
     * @param String $privateKey
     * @return Boolean
     */	
 	public static function setStripePrivateKey($privateKey)
    {
 		$key = self::where('key', 'stripe_private_key')->first();
        $key->value = encrypt($privateKey);
        if (!$key->save()) {
            return false;
        }
        return true;
    }    
}
