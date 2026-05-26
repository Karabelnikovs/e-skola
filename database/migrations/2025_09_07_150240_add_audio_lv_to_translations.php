<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('translations') || Schema::hasColumn('translations', 'audio_lv')) {
            return;
        }

        Schema::table('translations', function (Blueprint $table) {
            $table->string('audio_lv')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('translations', function (Blueprint $table) {
            $table->dropColumn('audio_lv');
        });
    }
};