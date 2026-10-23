<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tour Proposal - {{ $clientName }}</title>
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

        .section-title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #b91c1c;
            border-bottom: 2px solid #b91c1c;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 15px;
        }

        .pricing-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .pricing-table th {
            background-color: #b91c1c;
            color: #ffffff;
            font-weight: bold;
            font-size: 11px;
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
        }

        .feature-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .feature-table td {
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
        }

        .feature-header-inc {
            color: #15803d;
            font-weight: bold;
            font-size: 12px;
            border-bottom: 1px solid #bbf7d0;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .feature-header-exc {
            color: #b91c1c;
            font-weight: bold;
            font-size: 12px;
            border-bottom: 1px solid #fecaca;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .feature-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .feature-list li {
            padding: 3px 0;
            font-size: 11px;
            color: #475569;
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
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .day-header {
            background-color: #f1f5f9;
            border-left: 3px solid #b91c1c;
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
            background-color: #fffbeb;
            border: 1px dashed #fcd34d;
            border-radius: 6px;
            padding: 5px 8px;
            margin-top: 6px;
            font-size: 10px;
            color: #92400e;
        }

        .pdf-footer {
            margin-top: 35px;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            text-align: center;
            font-size: 9px;
            color: #64748b;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 50%;">
                @if ($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo" style="max-height: 70px; max-width: 180px;">
                @else
                    <span style="font-size: 18px; font-weight: bold; color: #0f172a; letter-spacing: 0.5px;">
                        {{ $settings->business_name ?? 'Kutch Tours & Taxi' }}
                    </span>
                @endif
            </td>
            <td style="width: 50%; text-align: right; font-size: 10px; color: #64748b; line-height: 1.4;">
                <strong style="color: #0f172a; font-size: 11px;">{{ $settings->business_name ?? 'Kutch Tours & Taxi' }}</strong><br>
                {{ $settings->address ?? 'Bhuj, Kutch, Gujarat, India' }}<br>
                Phone: {{ $settings->contact_number ?? '+91 99042 22293' }}<br>
                Email: {{ $settings->contact_email ?? 'info@kutchtoursandtaxi.com' }}<br>
                @if (!empty($settings->gst_number))
                    GSTIN: {{ $settings->gst_number }}
                @endif
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="font-size: 16px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; margin: 0; color: #0f172a;">
            Customized Tour Proposal
        </h2>
    </div>

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
                <td class="meta-value" style="color: #b91c1c;">{{ $title }}</td>
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
        Thank you for choosing {{ $settings->business_name ?? 'Kutch Tours & Taxi' }}. We look forward to hosting you!<br>
        Generated on {{ date('d-m-Y H:i A') }} • This proposal is subject to vehicle & hotel room availability. Prices may vary during peak Rann Utsav dates.
    </div>

</body>
</html>
