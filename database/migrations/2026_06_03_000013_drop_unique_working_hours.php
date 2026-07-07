<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('working_hours', function (Blueprint $table) {
            // Drop the foreign key first (name follows Laravel convention)
            $table->dropForeign(['user_id']);
            // Now drop the unique index
            $table->dropUnique(['user_id', 'day_of_week']);
            // Re-add foreign key as nullable
            $table->foreign('user_id')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('working_hours', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->unique(['user_id', 'day_of_week']);
            $table->foreign('user_id')->constrained()->cascadeOnDelete();
        });
    }
};
