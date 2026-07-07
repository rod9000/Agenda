<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop foreign key
        $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'working_hours' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE working_hours DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // Drop unique index
        $indexes = DB::select("SHOW INDEX FROM working_hours WHERE Column_name = 'day_of_week' AND Non_unique = 0");
        foreach ($indexes as $idx) {
            DB::statement("ALTER TABLE working_hours DROP INDEX `{$idx->Key_name}`");
        }

        // Make user_id nullable
        DB::statement("ALTER TABLE working_hours MODIFY user_id BIGINT UNSIGNED NULL");

        // Re-add foreign key with set null
        DB::statement("ALTER TABLE working_hours ADD CONSTRAINT working_hours_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL");
    }

    public function down()
    {
        $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'working_hours' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE working_hours DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        DB::statement("ALTER TABLE working_hours MODIFY user_id BIGINT UNSIGNED NOT NULL");
        DB::statement("ALTER TABLE working_hours ADD UNIQUE KEY working_hours_user_id_day_of_week_unique (user_id, day_of_week)");
        DB::statement("ALTER TABLE working_hours ADD CONSTRAINT working_hours_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE");
    }
};
