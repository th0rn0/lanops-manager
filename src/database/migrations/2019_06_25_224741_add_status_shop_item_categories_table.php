<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusShopItemCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_item_categories', function (Blueprint $table) {
            $table->enum('status', array('DRAFT','PUBLISHED', 'HIDDEN'))->after('order')->default('DRAFT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_item_categories', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
