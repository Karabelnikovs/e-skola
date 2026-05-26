<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// The original create_translations_table migration used a morphs/entity structure
// that doesn't match production. This migration creates or corrects the real schema.
return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('translations')) {
            // If the table already has the correct column, nothing to do.
            if (Schema::hasColumn('translations', 'dictionary_id')) {
                // Add audio_lv if missing (avoids failure from the older add_audio_lv migration).
                if (!Schema::hasColumn('translations', 'audio_lv')) {
                    Schema::table('translations', function (Blueprint $table) {
                        $table->string('audio_lv')->nullable();
                    });
                }
                return;
            }

            // Table exists with the wrong morphs structure — recreate it.
            Schema::drop('translations');
        }

        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dictionary_id');
            $table->string('phrase_en')->nullable();
            $table->string('phrase_lv')->nullable();
            $table->string('phrase_ua')->nullable();
            $table->string('phrase_ru')->nullable();
            $table->string('audio_lv')->nullable();
            $table->integer('order');
            $table->timestamps();
            $table->foreign('dictionary_id')->references('id')->on('dictionaries')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // No destructive rollback — the original migration handles its own down().
    }
};
