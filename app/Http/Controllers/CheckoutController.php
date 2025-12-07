<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Checkout;
use App\Models\Invoice;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    const COST_PER_DAY = 2000;

    /**
     * Display a listing of checkouts.
     */
    public function index()
    {
        $checkouts = Checkout::with(['driver', 'room'])->paginate(15);
        return view('checkouts.index', compact('checkouts'));
    }

    /**
     * Store checkout and calculate cost.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'checkin_id' => 'required|exists:checkins,id',
            'checkout_time' => 'required|date_format:Y-m-d H:i',
        ]);

        $checkin = Checkin::with(['driver', 'room'])->findOrFail($validated['checkin_id']);

        // Validation: driver must be checked in
        if ($checkin->status === 'checked_out') {
            return redirect()->back()->with('error', 'Driver has already checked out');
        }

        $checkInTime = $checkin->check_in_time;
        $checkOutTime = Carbon::createFromFormat('Y-m-d H:i', $validated['checkout_time']);

        // Calculate nights stayed
        $nightsStayed = $this->calculateNights($checkInTime, $checkOutTime);
        $totalCost = $nightsStayed * self::COST_PER_DAY;

        // Create checkout record
        $checkout = Checkout::create([
            'checkin_id' => $checkin->id,
            'driver_id' => $checkin->driver_id,
            'room_id' => $checkin->room_id,
            'checkout_time' => $checkOutTime,
            'nights_stayed' => $nightsStayed,
            'total_cost' => $totalCost,
            'payment_status' => 'unpaid',
        ]);

        // Update checkin status
        $checkin->update([
            'check_out_time' => $checkOutTime,
            'status' => 'checked_out',
        ]);

        // Update room status to available
        $checkin->room->update(['status' => 'tersedia']);

        // Create invoice
        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'driver_id' => $checkin->driver_id,
            'checkout_id' => $checkout->id,
            'invoice_date' => now(),
            'total_amount' => $totalCost,
            'status' => 'issued',
            'due_date' => now()->addDays(7),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'checkout',
            'model_type' => 'Checkout',
            'model_id' => $checkout->id,
            'description' => "Driver {$checkin->driver->name} checked out from room {$checkin->room->room_number}. Nights: {$nightsStayed}, Cost: Rp " . number_format($totalCost),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('checkouts.index')->with('success', "Checkout successful. Invoice: {$invoice->invoice_number}");
    }

    /**
     * Display the specified checkout.
     */
    public function show(Checkout $checkout)
    {
        $checkout->load(['checkin.driver', 'checkin.room', 'invoice']);
        return view('checkouts.show', compact('checkout'));
    }

    /**
     * Mark payment as paid
     */
    public function markAsPaid(Request $request, Checkout $checkout)
    {
        $checkout->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);

        if ($checkout->invoice) {
            $checkout->invoice->update([
                'status' => 'paid',
                'paid_date' => now(),
            ]);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'payment',
            'model_type' => 'Checkout',
            'model_id' => $checkout->id,
            'description' => "Payment marked as paid for checkout #{$checkout->id}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Payment marked as paid');
    }

    /**
     * Calculate nights stayed based on check-in and check-out times
     */
    private function calculateNights($checkInTime, $checkOutTime)
    {
        $checkIn = Carbon::parse($checkInTime);
        $checkOut = Carbon::parse($checkOutTime);

        $diffHours = $checkOut->diffInHours($checkIn);
        $nights = ceil($diffHours / 24);

        return max($nights, 1); // Minimum 1 night
    }

    /**
     * Get checkout report
     */
    public function report(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfMonth()->format('Y-m-d'));

        $checkouts = Checkout::whereBetween('checkout_time', [$from, $to])
            ->with(['driver', 'room'])
            ->get();

        $totalRevenue = $checkouts->sum('total_cost');
        $paidAmount = $checkouts->where('payment_status', 'paid')->sum('total_cost');
        $unpaidAmount = $checkouts->where('payment_status', 'unpaid')->sum('total_cost');

        return view('checkouts.report', compact('checkouts', 'totalRevenue', 'paidAmount', 'unpaidAmount', 'from', 'to'));
    }
}
