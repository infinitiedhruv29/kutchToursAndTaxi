<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tour Proposal - {{ $clientName }}</title>
    @php
        $bizName = $settings->business_name ?? 'Kutch Tours & Taxi';
        $bizPhone = $settings->contact_number ?? '+91 99042 22293';
        $bizEmail = $settings->contact_email ?? 'info@kutchtoursandtaxi.com';
        $bizAddress = $settings->address ?? 'Station Road, Bhuj, Kutch, Gujarat — 370001';
        $bizGst = $settings->gst_number ?? '';

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

        .pricing-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }

        .pricing-table th {
            background-color: {{ $primaryColor }};
            color: {{ $primaryContrastText }};
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 9px;
            text-align: center;
        }

        .pricing-table td {
            padding: 10px;
            border: 1px solid #e2e8f0;
            text-align: center;
            font-weight: bold;
            color: #0f172a;
            font-size: 13px;
            background-color: #ffffff;
        }

        .feature-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }

        .feature-table td {
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
        }

        .feature-header-inc {
            color: #15803d;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #bbf7d0;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }

        .feature-header-exc {
            color: #b91c1c;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #fecaca;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }

        .feature-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .feature-list li {
            padding: 3px 0;
            font-size: 11px;
            color: #334155;
        }

        .icon-check {
            color: #15803d;
            font-weight: bold;
            margin-right: 5px;
        }

        .icon-cross {
            color: #b91c1c;
            font-weight: bold;
            margin-right: 5px;
        }

        .day-row {
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        .day-header {
            background-color: #f8fafc;
            border-left: 3px solid {{ $primaryColor }};
            padding: 7px 12px;
            font-weight: bold;
            color: #0f172a;
            font-size: 12px;
        }

        .day-body {
            padding: 8px 12px;
            border-left: 1px solid #e2e8f0;
            margin-left: 2px;
        }

        .day-spots {
            margin: 6px 0;
            padding-left: 18px;
            color: #334155;
            font-size: 11px;
        }

        .day-note {
            background-color: {{ $primaryLightTint }};
            border: 1px dashed {{ $primaryBorderTint }};
            border-radius: 6px;
            padding: 5px 8px;
            margin-top: 6px;
            font-size: 10px;
            color: #0f172a;
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
                <h1 class="doc-title">Tour Proposal</h1>
                <div style="margin-top: 5px; font-size: 13px; font-weight: bold; color: {{ $primaryColor }};">
                    {{ $title }}
                </div>
                <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                    Date: {{ date('d M, Y') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="meta-container">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Client Name</td>
                <td class="meta-value">{{ $clientName }}</td>
                <td class="meta-label">Proposal Date</td>
                <td class="meta-value">{{ date('d M, Y') }}</td>
            </tr>
            <tr>
                <td class="meta-label">Contact Number</td>
                <td class="meta-value">{{ $clientPhone ?? 'N/A' }}</td>
                <td class="meta-label">Tour Title</td>
                <td class="meta-value" style="color: {{ $primaryColor }};">{{ $title }}</td>
            </tr>
            <tr>
                <td class="meta-label">Duration</td>
                <td class="meta-value">{{ $duration ?? 'N/A' }}</td>
                <td class="meta-label">Highlight</td>
                <td class="meta-value">{{ $highlight ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    @if ($price2p || $price4p || $price6p)
        <div class="section-title">Estimated Package Cost (INR)</div>
        <table class="pricing-table">
            <thead>
                <tr>
                    @if ($price2p)
                        <th>2 Persons (Sedan)</th>
                    @endif
                    @if ($price4p)
                        <th>4 Persons (Ertiga)</th>
                    @endif
                    @if ($price6p)
                        <th>6 Persons (Innova)</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    @if ($price2p)
                        <td>₹{{ number_format((float) $price2p) }} /-</td>
                    @endif
                    @if ($price4p)
                        <td>₹{{ number_format((float) $price4p) }} /-</td>
                    @endif
                    @if ($price6p)
                        <td>₹{{ number_format((float) $price6p) }} /-</td>
                    @endif
                </tr>
            </tbody>
        </table>
    @endif

    @if (count($inclusions) > 0 || count($exclusions) > 0)
        <table class="feature-table">
            <tr>
                <td>
                    <div class="feature-header-inc">What's Included</div>
                    <ul class="feature-list">
                        @forelse ($inclusions as $inc)
                            @if (trim((string) $inc) !== '')
                                <li><span class="icon-check">✓</span> {{ trim((string) $inc) }}</li>
                            @endif
                        @empty
                            <li>Standard inclusions apply.</li>
                        @endforelse
                    </ul>
                </td>
                <td>
                    <div class="feature-header-exc">What's Excluded</div>
                    <ul class="feature-list">
                        @forelse ($exclusions as $exc)
                            @if (trim((string) $exc) !== '')
                                <li><span class="icon-cross">✗</span> {{ trim((string) $exc) }}</li>
                            @endif
                        @empty
                            <li>Standard exclusions apply.</li>
                        @endforelse
                    </ul>
                </td>
            </tr>
        </table>
    @endif

    @if (count($itineraries) > 0)
        <div class="section-title">Day-by-Day Tour Itinerary</div>

        @foreach ($itineraries as $iti)
            <div class="day-row">
                <div class="day-header">
                    Day {{ $iti['day_number'] }}: {{ $iti['title'] }}
                </div>
                <div class="day-body">
                    @if (!empty($iti['description']))
                        <div style="font-size: 11px; color: #334155; margin-bottom: 5px;">{{ $iti['description'] }}</div>
                    @endif

                    @if (!empty($iti['spots']) && is_array($iti['spots']) && count($iti['spots']) > 0)
                        <ul class="day-spots">
                            @foreach ($iti['spots'] as $spot)
                                <li>{{ $spot }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if (!empty($iti['note']))
                        <div class="day-note">
                            <strong>Note:</strong> {{ $iti['note'] }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

    <div class="pdf-footer">
        Thank you for choosing {{ $bizName }}! We look forward to hosting you in Kutch.<br>
        For bookings or inquiries, call {{ $bizPhone }} | {{ $bizEmail }}<br>
        Generated on {{ date('d-m-Y H:i A') }} • This proposal is subject to vehicle & hotel room availability.
    </div>

</body>
</html>
