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

}
