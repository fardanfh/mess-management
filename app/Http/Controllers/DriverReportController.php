<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Checkout;
use App\Models\Fine;
use App\Exports\DriverReportExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DriverReportController extends Controller
{
    /**
     * Show driver checkout report with date filter
     */
    public function index(Request $request)
    {
        // Check if filter is applied
        $hasFilter = $request->has('start_date') && $request->has('end_date');

        // Get date range from request
        $startDate = $request->input('start_date', null);
        $endDate = $request->input('end_date', null);

        $reportData = collect();

        // Only fetch data if filter is applied
        if ($hasFilter) {
            // Convert to Carbon instances
            $startDateTime = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $endDateTime = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

            // Get all active drivers
            $drivers = Driver::where('status', 'active')
                ->with([
                    'checkins' => function ($query) use ($startDateTime, $endDateTime) {
                        $query->whereBetween('check_in_time', [$startDateTime, $endDateTime])
                            ->with(['checkout', 'locker', 'fines']);
                    }
                ])
                ->get();

            // Process report data
            $reportData = $drivers->map(function ($driver) use ($startDateTime, $endDateTime) {
                // Count room usages (number of checkouts in the period)
                $roomUsages = Checkout::where('driver_id', $driver->id)
                    ->whereBetween('checkout_time', [$startDateTime, $endDateTime])
                    ->count();

                // Count locker usages (number of checkouts with locker in the period)
                $lockerUsages = Checkout::where('driver_id', $driver->id)
                    ->whereBetween('checkout_time', [$startDateTime, $endDateTime])
                    ->whereNotNull('locker_id')
                    ->count();

                // Calculate total cost (room usages × cost per day)
                $totalNominal = $roomUsages * Checkout::COST_PER_DAY;

                // Count violations/fines in period (fines from check-ins in the period)
                $violationCount = Fine::whereIn('checkin_id', $driver->checkins()
                    ->whereBetween('check_in_time', [$startDateTime, $endDateTime])
                    ->pluck('id')
                )->count();

                return [
                    'driver_id' => $driver->id,
                    'name' => $driver->name,
                    'id_card' => $driver->id_card,
                    'room_usages' => $roomUsages,
                    'locker_usages' => $lockerUsages,
                    'total_nominal' => $totalNominal,
                    'violation_count' => $violationCount,
                ];
            })->filter(function ($item) {
                // Only show drivers with checkouts in the period
                return $item['room_usages'] > 0;
            })->values();
        }

        return view('reports.driver-report', compact(
            'reportData',
            'startDate',
            'endDate',
            'hasFilter'
        ));
    }

    /**
     * Export driver report as XLSX file
     */
    public function exportExcel(Request $request)
    {
        // Check if filter is applied
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (!$startDate || !$endDate) {
            return redirect()->route('driver-report.index')->with('error', 'Please select date range to export');
        }

        // Convert to Carbon instances
        $startDateTime = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $endDateTime = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        // Get all active drivers
        $drivers = Driver::where('status', 'active')
            ->with([
                'checkins' => function ($query) use ($startDateTime, $endDateTime) {
                    $query->whereBetween('check_in_time', [$startDateTime, $endDateTime])
                        ->with(['checkout', 'locker', 'fines']);
                }
            ])
            ->get();

        // Process report data
        $reportData = $drivers->map(function ($driver) use ($startDateTime, $endDateTime) {
            // Count room usages (number of checkouts in the period)
            $roomUsages = Checkout::where('driver_id', $driver->id)
                ->whereBetween('checkout_time', [$startDateTime, $endDateTime])
                ->count();

            // Count locker usages (number of checkouts with locker in the period)
            $lockerUsages = Checkout::where('driver_id', $driver->id)
                ->whereBetween('checkout_time', [$startDateTime, $endDateTime])
                ->whereNotNull('locker_id')
                ->count();

            // Calculate total cost (room usages × cost per day)
            $totalNominal = $roomUsages * Checkout::COST_PER_DAY;

            // Count violations/fines in period (fines from check-ins in the period)
            $violationCount = Fine::whereIn('checkin_id', $driver->checkins()
                ->whereBetween('check_in_time', [$startDateTime, $endDateTime])
                ->pluck('id')
            )->count();

            return [
                'name' => $driver->name,
                'id_card' => $driver->id_card,
                'room_usages' => $roomUsages,
                'locker_usages' => $lockerUsages,
                'total_nominal' => $totalNominal,
                'violation_count' => $violationCount,
            ];
        })->filter(function ($item) {
            // Only show drivers with checkouts in the period
            return $item['room_usages'] > 0;
        })->values();

        // Use export class with Excel facade
        $filename = "Driver_Report_" . $startDate . "_to_" . $endDate . ".xlsx";
        return Excel::download(new DriverReportExport($reportData, $startDate, $endDate), $filename);
    }
}
