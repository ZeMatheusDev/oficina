<?php

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
        Schema::create('readjustments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('month', 255)->nullable();
            $table->string('frequency', 255)->nullable();
            $table->string('source', 255)->nullable();
            $table->string('modality_id', 255)->nullable();
            $table->string('increase_perc', 255)->nullable();
            $table->string('increase_value', 255)->nullable();
            $table->mediumText('observation')->nullable();
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
        Schema::dropIfExists('readjustments');
    }
};
