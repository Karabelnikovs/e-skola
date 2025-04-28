<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->json('options')->nullable();
            $table->string('correct_answer');
            $table->integer('order');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
