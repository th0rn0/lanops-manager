<?php

use App\Models\EventSeatingPlan;

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
        Schema::table('event_seating', function (Blueprint $table) {
            $table->boolean('disabled')->default(false);
        });

        // Migration scheme to new Event Seating Data Schema
        foreach (EventSeatingPlan::all() as $seatingPlan) {
            $columns = $seatingPlan->columns;
            $rows = $seatingPlan->rows;
            $seatingPlan->columns = $rows;
            $seatingPlan->rows = $columns;
            $seatingPlan->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_seating_plans', function (Blueprint $table) {
            $table->dropColumn('disabled');
        });

        // Migration scheme to old Event Seating Data Schema
        foreach (EventSeating::where('event_participant_id', null)->get() as $seat) {
            $seat->delete();
        }
    }
};
