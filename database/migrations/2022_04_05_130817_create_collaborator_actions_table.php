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
        Schema::create('collaborator_actions', function (Blueprint $table) {
            $table->id();
            $table->string('action', 255)->nullable();
            $table->string('config_calendar_id', 255)->nullable();
            $table->string('initial_date', 255)->nullable();
            $table->string('final_date', 255)->nullable();
            $table->string('attachment', 255)->nullable();
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
        Schema::dropIfExists('collaborator_actions');
    }
};
