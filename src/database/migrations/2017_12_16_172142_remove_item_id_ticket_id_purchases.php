<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveItemIdTicketIdPurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign('purchases_ticket_id_foreign');
            $table->dropForeign('purchases_user_id_foreign');
            $table->dropColumn('item_id');
            $table->dropColumn('ticket_id');
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
            $table->integer('ticket_id')->unsigned()->nullable()->index()->after('user_id');
            $table->integer('item_id')->unsigned()->nullable()->index()->after('ticket_id');
            
			$table->foreign('ticket_id')->references('id')->on('event_tickets');
			$table->foreign('user_id')->references('id')->on('users');

        });
    }
}
