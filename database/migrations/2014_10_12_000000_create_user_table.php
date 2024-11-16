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
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('password');
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->nullable();
            $table->integer('umur')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('minat')->nullable();
            $table->string('institusi')->nullable();
            $table->integer('poin_saya')->default(0);
            $table->string('pekerjaan')->nullable();
            $table->string('profilepic')->nullable();
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
        Schema::dropIfExists('user');
    }
};
