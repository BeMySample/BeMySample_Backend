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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('backgroundImage')->nullable();
            $table->string('bgColor', 7);
            $table->boolean('createdByAI')->default(false);
            $table->integer('respondents')->default(0);
            $table->integer('maxRespondents')->nullable();
            $table->integer('coinAllocated')->nullable();
            $table->integer('coinUsed')->nullable();
            $table->string('kriteria')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->string('surveyTitle');
            $table->string('surveyDescription');
            $table->string('thumbnail');
            // $table->string('updated');
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
        Schema::dropIfExists('surveys');
    }
};
