<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusVariablesShopOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE shop_orders CHANGE COLUMN status status ENUM('PROCESSING','SHIPPED', 'EVENT', 'COMPLETE', 'CANCELLED', 'ERROR') NOT NULL DEFAULT 'EVENT'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE shop_orders CHANGE COLUMN status status ENUM('PROCESSING','SHIPPED', 'EVENT', 'COMPLETE')");
    }
}
