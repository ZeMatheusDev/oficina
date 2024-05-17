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
        Schema::create('working_days_of_weeks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('working_day_id');
            $table->string('day', 255);
            $table->string('morning_start', 255)->nullable();
            $table->string('morning_end', 255)->nullable();
            $table->string('interval_start', 255)->nullable();
            $table->string('interval_end', 255)->nullable();
            $table->string('afternoon_start', 255)->nullable();
            $table->string('afternoon_end', 255)->nullable();
            $table->integer('enabled');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('working_days_of_weeks');
    }
};
