<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('energy_kit_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('energy_kit_id')->constrained('energy_kits')->cascadeOnDelete();
            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->string('options')->nullable(); // e.g. "Câble MC4 5m inclus", "Option Trépied orientable"
            $table->timestamps();

            $table->unique(['energy_kit_id', 'equipment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('energy_kit_equipment');
    }
};
