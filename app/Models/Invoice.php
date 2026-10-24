<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'invoice_number',
    'invoice_date',
    'customer_name',
    'customer_contact',
    'customer_email',
    'customer_address',
    'customer_gst_number',
    'business_details',
    'subtotal',
    'tax_rate',
    'tax_amount',
    'total_amount',
    'notes',
])]
class Invoice extends Model
{
    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'business_details' => 'array',
            'subtotal' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order')->orderBy('id');
    }

    public static function generateInvoiceNumber(?string $date = null): string
    {
        $dayPrefix = date('Ymd', $date ? strtotime($date) : time());
        $prefix = "INV-{$dayPrefix}-";

        $lastInvoice = static::where('invoice_number', 'LIKE', "{$prefix}%")
            ->orderByDesc('id')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, strrpos($lastInvoice->invoice_number, '-') + 1);
            $nextNumber = str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return "{$prefix}{$nextNumber}";
    }
}
