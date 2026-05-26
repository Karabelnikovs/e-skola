<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

// Idempotent: checks information_schema before adding each index.
return new class extends Migration {
    public function up(): void
    {
        $this->addIndex('topics', 'topics_course_id_index', ['course_id']);
        $this->addIndex('topics', 'topics_course_id_order_index', ['course_id', 'order']);

        $this->addIndex('tests', 'tests_course_id_index', ['course_id']);
        $this->addIndex('tests', 'tests_course_id_order_index', ['course_id', 'order']);

        $this->addIndex('dictionaries', 'dictionaries_course_id_index', ['course_id']);
        $this->addIndex('dictionaries', 'dictionaries_course_id_order_index', ['course_id', 'order']);

        $this->addIndex('translations', 'translations_dictionary_id_index', ['dictionary_id']);
        $this->addIndex('translations', 'translations_dictionary_id_order_index', ['dictionary_id', 'order']);

        $this->addIndex('questions', 'questions_test_id_index', ['test_id']);
        $this->addIndex('questions', 'questions_test_id_order_index', ['test_id', 'order']);

        $this->addIndex('attempts', 'attempts_user_id_test_id_index', ['user_id', 'test_id']);
        $this->addIndex('attempts', 'attempts_test_id_passed_index', ['test_id', 'passed']);

        $this->addIndex('answers', 'answers_attempt_id_index', ['attempt_id']);
        $this->addIndex('answers', 'answers_question_id_index', ['question_id']);

        $this->addUniqueIndex('user_topic_completions', 'utc_user_topic_unique', ['user_id', 'topic_id']);
        $this->addUniqueIndex('user_progress', 'user_progress_user_course_unique', ['user_id', 'course_id']);

        $this->addIndex('certificates', 'certificates_user_id_index', ['user_id']);
        $this->addIndex('certificates', 'certificates_course_id_index', ['course_id']);

        $this->addIndex('pending_users', 'pending_users_token_index', ['token']);
    }

    public function down(): void
    {
        $pairs = [
            ['topics', 'topics_course_id_index'],
            ['topics', 'topics_course_id_order_index'],
            ['tests', 'tests_course_id_index'],
            ['tests', 'tests_course_id_order_index'],
            ['dictionaries', 'dictionaries_course_id_index'],
            ['dictionaries', 'dictionaries_course_id_order_index'],
            ['translations', 'translations_dictionary_id_index'],
            ['translations', 'translations_dictionary_id_order_index'],
            ['questions', 'questions_test_id_index'],
            ['questions', 'questions_test_id_order_index'],
            ['attempts', 'attempts_user_id_test_id_index'],
            ['attempts', 'attempts_test_id_passed_index'],
            ['answers', 'answers_attempt_id_index'],
            ['answers', 'answers_question_id_index'],
            ['user_topic_completions', 'utc_user_topic_unique'],
            ['user_progress', 'user_progress_user_course_unique'],
            ['certificates', 'certificates_user_id_index'],
            ['certificates', 'certificates_course_id_index'],
            ['pending_users', 'pending_users_token_index'],
        ];

        foreach ($pairs as [$table, $index]) {
            if (Schema::hasTable($table) && $this->indexExists($table, $index)) {
                try {
                    Schema::table($table, fn (Blueprint $t) => $t->dropIndex($index));
                } catch (\Throwable $e) {
                    Log::warning("Could not drop index {$index} on {$table}: {$e->getMessage()}");
                }
            }
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $db = config('database.connections.' . config('database.default') . '.database');
        return DB::table('information_schema.statistics')
            ->where('table_schema', $db)
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->exists();
    }

    private function addIndex(string $table, string $name, array $columns): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }
        foreach ($columns as $col) {
            if (!Schema::hasColumn($table, $col)) {
                return;
            }
        }
        if ($this->indexExists($table, $name)) {
            return;
        }

        try {
            Schema::table($table, fn (Blueprint $t) => $t->index($columns, $name));
        } catch (\Throwable $e) {
            Log::warning("Could not add index {$name} on {$table}: {$e->getMessage()}");
        }
    }

    private function addUniqueIndex(string $table, string $name, array $columns): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }
        foreach ($columns as $col) {
            if (!Schema::hasColumn($table, $col)) {
                return;
            }
        }
        if ($this->indexExists($table, $name)) {
            return;
        }

        $cols = implode('`, `', $columns);

        try {
            Schema::table($table, fn (Blueprint $t) => $t->unique($columns, $name));
            return;
        } catch (\Throwable) {
            // fall through to raw SQL
        }

        try {
            DB::statement("ALTER TABLE `{$table}` ADD UNIQUE `{$name}` (`{$cols}`)");
        } catch (\Throwable $e) {
            Log::warning("Skipped unique index {$name} on {$table}: {$e->getMessage()}");
        }
    }
};
