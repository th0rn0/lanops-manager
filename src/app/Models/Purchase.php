<?php

namespace App\Models;

use Helpers;

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
        'referral_code_discount_redeemed_purchase_id',
        'total'
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
            $formattedBasket = Helpers::formatBasket($model->basket);
            if ($model->referral_discount_total > 0 && $formattedBasket->referral_used && $referredPurchase = $model->user->getAvailableReferralPurchase()) {
                $referredPurchase->referral_code_discount_redeemed_purchase_id = $model->id;
                $referredPurchase->save();
            }
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

    public function referralDiscountUsedPurchase()
    {
        return $this->belongsTo('App\Models\Purchase', 'referral_code_discount_redeemed_purchase_id');
    }

    public function referralCodeUsedPurchase()
    {
        return $this->hasOne('App\Models\Purchase', 'referral_code_discount_redeemed_purchase_id');
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
