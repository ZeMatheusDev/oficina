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
        Schema::create('office', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->bigInteger('department_id')->nullable();
            $table->bigInteger('cost_center_id')->nullable();
            $table->bigInteger('collaborator_id')->nullable();
            $table->bigInteger('level_id')->nullable();
            $table->bigInteger('modality_id')->nullable();
            $table->bigInteger('working_day_id')->nullable();
            $table->bigInteger('master_office_id')->nullable();
            $table->integer('status');
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
        Schema::dropIfExists('office');
    }
};
