<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('dark_logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('contact_number', 30)->nullable();
            $table->string('contact_number_2', 30)->nullable();
            $table->string('whatsapp_number', 30)->nullable();
            $table->string('contact_email')->nullable();
            $table->text('address')->nullable();
            $table->text('instagram_link')->nullable();
            $table->text('youtube_link')->nullable();
            $table->text('facebook_link')->nullable();
            $table->text('google_map_link')->nullable();
            $table->string('gst_number', 30)->nullable();
            $table->json('general_inclusions')->nullable();
            $table->json('general_exclusions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
