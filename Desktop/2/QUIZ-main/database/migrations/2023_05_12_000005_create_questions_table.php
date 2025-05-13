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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->longText('question_text');
            $table->enum('question_type', ['multiple_choice', 'true_false', 'text'])->default('multiple_choice');
            $table->integer('points')->default(1);
            $table->integer('time_limit')->nullable()->comment('Time limit in seconds for this specific question');
            $table->string('media_url')->nullable()->comment('URL to image, video or audio file');
            $table->string('media_type')->nullable()->comment('image, video, audio');
            $table->string('hint')->nullable();
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
        Schema::dropIfExists('questions');
    }
};
