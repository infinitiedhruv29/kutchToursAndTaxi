<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('vehicle_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('vehicle_capacity_id')->constrained()->restrictOnDelete();
            $table->string('image_path')->nullable();
            $table->string('image_alt')->nullable();
            $table->decimal('price_per_km', 12, 2)->unsigned()->nullable();
            $table->unsignedInteger('min_km_per_day')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->index(['is_published', 'sort_order']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
