<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned()->index();
            $table->enum('action', array('ADD','SUB','BUY'));
            $table->integer('amount');
            $table->integer('event_ticket_id')->unsigned()->index()->nullable();
            $table->integer('admin_id')->unsigned()->index()->nullable();
            $table->timestamps();

            ## Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_ticket_id')->references('id')->on('event_tickets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign('credit_log_user_id_foreign');
        $table->dropForeign('credit_log_admin_id_foreign');
        $table->dropForeign('credit_logs_event_ticket_id_foreign');
        Schema::dropIfExists('credit_log');
    }
}
