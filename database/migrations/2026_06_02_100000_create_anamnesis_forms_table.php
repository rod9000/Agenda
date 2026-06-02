<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('anamnesis_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('answered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('answered_at')->nullable();

            // Saúde geral
            $table->boolean('heart_problem')->default(false);
            $table->boolean('high_pressure')->default(false);
            $table->boolean('low_pressure')->default(false);
            $table->boolean('diabetes')->default(false);
            $table->boolean('epilepsy')->default(false);
            $table->boolean('cancer')->default(false);
            $table->boolean('autoimmune')->default(false);
            $table->boolean('kidney_disease')->default(false);
            $table->boolean('hepatitis')->default(false);
            $table->boolean('hiv')->default(false);
            $table->boolean('pregnant')->default(false);

            // Pele e procedimentos
            $table->boolean('skin_disease')->default(false);
            $table->boolean('keloids')->default(false);
            $table->boolean('isotretinoin')->default(false);
            $table->boolean('cosmetic_procedure')->default(false);

            // Cirurgias e implantes
            $table->boolean('recent_surgery')->default(false);
            $table->boolean('pacemaker')->default(false);
            $table->boolean('dental_implants')->default(false);

            // Medicamentos e tratamentos
            $table->boolean('allergies')->default(false);
            $table->boolean('medications')->default(false);
            $table->boolean('medical_treatment')->default(false);
            $table->boolean('topical_medication')->default(false);

            // Descrições
            $table->text('allergy_description')->nullable();
            $table->text('medication_description')->nullable();
            $table->text('medical_treatment_description')->nullable();
            $table->text('observation')->nullable();

            // Consentimento
            $table->boolean('consent')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('anamnesis_forms');
    }
};
