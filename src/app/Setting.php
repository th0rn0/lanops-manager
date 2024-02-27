<?php

namespace App;

use DB;
use Storage;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'settings';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'setting',
        'value',
        'default',
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
     * Get About Who's Who
     * @return String
     */
    public static function getAboutWho()
    {
        return self::where('setting', 'about_who')->first()->value;
    }

    /**
     * Set About Who's Who
     * @param String $text
     */
    public static function setAboutWho($text)
    {
        $setting = self::where('setting', 'about_who')->first();
        $setting->value = $text;
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Active Payment Gateways
     * @return Array
     */
    public static function getPaymentGateways()
    {
        $paymentGateways = self::where('setting', 'like', '%payment_gateway_%')->get();
        $return = array();
        foreach ($paymentGateways as $gateway) {
            if ($gateway->value) {
                $return[] = str_replace('payment_gateway_', '', $gateway->setting);
            }
        }
        return $return;
    }

    /**
     * Get Supported Payment Gateways
     * @return Array
     */
    public static function getSupportedPaymentGateways()
    {
        $return = array();
        foreach (config('laravel-omnipay.gateways') as $key => $gateway) {
            $return[] = $key;
        }
        return $return;
    }

    /**
     * Enable Payment Gateway
     * @return Boolean
     */
    public static function enablePaymentGateway($gateway)
    {
        if (!$paymentGateway = self::where('setting', 'like', '%payment_gateway_'. $gateway . '%')->first()) {
            return false;
        }
        $paymentGateway->value = true;
        if (!$paymentGateway->save()) {
            return false;
        }
        return true;
    }

    /**
     * Disable Payment Gateway
     * @return Boolean
     */
    public static function disablePaymentGateway($gateway)
    {
        if (!$paymentGateway = self::where('setting', 'like', '%payment_gateway_'. $gateway . '%')->first()) {
            return false;
        }
        $paymentGateway->value = false;
        if (!$paymentGateway->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Payment Gateway Display Name
     * @return String
     */
    public static function getPaymentGatewayDisplayName($gateway)
    {
        return config('laravel-omnipay.gateways.' . $gateway . '.options.displayName');
    }

    /**
     * Get Payment Gateway Note
     * @return String
     */
    public static function getPaymentGatewayNote($gateway)
    {
        return config('laravel-omnipay.gateways.' . $gateway . '.options.note');
    }

    /**
     * Get SEO Keywords
     * @return String
     */
    public static function getSeoKeywords()
    {
        return self::where('setting', 'seo_keywords')->first()->value;
    }

    /**
     * Set SEO Keywords
     * @param String $keywords
     */
    public static function setSeoKeywords($keywords)
    {
        
        $setting = self::where('setting', 'seo_keywords')->first();
        $setting->value = implode(',', array_map('trim', explode(',', $keywords)));
        if (!$setting->save()) {
            return false;
        }
        return true;
    }

}
