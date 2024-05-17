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
        Schema::create('collaborators_documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('collaborator_id');
            $table->string('type', 255);
            $table->string('number', 255);
            $table->string('attachment_front', 255);
            $table->string('attachment_back', 255);
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
        Schema::dropIfExists('collaborators_documents');
    }
};
