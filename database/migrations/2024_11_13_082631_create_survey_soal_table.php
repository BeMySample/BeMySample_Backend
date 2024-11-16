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
        Schema::create('survey_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id');
            $table->string('heading');
            $table->string('desc')->nullable();
            $table->foreignId('tipe_soal')->constrained('soal_type');
            $table->foreignId('jawaban')->nullable();
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
        Schema::dropIfExists('survey_soal');
    }
};
