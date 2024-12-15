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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            // $table->string('section_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('content_text')->nullable();
            $table->string('bg_color', 7);
            $table->float('bg_opacity')->default(1);
            $table->string('button_text')->nullable();
            $table->string('button_color', 7);
            $table->string('button_text_color', 7);
            $table->string('text_color', 7);
            $table->boolean('must_be_filled')->default(false);
            $table->integer('max_choices')->default(0);
            $table->integer('min_choices')->default(0);
            $table->integer('options_count')->default(0);
            $table->boolean('other_option')->default(false);
            $table->boolean('large_label')->default(false);
            $table->boolean('mid_label')->default(false);
            $table->boolean('small_label')->default(false);
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
        Schema::dropIfExists('sections');
    }
};
