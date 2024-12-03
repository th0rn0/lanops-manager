<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->integer('referral_code_discount_redeemed_purchase_id')->after('referral_code_user_id')->unsigned()->index()->nullable();
            
            $table->foreign('referral_code_discount_redeemed_purchase_id')->references('id')->on('purchases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign('purchases_referral_code_discount_redeemed_purchase_id_foreign');
            $table->dropColumn('referral_code_discount_redeemed_purchase_id');
        });
    }
};
