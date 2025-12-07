<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #FEC905;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .summary-section {
            background: #f9f9f9;
            border-left: 4px solid #FEC905;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .summary-section h3 {
            color: #333;
            font-size: 16px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .summary-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: #666;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .summary-value {
            color: #333;
            font-size: 18px;
            font-weight: 600;
        }

        .section-title {
            color: #333;
            font-size: 18px;
            font-weight: 600;
            margin: 30px 0 15px 0;
            border-bottom: 2px solid #FEC905;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        thead {
            background: #FEC905;
            color: #000;
        }

        th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        tbody tr:nth-child(odd) {
            background: #f9f9f9;
        }

        tbody tr:hover {
            background: #f0f0f0;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #999;
            font-size: 11px;
        }

        @media print {
            body {
                padding: 0;
            }
            .container {
                padding: 0;
                max-width: 100%;
            }
            a {
                text-decoration: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Mess Management System</h1>
            <h2 style="font-size: 20px; color: #666; margin-bottom: 5px;">Dashboard Report</h2>
            <p><strong>Period:</strong> {{ date('d M Y', strtotime($from)) }} to {{ date('d M Y', strtotime($to)) }}</p>
        </div>

        <!-- Summary Statistics -->
        <div class="summary-section">
            <h3>Summary Statistics</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total Check-ins</div>
                    <div class="summary-value">{{ count($checkins) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Total Check-outs</div>
                    <div class="summary-value">{{ count($checkouts) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Total Nights Stayed</div>
                    <div class="summary-value">{{ $totalNights }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Average Nights/Checkout</div>
                    <div class="summary-value">
                        @if (count($checkouts) > 0)
                            {{ round($totalNights / count($checkouts), 1) }}
                        @else
                            0
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="summary-section">
            <h3>Financial Summary</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total Revenue</div>
                    <div class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Paid Amount</div>
                    <div class="summary-value">Rp {{ number_format($paidAmount, 0, ',', '.') }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Unpaid Amount</div>
                    <div class="summary-value">Rp {{ number_format($unpaidAmount, 0, ',', '.') }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Collection Rate</div>
                    <div class="summary-value">
                        @if ($totalRevenue > 0)
                            {{ round(($paidAmount / $totalRevenue) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Details Table -->
        <div class="section-title">Check-out Details</div>
        <table>
            <thead>
                <tr>
                    <th>Driver Name</th>
                    <th>ID Card</th>
                    <th>Room</th>
                    <th>Check-out Time</th>
                    <th>Nights</th>
                    <th>Total Cost</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @if (count($checkouts) > 0)
                    @foreach ($checkouts as $checkout)
                        <tr>
                            <td>{{ $checkout->driver->name }}</td>
                            <td>{{ $checkout->driver->id_card }}</td>
                            <td>{{ $checkout->room->room_number }}</td>
                            <td>{{ $checkout->checkout_time->format('d M Y H:i') }}</td>
                            <td>{{ $checkout->nights_stayed }}</td>
                            <td>Rp {{ number_format($checkout->total_cost, 0, ',', '.') }}</td>
                            <td>
                                <span style="text-transform: capitalize;">{{ $checkout->payment_status }}</span>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="empty-state">
                            <strong>No checkout records found for this period</strong>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Generated on {{ date('d M Y H:i:s') }}</p>
            <p style="margin-top: 5px;">To save as PDF: Use your browser's Print function (Ctrl+P or Cmd+P) and select "Save as PDF"</p>
            <p style="margin-top: 10px; color: #999;">Powered by Mess Management System</p>
        </div>
    </div>
</body>
</html>
