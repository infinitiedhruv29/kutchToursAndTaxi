<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxi_route_fares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taxi_route_id')->constrained('taxi_routes')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->decimal('one_way_fare', 12, 2)->unsigned()->nullable();
            $table->decimal('round_trip_fare', 12, 2)->unsigned()->nullable();
            $table->boolean('is_default')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['taxi_route_id', 'vehicle_id']);
            $table->index(['taxi_route_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxi_route_fares');
    }
};

