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
        Schema::create('operating_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_hub_id')->constrained('collection_hubs')->cascadeOnDelete();
            $table->string('day_of_week'); // lundi, mardi, mercredi, jeudi, vendredi, samedi, dimanche
            $table->string('open_time', 10); // ex: 08:30
            $table->string('close_time', 10); // ex: 18:00
            $table->boolean('is_active')->default(true);
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operating_slots');
    }
};
