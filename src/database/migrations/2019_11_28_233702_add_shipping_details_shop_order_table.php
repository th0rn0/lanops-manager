<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShippingDetailsShopOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->string('shipping_first_name')->after('status')->nullable();
            $table->string('shipping_last_name')->after('shipping_first_name')->nullable();
            $table->string('shipping_address_1')->after('shipping_last_name')->nullable();
            $table->string('shipping_address_2')->after('shipping_address_1')->nullable();
            $table->string('shipping_country')->after('shipping_address_2')->nullable();
            $table->string('shipping_postcode')->after('shipping_country')->nullable();
            $table->string('shipping_state')->after('shipping_postcode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropColumn('shipping_first_name');
            $table->dropColumn('shipping_last_name');
            $table->dropColumn('shipping_address_1');
            $table->dropColumn('shipping_address_2');
            $table->dropColumn('shipping_country');
            $table->dropColumn('shipping_postcode');
            $table->dropColumn('shipping_state');
        });
    }
}
