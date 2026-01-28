<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Locker;
use App\Models\Checkin;
use App\Models\Checkout;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    /**
     * Display dashboard with key metrics
     */
    public function index()
    {
        // Room statistics
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'tersedia')->count();
        $occupiedRooms = Room::where('status', 'terisi')->count();
        $maintenanceRooms = Room::where('status', 'perbaikan')->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

        // Locker statistics
        $totalLockers = Locker::count();
        $availableLockers = Locker::where('status', 'tersedia')->count();
        $fullLockers = Locker::where('status', 'penuh')->count();
        $maintenanceLockers = Locker::where('status', 'perbaikan')->count();
        $lockerOccupancyRate = $totalLockers > 0 ? round((($totalLockers - $availableLockers) / $totalLockers) * 100, 1) : 0;

        // Check-in/out statistics
        $todayCheckins = Checkin::whereDate('check_in_time', today())->count();
        $todayCheckouts = Checkout::whereDate('checkout_time', today())->count();
        $currentlyCheckedIn = Checkin::where('status', 'checked_in')->count();

        $thisMonthCheckins = Checkin::whereMonth('check_in_time', now()->month)
            ->whereYear('check_in_time', now()->year)
            ->count();

        $thisMonthCheckouts = Checkout::whereMonth('checkout_time', now()->month)
            ->whereYear('checkout_time', now()->year)
            ->count();

        // Financial statistics
        $totalRevenue = Checkout::sum('total_cost') ?? 0;
        $paidAmount = Checkout::where('payment_status', 'paid')->sum('total_cost') ?? 0;
        $unpaidAmount = Checkout::where('payment_status', 'unpaid')->sum('total_cost') ?? 0;
        $todayRevenue = Checkout::whereDate('checkout_time', today())->sum('total_cost') ?? 0;

        // Driver statistics
        $totalDrivers = Driver::count();
        $activeDrivers = Driver::where('status', 'active')->count();

        // Today's transactions
        $todayTransactions = Checkin::with(['driver', 'room', 'locker'])
            ->whereDate('check_in_time', today())
            ->latest()
            ->limit(5)
            ->get();

        $todayCheckouts = Checkout::with(['driver', 'room', 'locker'])
            ->whereDate('checkout_time', today())
            ->latest()
            ->limit(5)
            ->get();

        // Recent activities
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Monthly check-in/out data for chart
        $monthlyData = $this->getMonthlyCheckinCheckoutData();

        return view('dashboard.index', compact(
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'maintenanceRooms',
            'occupancyRate',
            'totalLockers',
            'availableLockers',
            'fullLockers',
            'maintenanceLockers',
            'lockerOccupancyRate',
            'todayCheckins',
            'todayCheckouts',
            'currentlyCheckedIn',
            'thisMonthCheckins',
            'thisMonthCheckouts',
            'totalRevenue',
            'paidAmount',
            'unpaidAmount',
            'todayRevenue',
            'totalDrivers',
            'activeDrivers',
            'todayTransactions',
            'recentActivities',
            'monthlyData'
        ));
    }

    /**
     * Get monthly checkin/checkout data for chart
     */
    private function getMonthlyCheckinCheckoutData()
    {
        $months = [];
        $checkins = [];
        $checkouts = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            $checkinCount = Checkin::whereMonth('check_in_time', $date->month)
                ->whereYear('check_in_time', $date->year)
                ->count();
            $checkins[] = $checkinCount;

            $checkoutCount = Checkout::whereMonth('checkout_time', $date->month)
                ->whereYear('checkout_time', $date->year)
                ->count();
            $checkouts[] = $checkoutCount;
        }

        return [
            'months' => $months,
            'checkins' => $checkins,
            'checkouts' => $checkouts,
        ];
    }

    /**
     * Get report for a date range
     */
    public function report(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfMonth()->format('Y-m-d'));

        $checkins = Checkin::whereBetween('check_in_time', [$from, $to])
            ->with(['driver', 'room', 'user'])
            ->get();

        $checkouts = Checkout::whereBetween('checkout_time', [$from, $to])
            ->with(['driver', 'room'])
            ->get();

        $totalRevenue = $checkouts->sum('total_cost');
        $paidAmount = $checkouts->where('payment_status', 'paid')->sum('total_cost');
        $unpaidAmount = $checkouts->where('payment_status', 'unpaid')->sum('total_cost');
        $totalNights = $checkouts->sum('nights_stayed');

        return view('dashboard.report', compact(
            'checkins',
            'checkouts',
            'totalRevenue',
            'paidAmount',
            'unpaidAmount',
            'totalNights',
            'from',
            'to'
        ));
    }

    /**
     * Export report as PDF
     */
    public function exportPDF(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfMonth()->format('Y-m-d'));

        $checkins = Checkin::whereBetween('check_in_time', [$from, $to])
            ->with(['driver', 'room'])
            ->get();

        $checkouts = Checkout::whereBetween('checkout_time', [$from, $to])
            ->with(['driver', 'room'])
            ->get();

        $totalNights = $checkouts->sum('nights_stayed');
        $totalRevenue = $checkouts->sum('total_cost');
        $paidAmount = $checkouts->where('payment_status', 'paid')->sum('total_cost');
        $unpaidAmount = $checkouts->where('payment_status', 'unpaid')->sum('total_cost');

        $html = $this->generatePdfHtml(
            $from,
            $to,
            $checkins,
            $checkouts,
            $totalNights,
            $totalRevenue,
            $paidAmount,
            $unpaidAmount
        );

        $filename = "report_" . date('Y-m-d_H-i-s') . ".pdf";

        // Generate PDF using DomPDF
        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 0.5)
            ->setOption('margin-right', 0.5)
            ->setOption('margin-bottom', 0.5)
            ->setOption('margin-left', 0.5);

        return $pdf->download($filename);
    }

    /**
     * Generate PDF HTML content
     */
    private function generatePdfHtml($from, $to, $checkins, $checkouts, $totalNights, $totalRevenue, $paidAmount, $unpaidAmount)
    {
        $html = '<!DOCTYPE html>
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
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
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

        .header h2 {
            font-size: 20px;
            color: #666;
            margin-bottom: 5px;
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
            <h2>Dashboard Report</h2>
            <p><strong>Period:</strong> ' . date('d M Y', strtotime($from)) . ' to ' . date('d M Y', strtotime($to)) . '</p>
        </div>

        <!-- Summary Statistics -->
        <div class="summary-section">
            <h3>Summary Statistics</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total Check-ins</div>
                    <div class="summary-value">' . count($checkins) . '</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Total Check-outs</div>
                    <div class="summary-value">' . count($checkouts) . '</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Total Nights Stayed</div>
                    <div class="summary-value">' . $totalNights . '</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Average Nights/Checkout</div>
                    <div class="summary-value">' . (count($checkouts) > 0 ? round($totalNights / count($checkouts), 1) : 0) . '</div>
                </div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="summary-section">
            <h3>Financial Summary</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total Revenue</div>
                    <div class="summary-value">Rp ' . number_format($totalRevenue, 0, ',', '.') . '</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Paid Amount</div>
                    <div class="summary-value">Rp ' . number_format($paidAmount, 0, ',', '.') . '</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Unpaid Amount</div>
                    <div class="summary-value">Rp ' . number_format($unpaidAmount, 0, ',', '.') . '</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Collection Rate</div>
                    <div class="summary-value">' . (($totalRevenue > 0) ? round(($paidAmount / $totalRevenue) * 100, 1) : 0) . '%</div>
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
            <tbody>';

        if (count($checkouts) > 0) {
            foreach ($checkouts as $checkout) {
                $html .= '
                <tr>
                    <td>' . htmlspecialchars($checkout->driver->name) . '</td>
                    <td>' . htmlspecialchars($checkout->driver->id_card) . '</td>
                    <td>' . htmlspecialchars($checkout->room->room_number) . '</td>
                    <td>' . $checkout->checkout_time->format('d M Y H:i') . '</td>
                    <td>' . $checkout->nights_stayed . '</td>
                    <td>Rp ' . number_format($checkout->total_cost, 0, ',', '.') . '</td>
                    <td>
                        <span style="text-transform: capitalize;">' . ucfirst($checkout->payment_status) . '</span>
                    </td>
                </tr>';
            }
        } else {
            $html .= '
                <tr>
                    <td colspan="7" class="empty-state">No checkout records found for this period</td>
                </tr>';
        }

        $html .= '
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Generated on ' . date('d M Y H:i:s') . '</p>
            <p style="margin-top: 5px;">Powered by Mess Management System</p>
        </div>
    </div>
</body>
</html>';

        return $html;
    }

    /**
     * Export report as Excel
     */
    public function exportExcel(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfMonth()->format('Y-m-d'));

        $checkouts = Checkout::whereBetween('checkout_time', [$from, $to])
            ->with(['driver', 'room'])
            ->get();

        $filename = "checkout_report_" . date('Y-m-d_H-i-s') . ".csv";

        $csv = "";
        $csv .= "\xEF\xBB\xBF"; // BOM for UTF-8

        // Add headers
        $csv .= implode(",", [
            'Driver Name',
            'ID Card',
            'Room Number',
            'Check-out Time',
            'Nights Stayed',
            'Total Cost (Rp)',
            'Payment Status'
        ]) . "\n";

        // Add data
        foreach ($checkouts as $checkout) {
            $row = [
                $checkout->driver->name,
                $checkout->driver->id_card,
                $checkout->room->room_number,
                $checkout->checkout_time->format('d M Y H:i'),
                $checkout->nights_stayed,
                number_format($checkout->total_cost, 0),
                ucfirst($checkout->payment_status)
            ];
            $csv .= '"' . implode('","', $row) . '"' . "\n";
        }

        // Add summary
        $csv .= "\n";
        $csv .= "SUMMARY\n";
        $csv .= "Total Checkouts," . count($checkouts) . "\n";
        $csv .= "Total Revenue,Rp " . number_format($checkouts->sum('total_cost'), 0) . "\n";
        $csv .= "Paid Amount,Rp " . number_format($checkouts->where('payment_status', 'paid')->sum('total_cost'), 0) . "\n";
        $csv .= "Unpaid Amount,Rp " . number_format($checkouts->where('payment_status', 'unpaid')->sum('total_cost'), 0) . "\n";

        return response($csv, 200)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
