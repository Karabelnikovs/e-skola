<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Adds columns that were added via hand-written SQL but are absent from original migrations.
// Every change is guarded with hasColumn so it's safe to run on production.
return new class extends Migration {
    public function up(): void
    {
        // ── users ─────────────────────────────────────────────────────────────
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken();
            }
            if (!Schema::hasColumn('users', 'language')) {
                $table->string('language', 5)->default('lv');
            }
        });

        // ── courses ───────────────────────────────────────────────────────────
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'img')) {
                $table->string('img')->nullable();
            }
            foreach (['title_lv', 'title_en', 'title_ru', 'title_ua'] as $col) {
                if (!Schema::hasColumn('courses', $col)) {
                    $table->string($col)->nullable();
                }
            }
            foreach (['description_lv', 'description_en', 'description_ru', 'description_ua'] as $col) {
                if (!Schema::hasColumn('courses', $col)) {
                    $table->text($col)->nullable();
                }
            }
        });

        // ── topics ────────────────────────────────────────────────────────────
        Schema::table('topics', function (Blueprint $table) {
            if (!Schema::hasColumn('topics', 'course_id')) {
                $table->unsignedBigInteger('course_id')->nullable();
            }
            if (!Schema::hasColumn('topics', 'section_id')) {
                // section_id may already exist as NOT NULL from original migration; make nullable.
            }
            foreach (['title_lv', 'title_en', 'title_ru', 'title_ua'] as $col) {
                if (!Schema::hasColumn('topics', $col)) {
                    $table->string($col)->nullable();
                }
            }
            foreach (['content_lv', 'content_en', 'content_ru', 'content_ua'] as $col) {
                if (!Schema::hasColumn('topics', $col)) {
                    $table->text($col)->nullable();
                }
            }
        });

        // ── tests ─────────────────────────────────────────────────────────────
        Schema::table('tests', function (Blueprint $table) {
            foreach (['title_lv', 'title_en', 'title_ru', 'title_ua'] as $col) {
                if (!Schema::hasColumn('tests', $col)) {
                    $table->string($col)->nullable();
                }
            }
        });

        // ── questions ─────────────────────────────────────────────────────────
        Schema::table('questions', function (Blueprint $table) {
            foreach (['question_lv', 'question_en', 'question_ru', 'question_uk'] as $col) {
                if (!Schema::hasColumn('questions', $col)) {
                    $table->string($col)->nullable();
                }
            }
            foreach (['options_lv', 'options_en', 'options_ru', 'options_ua'] as $col) {
                if (!Schema::hasColumn('questions', $col)) {
                    $table->text($col)->nullable();
                }
            }
            if (!Schema::hasColumn('questions', 'image')) {
                $table->string('image')->nullable();
            }
            if (!Schema::hasColumn('questions', 'audio')) {
                $table->string('audio')->nullable();
            }
        });

        // ── certificates ──────────────────────────────────────────────────────
        Schema::table('certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('certificates', 'is_read')) {
                $table->integer('is_read')->default(0);
            }
        });
    }

    public function down(): void
    {
        // Column additions are not rolled back to avoid data loss.
    }
};
