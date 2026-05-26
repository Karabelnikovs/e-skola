<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('user_progress')) {
            return;
        }

        Schema::table('user_progress', function (Blueprint $table) {
            if (!Schema::hasColumn('user_progress', 'third_notification_sent_at')) {
                $table->timestamp('third_notification_sent_at')->nullable();
            }
            if (!Schema::hasColumn('user_progress', 'fourth_notification_sent_at')) {
                $table->timestamp('fourth_notification_sent_at')->nullable();
            }
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