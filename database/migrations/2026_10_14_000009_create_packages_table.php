<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('package_type', 80)->nullable();
            $table->string('highlight')->nullable();
            $table->string('duration')->nullable();
            $table->longText('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_alt')->nullable();
            $table->decimal('price_2p', 12, 2)->unsigned()->nullable();
            $table->decimal('price_4p', 12, 2)->unsigned()->nullable();
            $table->decimal('price_6p', 12, 2)->unsigned()->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->index(['is_published', 'sort_order']);
            $table->index(['is_published', 'is_featured']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
