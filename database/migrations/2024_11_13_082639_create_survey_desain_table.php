<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('survey_desain', function (Blueprint $table) {
            $table->id();
            $table->string('background_img')->nullable();
            $table->string('opacity')->nullable();
            $table->string('warna_latar')->nullable();
            $table->string('warna_tombol')->nullable();
            $table->string('warna_tombol_text')->nullable();
            $table->string('warna_text')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('survey_desain');
    }
};
