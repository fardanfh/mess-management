@extends('layouts.admin')

@section('title', 'Check-in Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 text-end">
            @if ($checkin->status === 'checked_in')
                <a href="{{ route('checkins.checkout-form', $checkin) }}" class="btn btn-success">
                    <i class="fas fa-sign-out-alt"></i> Process Checkout
                </a>
            @endif
            <a href="{{ route('checkins.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header card-header-primary">
                    <i class="fas fa-user"></i> Driver Information
                </div>
                <div class="card-body">
                    <p>
                        <strong>Name:</strong><br>
                        <a href="{{ route('drivers.show', $checkin->driver) }}">{{ $checkin->driver->name }}</a>
                    </p>
                    <p>
                        <strong>ID Card:</strong><br>
                        {{ $checkin->driver->id_card }}
                    </p>
                    <p>
                        <strong>Phone:</strong><br>
                        {{ $checkin->driver->phone ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-door-closed"></i> Room Information
                </div>
                <div class="card-body">
                    <p>
                        <strong>Room Number:</strong><br>
                        <a href="{{ route('rooms.show', $checkin->room) }}">{{ $checkin->room->room_number }}</a>
                    </p>
                    <p>
                        <strong>Capacity:</strong><br>
                        {{ $checkin->room->capacity }} bed(s)
                    </p>
                    <p>
                        <strong>Current Occupancy:</strong><br>
                        {{ $checkin->room->getCurrentOccupancy() }} / {{ $checkin->room->capacity }}
                    </p>
                </div>
            </div>

            @if ($checkin->locker)
                <div class="card mt-4">
                    <div class="card-header card-header-primary">
                        <i class="fas fa-cube"></i> Locker Information
                    </div>
                    <div class="card-body">
                        <p>
                            <strong>Locker Number:</strong><br>
                            <a href="{{ route('lockers.show', $checkin->locker) }}">{{ $checkin->locker->locker_number }}</a>
                        </p>
                        <p>
                            <strong>Capacity:</strong><br>
                            {{ $checkin->locker->capacity }} driver(s)
                        </p>
                        <p>
                            <strong>Current Occupancy:</strong><br>
                            {{ $checkin->locker->getCurrentOccupancy() }} / {{ $checkin->locker->capacity }}
                        </p>
                        <p>
                            <strong>Status:</strong><br>
                            @if ($checkin->locker->status === 'tersedia')
                                <span class="badge bg-success">Available</span>
                            @elseif ($checkin->locker->status === 'penuh')
                                <span class="badge bg-warning">Full</span>
                            @else
                                <span class="badge bg-danger">Maintenance</span>
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="card mt-4">
                <div class="card-header card-header-primary">
                    <i class="fas fa-clock"></i> Timeline
                </div>
                <div class="card-body">
                    <p>
                        <strong><i class="fas fa-sign-in-alt text-success"></i> Check-in Time:</strong><br>
                        {{ $checkin->check_in_time->format('d M Y H:i:s') }}
                    </p>
                    @if ($checkin->check_out_time)
                        <p>
                            <strong><i class="fas fa-sign-out-alt text-danger"></i> Check-out Time:</strong><br>
                            {{ $checkin->check_out_time->format('d M Y H:i:s') }}
                        </p>
                    @endif
                    <p>
                        <strong>Status:</strong><br>
                        @if ($checkin->status === 'checked_in')
                            <span class="status-checked-in">Checked In</span>
                        @else
                            <span class="status-checked-out">Checked Out</span>
                        @endif
                    </p>
                    <p>
                        <strong>Processed By:</strong><br>
                        {{ $checkin->user->name }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Add Fine Card -->
            @if ($checkin->status === 'checked_in')
                <div class="card mb-4 border-warning">
                    <div class="card-header card-header-warning">
                        <i class="fas fa-ban"></i> Add Fine
                    </div>
                    <div class="card-body">
                        <form action="{{ route('fines.store') }}" method="POST" id="fineForm">
                            @csrf
                            <input type="hidden" name="checkin_id" value="{{ $checkin->id }}">

                            <div class="mb-4">
                                <label class="form-label fw-bold mb-3">Select Fine Type:</label>
                                
                                <div class="fine-option">
                                    <input class="fine-checkbox-input" type="checkbox" name="fine_types[]" value="smoking" 
                                           id="fine_smoking" data-amount="50000"
                                           @if($checkin->fines->where('fine_type', 'smoking')->count() > 0) checked @endif>
                                    <label class="fine-checkbox-label" for="fine_smoking">
                                        <span class="fine-checkbox-custom">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span class="fine-icon">
                                            <i class="fas fa-smoking"></i>
                                        </span>
                                        <span class="fine-label-text">
                                            <span class="fine-title">Smoking Fine</span>
                                        </span>
                                        <span class="fine-badge">Rp 50.000</span>
                                    </label>
                                </div>

                                <div class="fine-option">
                                    <input class="fine-checkbox-input" type="checkbox" name="fine_types[]" value="eating_drinking" 
                                           id="fine_eating" data-amount="25000"
                                           @if($checkin->fines->where('fine_type', 'eating_drinking')->count() > 0) checked @endif>
                                    <label class="fine-checkbox-label" for="fine_eating">
                                        <span class="fine-checkbox-custom">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span class="fine-icon">
                                            <i class="fas fa-utensils"></i>
                                        </span>
                                        <span class="fine-label-text">
                                            <span class="fine-title">Eating & Drinking on Bed Fine</span>
                                        </span>
                                        <span class="fine-badge">Rp 25.000</span>
                                    </label>
                                </div>

                                <div class="fine-option">
                                    <input class="fine-checkbox-input" type="checkbox" name="fine_types[]" value="drying_clothes" 
                                           id="fine_drying" data-amount="25000"
                                           @if($checkin->fines->where('fine_type', 'drying_clothes')->count() > 0) checked @endif>
                                    <label class="fine-checkbox-label" for="fine_drying">
                                        <span class="fine-checkbox-custom">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span class="fine-icon">
                                            <i class="fas fa-water"></i>
                                        </span>
                                        <span class="fine-label-text">
                                            <span class="fine-title">Drying Clothes in Mess Fine</span>
                                        </span>
                                        <span class="fine-badge">Rp 25.000</span>
                                    </label>
                                </div>

                                <div class="fine-option">
                                    <input class="fine-checkbox-input" type="checkbox" name="fine_types[]" value="littering" 
                                           id="fine_littering" data-amount="25000"
                                           @if($checkin->fines->where('fine_type', 'littering')->count() > 0) checked @endif>
                                    <label class="fine-checkbox-label" for="fine_littering">
                                        <span class="fine-checkbox-custom">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span class="fine-icon">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                        <span class="fine-label-text">
                                            <span class="fine-title">Littering in Mess Fine</span>
                                        </span>
                                        <span class="fine-badge">Rp 25.000</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Dynamic Total Display -->
                            <div class="alert alert-light border-warning mb-3" id="totalFineDisplay" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Total Selected Fines:</span>
                                    <span class="h5 text-warning mb-0" id="totalFineAmount">Rp 0</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" id="description" name="description" rows="2" 
                                    placeholder="Enter fine notes..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-warning w-100 btn-lg" id="submitBtn" disabled>
                                <i class="fas fa-plus"></i> Add Fine
                            </button>
                        </form>
                    </div>
                </div>

                <style>
                    /* Hide default checkbox */
                    .fine-checkbox-input {
                        display: none;
                    }

                    /* Fine option container */
                    .fine-option {
                        margin-bottom: 12px;
                    }

                    /* Checkbox label styling */
                    .fine-checkbox-label {
                        display: flex;
                        align-items: center;
                        padding: 12px 16px;
                        background: #f8f9fa;
                        border: 2px solid #e0e0e0;
                        border-radius: 8px;
                        cursor: pointer;
                        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                        gap: 12px;
                    }

                    .fine-checkbox-label:hover {
                        background-color: #f0f0f0;
                        border-color: #ffc107;
                        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.15);
                    }

                    /* Checked state */
                    .fine-checkbox-input:checked + .fine-checkbox-label {
                        background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.05));
                        border-color: #ffc107;
                        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.25);
                    }

                    /* Custom checkbox styling */
                    .fine-checkbox-custom {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        width: 24px;
                        height: 24px;
                        min-width: 24px;
                        background: white;
                        border: 2px solid #d0d0d0;
                        border-radius: 6px;
                        transition: all 0.3s ease;
                    }

                    .fine-checkbox-input:checked + .fine-checkbox-label .fine-checkbox-custom {
                        background: linear-gradient(135deg, #ffc107, #ffb300);
                        border-color: #ffc107;
                        color: white;
                        box-shadow: 0 2px 6px rgba(255, 193, 7, 0.4);
                    }

                    .fine-checkbox-custom i {
                        font-size: 14px;
                        opacity: 0;
                        transform: scale(0.5);
                        transition: all 0.3s ease;
                    }

                    .fine-checkbox-input:checked + .fine-checkbox-label .fine-checkbox-custom i {
                        opacity: 1;
                        transform: scale(1);
                    }

                    /* Icon styling */
                    .fine-icon {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        width: 36px;
                        height: 36px;
                        min-width: 36px;
                        border-radius: 50%;
                        background: #f0f0f0;
                        transition: all 0.3s ease;
                    }

                    .fine-checkbox-input:checked + .fine-checkbox-label .fine-icon {
                        background: rgba(255, 193, 7, 0.2);
                    }

                    .fine-icon i {
                        font-size: 18px;
                        color: #666;
                        transition: all 0.3s ease;
                    }

                    .fine-checkbox-input:checked + .fine-checkbox-label .fine-icon i {
                        color: #ffc107;
                    }

                    /* Label text */
                    .fine-label-text {
                        flex: 1;
                    }

                    .fine-title {
                        font-weight: 500;
                        color: #333;
                        display: block;
                        font-size: 14px;
                    }

                    .fine-checkbox-input:checked + .fine-checkbox-label .fine-title {
                        color: #ffc107;
                        font-weight: 600;
                    }

                    /* Badge styling */
                    .fine-badge {
                        padding: 6px 12px;
                        background: #f0f0f0;
                        color: #666;
                        border-radius: 20px;
                        font-weight: 600;
                        font-size: 13px;
                        white-space: nowrap;
                        transition: all 0.3s ease;
                    }

                    .fine-checkbox-input:checked + .fine-checkbox-label .fine-badge {
                        background: linear-gradient(135deg, #ffc107, #ffb300);
                        color: white;
                        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
                    }

                    /* Animation */
                    .fine-option {
                        animation: slideIn 0.4s ease;
                    }

                    @keyframes slideIn {
                        from {
                            opacity: 0;
                            transform: translateX(-15px);
                        }
                        to {
                            opacity: 1;
                            transform: translateX(0);
                        }
                    }

                    #totalFineDisplay {
                        animation: fadeIn 0.3s ease;
                    }

                    @keyframes fadeIn {
                        from {
                            opacity: 0;
                        }
                        to {
                            opacity: 1;
                        }
                    }
                </style>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const fineCheckboxes = document.querySelectorAll('.fine-checkbox-input');
                        const totalDisplay = document.getElementById('totalFineDisplay');
                        const totalAmount = document.getElementById('totalFineAmount');
                        const submitBtn = document.getElementById('submitBtn');

                        function updateTotal() {
                            let total = 0;
                            let checkedCount = 0;

                            fineCheckboxes.forEach(checkbox => {
                                if (checkbox.checked) {
                                    total += parseInt(checkbox.dataset.amount);
                                    checkedCount++;
                                }
                            });

                            if (checkedCount > 0) {
                                totalDisplay.style.display = 'block';
                                totalAmount.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                                submitBtn.disabled = false;
                                submitBtn.classList.add('btn-warning-active');
                            } else {
                                totalDisplay.style.display = 'none';
                                submitBtn.disabled = true;
                            }
                        }

                        fineCheckboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', updateTotal);
                        });

                        // Initial check
                        updateTotal();
                    });
                </script>
            @endif

            <!-- Checkout Information -->
            @if ($checkin->checkout)
                <div class="card">
                    <div class="card-header card-header-primary">
                        <i class="fas fa-receipt"></i> Checkout Information
                    </div>
                    <div class="card-body">
                        <p>
                            <strong>Nights Stayed:</strong><br>
                            {{ $checkin->checkout->nights_stayed }} night(s)
                        </p>
                        <p>
                            <strong>Total Cost:</strong><br>
                            <h5>Rp {{ number_format($checkin->checkout->total_cost, 0, ',', '.') }}</h5>
                        </p>
                        <p>
                            <strong>Payment Status:</strong><br>
                            @if ($checkin->checkout->payment_status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-warning">Unpaid</span>
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            <!-- Fines List -->
            <div class="card mt-4">
                <div class="card-header card-header-primary d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-ban"></i> Fines (Denda)</span>
                    @if ($checkin->fines->count() > 0)
                        <span class="badge bg-danger">{{ $checkin->fines->count() }}</span>
                    @endif
                </div>
                <div class="card-body">
                    @if ($checkin->fines->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fine Type</th>
                                        <th>Locker</th>
                                        <th>Amount</th>
                                        <th>Added By</th>
                                        <th>Date</th>
                                        @if ($checkin->status === 'checked_in')
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($checkin->fines as $fine)
                                        <tr>
                                            <td>
                                                <span class="badge bg-warning">{{ $fine->getTypeLabel() }}</span>
                                            </td>
                                            <td>
                                                @if ($checkin->locker)
                                                    <small>{{ $checkin->locker->locker_number }}</small>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>Rp {{ number_format($fine->amount, 0, ',', '.') }}</strong>
                                            </td>
                                            <td>{{ $fine->addedBy->name ?? 'Unknown' }}</td>
                                            <td><small>{{ $fine->created_at->format('d M Y H:i') }}</small></td>
                                            @if ($checkin->status === 'checked_in')
                                                <td>
                                                    <form action="{{ route('fines.destroy', $fine) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this fine?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="{{ $checkin->status === 'checked_in' ? '5' : '4' }}" class="text-end">
                                            <strong>Total Fines:</strong>
                                        </td>
                                        <td>
                                            <h5 class="text-danger">Rp {{ number_format($checkin->getTotalFines(), 0, ',', '.') }}</h5>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">
                            <i class="fas fa-check-circle text-success"></i> No fines recorded for this check-in.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
