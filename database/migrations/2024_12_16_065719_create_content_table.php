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
        Schema::create('content', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->onDelete('cascade');  
            $table->string('bgColor')->nullable();   
            $table->float('bgOpacity')->nullable();  
            $table->string('buttonColor')->nullable();   
            $table->string('buttonText')->nullable();   
            $table->string('buttonTextColor')->nullable();  
            $table->text('contentText')->nullable();   
            $table->string('dateFormat')->nullable();   
            $table->text('description')->nullable();   
            $table->string('largeLabel')->nullable();   
            $table->json('listChoices')->nullable();   
            $table->integer('maxChoices')->nullable();
            $table->string('midLabel')->nullable();  
            $table->integer('minChoices')->nullable();
            $table->boolean('mustBeFilled')->default(true);   
            $table->integer('optionsCount')->nullable(); 
            $table->boolean('otherOption')->default(false);   
            $table->string('smallLabel')->nullable(); 
            $table->string('textColor')->nullable();
            $table->string('timeFormat')->nullable();   
            $table->string('title')->nullable();
            $table->boolean('toggleResponseCopy')->default(false);   
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
        Schema::dropIfExists('content');
    }
};
