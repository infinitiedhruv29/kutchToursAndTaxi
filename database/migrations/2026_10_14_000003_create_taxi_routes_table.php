<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxi_routes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('origin_name');
            $table->string('destination_name');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_alt')->nullable();
            $table->decimal('distance_km', 10, 2)->unsigned()->nullable();
            $table->unsignedInteger('estimated_duration_minutes')->nullable();
            $table->decimal('forward_one_way_fare', 12, 2)->unsigned()->nullable();
            $table->decimal('forward_round_trip_fare', 12, 2)->unsigned()->nullable();
            $table->boolean('reverse_enabled')->default(false);
            // NULL means inherit the corresponding forward fare; zero is an explicit override.
            $table->decimal('reverse_one_way_fare', 12, 2)->unsigned()->nullable();
            $table->decimal('reverse_round_trip_fare', 12, 2)->unsigned()->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->index(['is_published', 'sort_order']);
            $table->index(['is_published', 'is_featured']);
            $table->index(['origin_name', 'destination_name']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxi_routes');
    }
};
