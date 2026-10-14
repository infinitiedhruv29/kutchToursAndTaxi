<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->enum('item_type', ['package', 'taxi', 'other'])->default('other');
            $table->text('description');
            $table->decimal('quantity', 10, 2)->unsigned()->default(1);
            $table->decimal('unit_price', 12, 2)->unsigned()->default(0);
            $table->decimal('amount', 12, 2)->unsigned()->default(0);
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->string('vehicle_name')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_capacity')->nullable();
            $table->decimal('price_per_km', 12, 2)->unsigned()->nullable();
            $table->decimal('total_distance_km', 10, 2)->unsigned()->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->index(['invoice_id', 'sort_order']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
