<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('cnpj')->nullable();
            // Evolution API
            $table->string('evolution_api_url')->nullable();
            $table->string('evolution_api_key')->nullable();
            $table->string('evolution_instance_name')->nullable();
            $table->string('whatsapp_type')->default('normal');
            // Bot/Webhook
            $table->boolean('webhook_enabled')->default(true);
            $table->boolean('bot_enabled')->default(true);
            $table->text('welcome_message')->nullable();
            $table->text('off_hours_message')->nullable();
            $table->string('evolution_webhook_url')->nullable();
            // Trial
            $table->timestamp('trial_starts_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
