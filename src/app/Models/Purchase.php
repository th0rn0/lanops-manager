<?php

namespace App\Models;

use Helpers;

use App\Models\ReferralCodeAudit;

use App\Models\ReferralCode;


use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'purchases';

    protected $fillable = [
        'user_id',
        'type',
        'transaction_id',
        'token',
        'status',
        'paypal_email',
        'basket',
        'referral_discount_total',
        'referral_code_user_id',
        'total',
        'referral_discount_total',
    ];

    protected $casts = [
        'basket' => 'array'
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

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $formattedBasket = Helpers::formatBasket($model->basket);
            $model->total = $formattedBasket->total;
            $model->total_before_discount = $formattedBasket->total_before_discounts;
            $model->referral_discount_total = $formattedBasket->referral_discount_total;
            if ($formattedBasket->referral_code && $referralUser = User::getuserByReferralCode($formattedBasket->referral_code)) {
                $model->referral_code_user_id = $referralUser->id;
            }
        });
        self::created(function ($model) {
            if ($model->referral_discount_total > 0) {
                $model->user->decrementReferralCounter();
            } elseif ($model->referral_code_user_id) {
                $model->referralUser->incrementReferralCounter();
            }
            // if ($model->referral_code && $userReferral = User::getuserByReferralCode($model->referral_code)) {
            //     $userReferral->incrementReferralCounter();
            //     $referralCodeAuditParams = [
            //         'referral_code' => $model->referral_code,
            //         'event_description' => ReferralCodeAudit::$applied,
            //         'purchase_id' => $model->id,
            //     ];
            //     ReferralCodeAudit::create($referralCodeAuditParams);

            //     $referralCodeParams = [
            //         'referral_purchase_id' => $model->id,
            //     ];

            //     ReferralCode::create($referralCodeParams);
            // }
            // if (Helpers::formatBasket($model->basket,$model->user, null, true)->referral_used && $userReferred = User::where('id', $model->user_id)->first()) {
            //     $userReferred->decrementReferralCounter();
            //     $referralCodeAuditParams = [
            //         'referral_code' => $userReferred->referral_code,
            //         'event_description' => ReferralCodeAudit::$redeemed,
            //         'purchase_id' => $model->id,
            //     ];
            //     ReferralCodeAudit::create($referralCodeAuditParams);
            // }
        });
    }
    /*
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function participants()
    {
        return $this->hasMany('App\Models\EventParticipant', 'purchase_id');
    }

    public function referralUser()
    {
        return $this->belongsTo('App\Models\User', 'referral_code_user_id');
    }

    /**
     * Get Purchase Type
     * @param String
     */
    public function getPurchaseType()
    {
        switch (strtolower($this->type)) {
            case 'stripe':
                return 'Card';
                break;
            case 'paypal express':
                return 'Paypal';
                break;
            default:
                return $this->type;
                break;
        }
    }
}
