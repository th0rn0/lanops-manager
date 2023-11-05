<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\EventSeating;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_seating', function (Blueprint $table) {
            $table->string('seat')->after('id');
        });
        $seatings = EventSeating::get();

        foreach ($seatings as $seating) {

            $header = Helpers::getLatinAlphabetUpperLetterByIndex($seating->column);

            $seating->seat = $header.$seating->row;
            if (!$seating->save()) {
                throw new \Exception("Down migration stopped due to invalid data for seat column: $seating->column seat row: $seating->row . This might take manual fixing! Get support!");
            }

        }
        Schema::table('event_seating', function (Blueprint $table) {
            $table->dropColumn('column');
            $table->dropColumn('row');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('event_seating', function (Blueprint $table) {
            $table->integer('column')->nullable()->after('id');
            $table->integer('row')->nullable()->after('column');
        });

        $seatings = EventSeating::get();

        foreach ($seatings as $seating) {
            $header = Str::of($seating->seat)->match('/([a-zA-Z])/');
            $row = Str::of($seating->seat)->match('/[a-zA-Z]([0-9]*)/');
            $column = Helpers::getLatinAlphabetLetterIndex($header);

            $seating->row = $row;
            $seating->column = $column;
            if (!$seating->save()) {
                throw new \Exception("Migration stopped due to invalid data for seat: $seating->seat. This might take manual fixing! Get support!");
            }
        }

        Schema::table('event_seating', function (Blueprint $table) {
            $table->dropColumn('seat');
        }); 

    }
};