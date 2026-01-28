<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\Checkin;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class FineController extends Controller
{
    /**
     * Store a newly created fine in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'checkin_id' => 'required|exists:checkins,id',
            'fine_types' => 'required|array|min:1',
            'fine_types.*' => 'required|in:smoking,eating_drinking,drying_clothes,littering',
            'description' => 'nullable|string|max:500',
        ]);

        // Get the checkin
        $checkin = Checkin::findOrFail($validated['checkin_id']);

        // Check if checkin is still active (not checked out)
        if ($checkin->status !== 'checked_in') {
            return back()->withErrors(['error' => 'Hanya dapat menambahkan denda untuk checkin yang masih aktif.']);
        }

        // Get the amount based on fine type
        $fineAmounts = [
            'smoking' => 50000,
            'eating_drinking' => 25000,
            'drying_clothes' => 25000,
            'littering' => 25000,
        ];

        $totalAmount = 0;
        $createdFines = [];

        // Create fines for each selected type
        foreach ($validated['fine_types'] as $fineType) {
            $amount = $fineAmounts[$fineType] ?? 0;

            // Create the fine
            $fine = Fine::create([
                'checkin_id' => $validated['checkin_id'],
                'fine_type' => $fineType,
                'amount' => $amount,
                'description' => $validated['description'] ?? null,
                'added_by' => auth()->id(),
            ]);

            $createdFines[] = $fine->getTypeLabel();
            $totalAmount += $amount;

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'model_type' => 'Fine',
                'model_id' => $fine->id,
                'description' => 'Menambahkan denda: ' . $fine->getTypeLabel(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }

        $finesList = implode(', ', $createdFines);

        return redirect()->route('checkins.show', $checkin->id)
            ->with('success', 'Denda berhasil ditambahkan: ' . $finesList . ' - Total Rp ' . number_format($totalAmount, 0, ',', '.'));
    }

    /**
     * Remove the specified fine from storage.
     */
    public function destroy(Fine $fine)
    {
        $checkinId = $fine->checkin_id;
        $fineType = $fine->getTypeLabel();
        $amount = $fine->amount;

        // Log activity before deletion
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'model_type' => 'Fine',
            'model_id' => $fine->id,
            'description' => 'Menghapus denda: ' . $fineType,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Soft delete
        $fine->delete();

        return redirect()->route('checkins.show', $checkinId)
            ->with('success', "Denda '{$fineType}' berhasil dihapus - Rp " . number_format($amount, 0, ',', '.'));
    }

    /**
     * Restore a soft-deleted fine.
     */
    public function restore(Fine $fine)
    {
        $checkinId = $fine->checkin_id;

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'restore',
            'model_type' => 'Fine',
            'model_id' => $fine->id,
            'description' => 'Memulihkan denda: ' . $fine->getTypeLabel(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $fine->restore();

        return redirect()->route('checkins.show', $checkinId)
            ->with('success', 'Denda berhasil dipulihkan.');
    }
}
