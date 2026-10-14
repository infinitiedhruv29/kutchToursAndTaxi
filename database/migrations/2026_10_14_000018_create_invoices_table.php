<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->string('customer_name');
            $table->string('customer_contact', 30)->nullable();
            $table->string('customer_email')->nullable();
            $table->text('customer_address')->nullable();
            $table->string('customer_gst_number', 30)->nullable();
            // Issuer details are frozen here so settings edits do not alter issued invoices.
            $table->json('business_details')->nullable();
            $table->decimal('subtotal', 12, 2)->unsigned()->default(0);
            $table->decimal('tax_rate', 5, 2)->unsigned()->default(0);
            $table->decimal('tax_amount', 12, 2)->unsigned()->default(0);
            $table->decimal('total_amount', 12, 2)->unsigned()->default(0);
            $table->text('notes')->nullable();
            $table->index('invoice_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
