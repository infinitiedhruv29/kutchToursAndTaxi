<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_packages', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('client_phone', 30)->nullable();
            $table->string('title');
            $table->string('duration')->nullable();
            $table->string('highlight')->nullable();
            $table->decimal('price_2p', 12, 2)->unsigned()->nullable();
            $table->decimal('price_4p', 12, 2)->unsigned()->nullable();
            $table->decimal('price_6p', 12, 2)->unsigned()->nullable();
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->json('itineraries')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_packages');
    }
};
