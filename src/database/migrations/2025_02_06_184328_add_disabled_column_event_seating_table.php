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
        foreach (EventSeatingPlan::all() as $seatingPlan) {
            $seatingPlan->column += 1;
            $seatingPlan->save();
            $seatingPlan->column += 1;
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
        foreach (EventSeating::where('event_participant_id', null)->get() as $seat) {
            $seat->delete();
        }
    }
};
