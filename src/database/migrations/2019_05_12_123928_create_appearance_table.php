<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppearanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appearance', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->string('value', 20000)->nullable()->default(null);
            $table->enum('type', array('CSS_VAR','CSS_RAW', 'TEMPLATE_VAR', 'TEMPLATE_RAW'))->default('CSS_VAR');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appearance');
    }
}
