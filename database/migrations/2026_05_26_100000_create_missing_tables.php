<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // sessions
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        // password_reset_tokens
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->index();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        // dictionaries
        if (!Schema::hasTable('dictionaries')) {
            Schema::create('dictionaries', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_id');
                $table->string('title_lv')->nullable();
                $table->string('title_en')->nullable();
                $table->string('title_ru')->nullable();
                $table->string('title_ua')->nullable();
                $table->integer('order');
                $table->timestamps();
                $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            });
        }

        // user_progress
        if (!Schema::hasTable('user_progress')) {
            Schema::create('user_progress', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('course_id');
                $table->unsignedInteger('current_order')->default(0);
                $table->timestamp('first_notification_sent_at')->nullable();
                $table->timestamp('second_notification_sent_at')->nullable();
                $table->timestamp('third_notification_sent_at')->nullable();
                $table->timestamp('fourth_notification_sent_at')->nullable();
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            });
        }

        // contacts
        if (!Schema::hasTable('contacts')) {
            Schema::create('contacts', function (Blueprint $table) {
                $table->id();
                $table->json('addresses')->nullable();
                $table->json('phone_numbers')->nullable();
                $table->json('emails')->nullable();
                $table->timestamps();
            });
        }

        // terms
        if (!Schema::hasTable('terms')) {
            Schema::create('terms', function (Blueprint $table) {
                $table->id();
                $table->text('content_en')->nullable();
                $table->text('content_lv')->nullable();
                $table->text('content_ua')->nullable();
                $table->text('content_ru')->nullable();
                $table->timestamps();
            });
        }

        // privacy
        if (!Schema::hasTable('privacy')) {
            Schema::create('privacy', function (Blueprint $table) {
                $table->id();
                $table->text('content_en')->nullable();
                $table->text('content_lv')->nullable();
                $table->text('content_ua')->nullable();
                $table->text('content_ru')->nullable();
                $table->timestamps();
            });
        }

        // welcome_email
        if (!Schema::hasTable('welcome_email')) {
            Schema::create('welcome_email', function (Blueprint $table) {
                $table->id();
                $table->text('content_en')->nullable();
                $table->text('content_lv')->nullable();
                $table->text('content_ua')->nullable();
                $table->text('content_ru')->nullable();
                $table->timestamps();
            });
        }

        // cookies
        if (!Schema::hasTable('cookies')) {
            Schema::create('cookies', function (Blueprint $table) {
                $table->id();
                $table->text('content_en')->nullable();
                $table->text('content_lv')->nullable();
                $table->text('content_ua')->nullable();
                $table->text('content_ru')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cookies');
        Schema::dropIfExists('welcome_email');
        Schema::dropIfExists('privacy');
        Schema::dropIfExists('terms');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('user_progress');
        Schema::dropIfExists('dictionaries');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
