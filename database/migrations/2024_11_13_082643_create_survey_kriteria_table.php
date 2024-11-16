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
        Schema::create('survey_kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('gender_target')->nullable();
            $table->string('age_target')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('hobi')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('tempat_bekerja')->nullable();
            $table->integer('responden_target')->default(0);
            $table->integer('poin_foreach')->default(0);
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
        Schema::dropIfExists('survey_kriteria');
    }
};
