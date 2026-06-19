<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexes extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->index('start');
            $table->index('status');
            $table->index('confirmation_token');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('quantity');
            $table->index('expiry_date');
        });

        Schema::table('notifications_log', function (Blueprint $table) {
            $table->index('status');
            $table->index('type');
            $table->index('sent_at');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->index('phone');
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['start']);
            $table->dropIndex(['status']);
            $table->dropIndex(['confirmation_token']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['quantity']);
            $table->dropIndex(['expiry_date']);
        });

        Schema::table('notifications_log', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['type']);
            $table->dropIndex(['sent_at']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['phone']);
        });
    }
}
