<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tax Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 0;
        }

        .meta-container {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 25px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .meta-label {
            font-weight: bold;
            color: #64748b;
            width: 25%;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .meta-value {
            color: #0f172a;
            font-weight: 600;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .items-table th {
            background-color: #047857;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 9px 10px;
            text-align: left;
        }

        .items-table td {
            padding: 9px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
            color: #334155;
        }

        .summary-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .summary-table td {
            padding: 6px 10px;
            font-size: 11px;
        }

        .grand-total {
            background-color: #f0fdf4;
            font-weight: bold;
            font-size: 13px;
            color: #065f46;
            border-top: 2px solid #047857;
            border-bottom: 2px solid #047857;
        }

        .notes-box {
            background-color: #fffbeb;
            border: 1px dashed #fcd34d;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 10px;
            color: #92400e;
            margin-bottom: 30px;
        }

        .footer {
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            text-align: center;
            font-size: 9px;
            color: #64748b;
        }
    </style>
</head>
<body>

    @php
        $biz = $invoice->business_details ?? [];
        $bizName = $biz['name'] ?? ($settings->business_name ?? 'Kutch Tours & Taxi');
        $bizPhone = $biz['contact'] ?? ($settings->contact_number ?? '+91 99042 22293');
        $bizEmail = $biz['email'] ?? ($settings->contact_email ?? 'info@kutchtoursandtaxi.com');
        $bizAddress = $biz['address'] ?? ($settings->address ?? 'Bhuj, Kutch, Gujarat, India');
        $bizGst = $biz['gst_number'] ?? ($settings->gst_number ?? '');
    @endphp

    <table class="header-table">
        <tr>
            <td style="width: 50%;">
                @if ($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo" style="max-height: 70px; max-width: 180px;">
                @else
                    <span style="font-size: 18px; font-weight: bold; color: #0f172a;">
                        {{ $bizName }}
                    </span>
                @endif
                <div style="font-size: 10px; color: #64748b; margin-top: 5px;">
                    {{ $bizAddress }}<br>
                    Phone: {{ $bizPhone }} | Email: {{ $bizEmail }}
                    @if (!empty($bizGst))
                        <br><strong>GSTIN:</strong> {{ $bizGst }}
                    @endif
                </div>
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <h1 class="invoice-title">Tax Invoice</h1>
                <div style="margin-top: 6px; font-size: 12px; font-weight: bold; color: #0f172a;">
                    {{ $invoice->invoice_number }}
                </div>
                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                    Date: {{ $invoice->invoice_date?->format('d M, Y') ?? date('d M, Y') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="meta-container">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Billed To</td>
                <td class="meta-value" style="font-size: 13px;">{{ $invoice->customer_name }}</td>
                <td class="meta-label">Invoice Number</td>
                <td class="meta-value" style="color: #047857;">{{ $invoice->invoice_number }}</td>
            </tr>
            <tr>
                <td class="meta-label">Contact Number</td>
                <td class="meta-value">{{ $invoice->customer_contact ?? 'N/A' }}</td>
                <td class="meta-label">Invoice Date</td>
                <td class="meta-value">{{ $invoice->invoice_date?->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td class="meta-label">Email Address</td>
                <td class="meta-value">{{ $invoice->customer_email ?? 'N/A' }}</td>
                <td class="meta-label">Customer GSTIN</td>
                <td class="meta-value">{{ $invoice->customer_gst_number ?? 'Unregistered' }}</td>
            </tr>
            @if (!empty($invoice->customer_address))
                <tr>
                    <td class="meta-label">Billing Address</td>
                    <td colspan="3" class="meta-value">{{ $invoice->customer_address }}</td>
                </tr>
            @endif
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 8%; text-align: center;">#</th>
                <th style="width: 48%;">Service / Item Description</th>
                <th style="width: 14%; text-align: center;">Qty / Distance</th>
                <th style="width: 15%; text-align: right;">Rate (₹)</th>
                <th style="width: 15%; text-align: right;">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoice->items as $index => $item)
                <tr>
                    <td style="text-align: center; color: #64748b;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->description }}</strong>
                        @if ($item->vehicle_name)
                            <div style="font-size: 10px; color: #64748b; margin-top: 2px;">
                                Vehicle: {{ $item->vehicle_name }} ({{ $item->vehicle_type ?? 'Sedan' }}, {{ $item->vehicle_capacity ?? '4 Seats' }})
                            </div>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        {{ $item->item_type === 'taxi' ? ($item->total_distance_km ? number_format((float) $item->total_distance_km) . ' km' : '1') : number_format((float) $item->quantity) }}
                    </td>
                    <td style="text-align: right;">
                        ₹{{ number_format((float) $item->unit_price, 2) }}
                    </td>
                    <td style="text-align: right; font-weight: 600;">
                        ₹{{ number_format((float) $item->amount, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #64748b;">
                        No items attached.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary-table">
        <tr>
            <td style="color: #64748b;">Subtotal:</td>
            <td style="text-align: right; font-weight: 600;">₹{{ number_format((float) $invoice->subtotal, 2) }}</td>
        </tr>
        @if ((float) $invoice->tax_rate > 0)
            <tr>
                <td style="color: #64748b;">GST ({{ (float) $invoice->tax_rate }}%):</td>
                <td style="text-align: right; font-weight: 600;">₹{{ number_format((float) $invoice->tax_amount, 2) }}</td>
            </tr>
        @endif
        <tr class="grand-total">
            <td>Grand Total:</td>
            <td style="text-align: right;">₹{{ number_format((float) $invoice->total_amount, 2) }}</td>
        </tr>
    </table>

    @if (!empty($invoice->notes))
        <div class="notes-box">
            <strong>Notes / Terms:</strong><br>
            {{ $invoice->notes }}
        </div>
    @endif

    <div class="footer">
        Thank you for traveling with {{ $bizName }}! For inquiries or assistance, reach us at {{ $bizPhone }}.<br>
        This is a computer generated invoice and requires no physical signature.
    </div>

</body>
</html>
