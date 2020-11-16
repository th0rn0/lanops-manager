<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveFoodListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('food_list');
        Schema::drop('food_orders');
        Schema::drop('food_order_items');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::create('food_list', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->float('price', 10, 0);
			$table->integer('size');
			$table->timestamps();
        });
        Schema::create('food_orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->boolean('paid');
			$table->float('debt', 10, 0);
			$table->integer('event_id');
			$table->timestamps();
        });
        Schema::create('food_order_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('food_id');
			$table->integer('order_id;');
			$table->timestamps();
		});        
        
    }
}
