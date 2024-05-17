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
        Schema::create('wages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('modality_id', 255)->nullable();
            $table->string('office_Id', 255)->nullable();
            $table->string('benefit_id', 255)->nullable();
            $table->integer('status');
            $table->mediumText('observation')->nullable();
            $table->string('wage_fix', 255)->nullable();
            $table->string('gratification', 255)->nullable();
            $table->string('comisssion', 255)->nullable();
            $table->string('total_value', 255)->nullable();
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
        Schema::dropIfExists('wages');
    }
};
