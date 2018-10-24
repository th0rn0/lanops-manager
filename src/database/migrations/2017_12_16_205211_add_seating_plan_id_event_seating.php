<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeatingPlanIdEventSeating extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_seating', function (Blueprint $table) {
            $table->integer('event_seating_plan_id')->after('event_id')->unsigned()->index();

            ## Foreign Keys
            $table->foreign('event_seating_plan_id')->references('id')->on('event_seating_plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_seating', function (Blueprint $table) {
            $table->dropForeign('event_seating_event_seating_plan_id_foreign');
            $table->dropColumn('event_seating_plan_id');
        });
    }
}
