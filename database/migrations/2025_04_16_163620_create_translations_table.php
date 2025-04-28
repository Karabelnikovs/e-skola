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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->morphs('entity');
            $table->string('language', 5);
            $table->string('field');
            $table->text('translation');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('translations');
    }
};
