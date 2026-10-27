<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tax Invoice - {{ $invoice->invoice_number }}</title>
    @php
        $biz = $invoice->business_details ?? [];
        $bizName = $biz['name'] ?? ($settings->business_name ?? 'Kutch Tours & Taxi');
        $bizPhone = $biz['contact'] ?? ($settings->contact_number ?? '+91 99042 22293');
        $bizEmail = $biz['email'] ?? ($settings->contact_email ?? 'info@kutchtoursandtaxi.com');
        $bizAddress = $biz['address'] ?? ($settings->address ?? 'Station Road, Bhuj, Kutch, Gujarat — 370001');
        $bizGst = $biz['gst_number'] ?? ($settings->gst_number ?? '');

        // Dynamic Primary Color from Admin Settings
        $primaryColor = !empty($settings?->primary_color)
            ? (str_starts_with($settings->primary_color, '#') ? $settings->primary_color : '#' . $settings->primary_color)
            : '#a8863e';

        $cleanHex = ltrim($primaryColor, '#');
        if (strlen($cleanHex) === 3) {
            $cleanHex = $cleanHex[0].$cleanHex[0].$cleanHex[1].$cleanHex[1].$cleanHex[2].$cleanHex[2];
        }
        $r = hexdec(substr($cleanHex, 0, 2));
        $g = hexdec(substr($cleanHex, 2, 2));
        $b = hexdec(substr($cleanHex, 4, 2));

        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        $primaryContrastText = $luminance > 0.65 ? '#0f172a' : '#ffffff';

        $tintR = round(255 - (255 - $r) * 0.10);
        $tintG = round(255 - (255 - $g) * 0.10);
        $tintB = round(255 - (255 - $b) * 0.10);
        $primaryLightTint = sprintf('#%02x%02x%02x', $tintR, $tintG, $tintB);

        $borderR = round(255 - (255 - $r) * 0.32);
        $borderG = round(255 - (255 - $g) * 0.32);
        $borderB = round(255 - (255 - $b) * 0.32);
        $primaryBorderTint = sprintf('#%02x%02x%02x', $borderR, $borderG, $borderB);
    @endphp
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
            margin-bottom: 20px;
        }

        .header-table td {
            vertical-align: top;
        }

        .doc-title {
            font-size: 18px;
            font-weight: bold;
            color: {{ $primaryColor }};
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin: 0;
        }

        .meta-container {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-top: 3px solid {{ $primaryColor }};
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 22px;
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

        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: {{ $primaryColor }};
            border-bottom: 2px solid {{ $primaryColor }};
            padding-bottom: 4px;
            margin-top: 22px;
            margin-bottom: 12px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }

        .items-table th {
            background-color: {{ $primaryColor }};
            color: {{ $primaryContrastText }};
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
            background-color: #ffffff;
        }

        .summary-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .summary-table td {
            padding: 6px 10px;
            font-size: 11px;
        }

        .grand-total {
            background-color: {{ $primaryLightTint }};
            font-weight: bold;
            font-size: 13px;
            color: #0f172a;
            border-top: 2px solid {{ $primaryColor }};
            border-bottom: 2px solid {{ $primaryColor }};
        }

        .notes-box {
            background-color: {{ $primaryLightTint }};
            border: 1px dashed {{ $primaryBorderTint }};
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 10px;
            color: #0f172a;
            margin-bottom: 25px;
        }

        .pdf-footer {
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            text-align: center;
            font-size: 9px;
            color: #64748b;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                @if ($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo" style="max-height: 60px; max-width: 175px;">
                @else
                    <span style="font-size: 18px; font-weight: bold; color: {{ $primaryColor }}; letter-spacing: 0.5px;">
                        {{ $bizName }}
                    </span>
                @endif
                <div style="font-size: 10px; color: #64748b; line-height: 1.45; margin-top: 6px;">
                    <strong style="color: #0f172a; font-size: 11px;">{{ $bizName }}</strong><br>
                    {{ $bizAddress }}<br>
                    Phone: {{ $bizPhone }} | Email: {{ $bizEmail }}
                    @if (!empty($bizGst))
                        <br><strong>GSTIN:</strong> {{ $bizGst }}
                    @endif
                </div>
            </td>
            <td style="width: 45%; text-align: right; vertical-align: top;">
                <h1 class="doc-title">Tax Invoice</h1>
                <div style="margin-top: 5px; font-size: 13px; font-weight: bold; color: {{ $primaryColor }};">
                    {{ $invoice->invoice_number }}
                </div>
                <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
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
                <td class="meta-value" style="color: {{ $primaryColor }};">{{ $invoice->invoice_number }}</td>
            </tr>
            <tr>
                <td class="meta-label">Contact Number</td>
                <td class="meta-value">{{ $invoice->customer_contact ?? 'N/A' }}</td>
                <td class="meta-label">Invoice Date</td>
                <td class="meta-value">{{ $invoice->invoice_date?->format('d-m-Y') ?? date('d-m-Y') }}</td>
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
                        <strong style="color: #0f172a;">{{ $item->description }}</strong>
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
                    <td style="text-align: right; font-weight: 600; color: #0f172a;">
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
            <td style="text-align: right; font-weight: 600; color: #0f172a;">₹{{ number_format((float) $invoice->subtotal, 2) }}</td>
        </tr>
        @if ((float) $invoice->tax_rate > 0)
            <tr>
                <td style="color: #64748b;">GST ({{ (float) $invoice->tax_rate }}%):</td>
                <td style="text-align: right; font-weight: 600; color: #0f172a;">₹{{ number_format((float) $invoice->tax_amount, 2) }}</td>
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

    <div class="pdf-footer">
        Thank you for choosing {{ $bizName }}! We appreciate your business.<br>
        For inquiries or assistance, reach us at {{ $bizPhone }} | {{ $bizEmail }}<br>
        This is a computer generated invoice and requires no physical signature.
    </div>

</body>
</html>
