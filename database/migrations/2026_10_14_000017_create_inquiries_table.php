<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->enum('service_type', ['general', 'taxi', 'route', 'package', 'custom'])->default('general');
            $table->foreignId('taxi_route_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            // Preserve the requested service and direction independently of later content changes.
            $table->string('service_title')->nullable();
            $table->string('origin_name')->nullable();
            $table->string('destination_name')->nullable();
            $table->enum('direction', ['forward', 'reverse'])->nullable();
            $table->enum('trip_type', ['one_way', 'round_trip'])->nullable();
            $table->date('travel_date')->nullable();
            $table->unsignedSmallInteger('passenger_count')->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['new', 'contacted', 'closed'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->index(['status', 'created_at']);
            $table->index(['service_type', 'created_at']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
