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
        Schema::create('collaborators_professional_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('admission_date');
            $table->bigInteger('demission_date');
            $table->bigInteger('corporative_mail')->nullable();
            $table->bigInteger('collaborator_id');
            $table->bigInteger('office_id')->nullable();
            $table->bigInteger('modality_id')->nullable();
            $table->bigInteger('level_id')->nullable();
            $table->bigInteger('department_id')->nullable();
            $table->bigInteger('cost_center_id')->nullable();
            $table->bigInteger('working_day_id')->nullable();
            $table->string('benefit_id', 255)->nullable();
            $table->string('wage_id', 255)->nullable();
            $table->string('admission_date', 255)->nullable();
            $table->string('demission_date', 255)->nullable();
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
        Schema::dropIfExists('collaborators_professional_data');
    }
};
