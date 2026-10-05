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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('brand');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('power_watts')->nullable();
            $table->integer('capacity_wh')->nullable();
            $table->decimal('daily_rate', 8, 2);
            $table->decimal('deposit_amount', 8, 2)->default(0);
            $table->boolean('is_available')->default(true);
            $table->string('status')->default('pending'); // default 'pending' (displayed as "Non traité")
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
