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
        Schema::create('kontribusi_explore', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_author');
            $table->string('thumbnail')->nullable();
            $table->string('judul');
            $table->integer('coin')->default(0);
            $table->foreignId('kriteria');
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
        Schema::dropIfExists('kontribusi_explore');
    }
};
