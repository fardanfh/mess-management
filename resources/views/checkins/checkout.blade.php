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

            @if ($checkin->locker)
                <div class="card mt-4">
                    <div class="card-header card-header-primary">
                        <i class="fas fa-cube"></i> Locker
                    </div>
                    <div class="card-body">
                        <p>
                            <strong>Locker Number:</strong><br>
                            <span style="font-size: 20px">{{ $checkin->locker->locker_number }}</span>
                        </p>
                    </div>
                </div>
            @endif
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

                        <!-- Duration Display -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div id="durationSummary" class="alert alert-info d-none">
                                    <strong>Nights Stayed:</strong>
                                    <h5 id="nightsStayed">-</h5>
                                </div>
                            </div>
                        </div>

                        <!-- Fines Summary -->
                        @if ($checkin->fines->count() > 0)
                            <div class="card mb-4">
                                <div class="card-header card-header-warning">
                                    <i class="fas fa-ban"></i> Total Fines (Denda)
                                </div>
                                <div class="card-body">
                                    @foreach ($checkin->fines as $fine)
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>{{ $fine->getTypeLabel() }}</span>
                                            <span class="fw-bold">Rp {{ number_format($fine->amount, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <h5><strong>Total Fines:</strong></h5>
                                        <h5 class="text-danger"><strong>Rp {{ number_format($checkin->getTotalFines(), 0, ',', '.') }}</strong></h5>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-success mb-4">
                                <i class="fas fa-check-circle"></i> <strong>Tidak ada denda</strong> - Total Bayar: <strong>Rp 0</strong>
                            </div>
                        @endif

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
    // Handle form submission - show SweetAlert confirm then convert datetime-local format to Y-m-d H:i and submit
    (function() {
        const form = document.getElementById('checkoutForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // ensure Swal2 is available
            function ensureSwal(callback) {
                if (window.Swal) return callback();
                const s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                s.onload = callback;
                document.head.appendChild(s);
            }

            ensureSwal(function() {
                const driverName = `{{ addslashes($checkin->driver->name) }}`;
                const roomNumber = `{{ addslashes($checkin->room->room_number) }}`;

                Swal.fire({
                    title: 'Confirm Checkout',
                    html: `<p>Driver: <strong>${driverName}</strong></p><p>Room: <strong>${roomNumber}</strong></p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Process Checkout',
                    cancelButtonText: 'Cancel'
                }).then(result => {
                    if (result.isConfirmed) {
                        // Convert datetime-local to server format before submitting
                        const checkOutTimeInput = document.getElementById('checkout_time').value;
                        if (checkOutTimeInput) {
                            const formatted = checkOutTimeInput.replace('T', ' ');
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'checkout_time';
                            hiddenInput.value = formatted;

                            // Remove original datetime-local input from form data
                            document.getElementById('checkout_time').name = 'checkout_time_display';

                            // Add formatted hidden input
                            form.appendChild(hiddenInput);
                        }

                        // Submit the form programmatically
                        form.submit();
                    }
                });
            });
        });
    })();

    document.getElementById('checkout_time').addEventListener('change', function() {
        const checkInTime = new Date('{{ $checkin->check_in_time->format('Y-m-d H:i') }}');
        const checkOutTime = new Date(this.value.replace('T', ' '));

        if (isNaN(checkOutTime)) return;

        const diffMs = checkOutTime - checkInTime;
        const diffHours = diffMs / (1000 * 60 * 60);
        const nights = Math.ceil(diffHours / 24);

        document.getElementById('nightsStayed').textContent = nights + ' night(s)';
        document.getElementById('durationSummary').classList.remove('d-none');
    });

    // Trigger calculation on page load
    document.getElementById('checkout_time').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection
