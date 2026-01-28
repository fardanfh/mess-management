@extends('layouts.admin')

@section('title', 'Check-outs')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <a href="{{ route('checkouts.report') }}" class="btn btn-primary">
            <i class="fas fa-file-alt"></i> View Report
        </a>
    </div>

    <!-- Check-outs Table -->
    <div class="card">
        <div class="card-header card-header-primary">
            <span><i class="fas fa-list"></i> Check-out Records</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>Driver</th>
                            <th>Room</th>
                            <th>Check-out Time</th>
                            <th>Nights</th>
                            <th>Total Cost</th>
                            <th>Payment Status</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($checkouts as $checkout)
                            <tr>
                                <td>
                                    <a href="{{ route('drivers.show', $checkout->driver) }}" class="text-decoration-none text-black font-weight-bold">
                                        {{ $checkout->driver->name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('rooms.show', $checkout->room) }}" class="text-decoration-none text-warning">
                                        <strong>{{ $checkout->room->room_number }}</strong>
                                    </a>
                                </td>
                                <td><small>{{ $checkout->checkout_time->format('d M Y H:i') }}</small></td>
                                <td>
                                    <span class="badge bg-info" style="font-size: 0.85rem;">
                                        <i class="fas fa-moon"></i> {{ $checkout->nights_stayed }}
                                    </span>
                                </td>
                                <td><strong class="text-dark">Rp {{ number_format($checkout->total_cost, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if ($checkout->payment_status === 'paid')
                                        <span class="badge bg-success" style="font-size: 0.85rem;"><i class="fas fa-check-circle"></i> Paid</span>
                                    @else
                                        <span class="badge bg-warning" style="font-size: 0.85rem;"><i class="fas fa-clock"></i> Unpaid</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('checkouts.show', $checkout) }}" class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if ($checkout->payment_status === 'unpaid')
                                            <form action="{{ route('checkouts.mark-paid', $checkout) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-sm" title="Mark as Paid" onclick="return confirm('Mark as paid?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-inbox text-muted" style="font-size: 40px;"></i>
                                    <p class="text-muted mt-3 mb-0">No check-outs found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
