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
        Schema::create('survey', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('judul_survey');
            $table->string('deskripsi_survey');
            $table->string('thumbnail')->nullable();
            $table->string('status');
            $table->string('responden_now')->nullable();
            $table->integer('coin_allocated')->default(0);
            $table->integer('coin_used')->default(0);
            $table->integer('jumlah_soal')->default(0);
            $table->foreignId('desainAttr');
            $table->foreignId('kriteria');
            $table->dateTime('tanggal')->nullable();
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
        Schema::dropIfExists('survey');
    }
};
