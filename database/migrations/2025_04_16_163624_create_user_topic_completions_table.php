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
        if (Schema::hasTable('user_topic_completions')) {
            return;
        }

        Schema::create('user_topic_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');
            $table->timestamp('completed_at');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('user_topic_completions');
    }
};
