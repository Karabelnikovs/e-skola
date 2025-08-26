<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_progress', function (Blueprint $table) {
            $table->timestamp('third_notification_sent_at')->nullable();
            $table->timestamp('fourth_notification_sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropColumn('third_notification_sent_at');
            $table->dropColumn('fourth_notification_sent_at');
        });
    }
};