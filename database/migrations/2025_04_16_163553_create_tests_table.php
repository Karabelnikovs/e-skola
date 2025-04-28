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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('type')->nullable();
            $table->integer('passing_score');
            $table->integer('order');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('tests');
    }
};
