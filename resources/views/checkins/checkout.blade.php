@extends('layouts.admin')

@section('title', 'Process Checkout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header card-header-primary">
                    <i class="fas fa-user"></i> Driver Information
                </div>
                <div class="card-body">
                    <p>
                        <strong>Name:</strong><br>
                        {{ $checkin->driver->name }}
                    </p>
                    <p>
                        <strong>ID Card:</strong><br>
                        {{ $checkin->driver->id_card }}
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-door-closed"></i> Room
                </div>
                <div class="card-body">
                    <p>
                        <strong>Room Number:</strong><br>
                        <span style="font-size: 20px">{{ $checkin->room->room_number }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-sign-out-alt"></i> Checkout Details
                </div>
                <div class="card-body">
                    <form action="{{ route('checkouts.store') }}" method="POST" id="checkoutForm">
                        @csrf

                        <input type="hidden" name="checkin_id" value="{{ $checkin->id }}">

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="alert alert-warning">
                                    <strong>Check-in Time:</strong><br>
                                    {{ $checkin->check_in_time->format('d M Y H:i:s') }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="checkout_time" class="form-label">Check-out Time <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('checkout_time') is-invalid @enderror"
                                           id="checkout_time" name="checkout_time" value="{{ old('checkout_time', now()->format('Y-m-d\TH:i')) }}" required>
                                    @error('checkout_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Cost Calculation Preview -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div id="costSummary" class="alert alert-warning d-none">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Nights Stayed:</strong>
                                            <h5 id="nightsStayed">-</h5>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Cost per Night:</strong>
                                            <h5>Rp 2.000</h5>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Total Cost:</strong>
                                            <h5 id="totalCost">-</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sign-out-alt"></i> Confirm Checkout
                            </button>
                            <a href="{{ route('checkins.show', $checkin) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    const costPerDay = 2000;

    // Handle form submission - convert datetime-local format to Y-m-d H:i
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const checkOutTimeInput = document.getElementById('checkout_time').value;

        if (checkOutTimeInput) {
            // Convert from Y-m-d\TH:i to Y-m-d H:i
            // Input format: 2025-12-07T14:30 → Output format: 2025-12-07 14:30
            const formatted = checkOutTimeInput.replace('T', ' ');

            // Create hidden input with correct format
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'checkout_time';
            hiddenInput.value = formatted;

            // Remove original datetime-local input from form data
            document.getElementById('checkout_time').name = 'checkout_time_display';

            // Add formatted hidden input
            this.appendChild(hiddenInput);
        }
    });

    document.getElementById('checkout_time').addEventListener('change', function() {
        const checkInTime = new Date('{{ $checkin->check_in_time->format('Y-m-d H:i') }}');
        const checkOutTime = new Date(this.value.replace('T', ' '));

        if (isNaN(checkOutTime)) return;

        const diffMs = checkOutTime - checkInTime;
        const diffHours = diffMs / (1000 * 60 * 60);
        const nights = Math.ceil(diffHours / 24);
        const totalCost = nights * costPerDay;

        document.getElementById('nightsStayed').textContent = nights + ' night(s)';
        document.getElementById('totalCost').textContent = 'Rp ' + totalCost.toLocaleString('id-ID');
        document.getElementById('costSummary').classList.remove('d-none');
    });

    // Trigger calculation on page load
    document.getElementById('checkout_time').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection
