@extends('layouts.admin')

@section('title', 'New Check-in')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header card-header-primary">
                    <i class="fas fa-id-card"></i> NFC Card Scan / Tap ID Card
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>How to Use:</strong><br>
                        Tap the ID card reader in the input field below, or type the ID card number and press <kbd>Enter</kbd>
                    </div>

                    <form id="scanForm">
                        <div class="mb-3">
                            <label for="scan_id_card" class="form-label">
                                <i class="fas fa-credit-card"></i> Tap ID Card or Enter ID
                            </label>
                            <input type="text" class="form-control form-control-lg"
                                id="scan_id_card"
                                placeholder="Scan card here or type ID (e.g., DRV-12345)..."
                                value="{{ request('id_card') }}"
                                autofocus
                                autocomplete="off">
                            <small class="text-muted d-block mt-2">
                                Auto-detect: Press <kbd>Enter</kbd> to scan
                            </small>
                        </div>
                        <button type="button" class="btn btn-primary w-100" id="scanBtn">
                            <i class="fas fa-search"></i> Scan Card
                        </button>
                    </form>

                    <!-- Scan result message -->
                    <div id="scanResult" class="mt-3"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-check"></i> Driver & Room Selection
                </div>
                <div class="card-body">
                    <form action="{{ route('checkins.store') }}" method="POST" id="checkinForm">
                        @csrf

                        <div class="mb-3">
                            <label for="driver_id" class="form-label">Driver <span class="text-danger">*</span></label>
                            <select class="form-select @error('driver_id') is-invalid @enderror"
                                    id="driver_id" name="driver_id" required>
                                <option value="">-- Select Driver --</option>
                                @foreach ($drivers as $driver)
                                    <option value="{{ $driver->id }}" data-name="{{ $driver->name }}">
                                        {{ $driver->id_card }} - {{ $driver->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('driver_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Room <span class="text-danger">*</span></label>

                            <!-- Search Filter -->
                            <div class="mb-2">
                                <input type="text" id="roomSearch" class="form-control form-control-sm"
                                       placeholder="Search room number...">
                            </div>

                            <!-- Room Grid with Pagination -->
                            <div id="roomGrid" class="room-grid">
                                <!-- Rooms will be loaded here via JavaScript -->
                            </div>

                            <!-- Pagination Controls -->
                            <div id="roomPagination" class="mt-3 d-flex justify-content-center gap-1">
                                <!-- Pagination buttons will be generated here -->
                            </div>

                            <!-- Hidden input to hold selected room -->
                            <input type="hidden" id="room_id" name="room_id" value="" required>

                            @error('room_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <script>
                            // Room data from backend
                            const allRooms = {!! json_encode($availableRooms->toArray()) !!};
                            let filteredRooms = [...allRooms];
                            let currentPage = 1;
                            const roomsPerPage = 24; // Show 24 rooms per page

                            // Initialize room grid
                            function initRoomGrid() {
                                renderRooms();
                                renderPagination();
                                setupSearch();
                            }

                            // Render rooms for current page
                            function renderRooms() {
                                const roomGrid = document.getElementById('roomGrid');
                                roomGrid.innerHTML = '';

                                const start = (currentPage - 1) * roomsPerPage;
                                const end = start + roomsPerPage;
                                const roomsToShow = filteredRooms.slice(start, end);

                                roomsToShow.forEach(room => {
                                    const label = document.createElement('label');
                                    label.className = 'room-selector';
                                    label.innerHTML = `
                                        <input type="radio" name="room_selector" value="${room.id}"
                                               class="room-input" data-room="${room.room_number}">
                                        <div class="room-box">
                                            <div class="room-number">${room.room_number}</div>
                                            <div class="room-capacity">${room.capacity} bed(s)</div>
                                        </div>
                                    `;

                                    label.addEventListener('click', function() {
                                        document.getElementById('room_id').value = room.id;
                                        // Update all radio states
                                        document.querySelectorAll('.room-input').forEach(input => {
                                            input.checked = false;
                                        });
                                        this.querySelector('.room-input').checked = true;
                                        // Reset locker selection when room changes
                                        document.getElementById('locker_id').value = '';
                                        document.querySelectorAll('.locker-input').forEach(input => {
                                            input.checked = false;
                                        });
                                    });

                                    roomGrid.appendChild(label);
                                });

                                // Show message if no rooms
                                if (roomsToShow.length === 0) {
                                    roomGrid.innerHTML = '<div class="col-12 text-center text-muted py-4">No rooms found</div>';
                                }
                            }

                            // Render pagination buttons
                            function renderPagination() {
                                const pagination = document.getElementById('roomPagination');
                                pagination.innerHTML = '';

                                const totalPages = Math.ceil(filteredRooms.length / roomsPerPage);

                                if (totalPages <= 1) return;

                                // Previous button
                                if (currentPage > 1) {
                                    const prevBtn = document.createElement('button');
                                    prevBtn.type = 'button';
                                    prevBtn.className = 'btn btn-sm btn-outline-secondary';
                                    prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
                                    prevBtn.onclick = () => {
                                        currentPage--;
                                        renderRooms();
                                        renderPagination();
                                    };
                                    pagination.appendChild(prevBtn);
                                }

                                // Page numbers
                                const startPage = Math.max(1, currentPage - 2);
                                const endPage = Math.min(totalPages, currentPage + 2);

                                if (startPage > 1) {
                                    const firstBtn = document.createElement('button');
                                    firstBtn.type = 'button';
                                    firstBtn.className = 'btn btn-sm btn-outline-secondary';
                                    firstBtn.textContent = '1';
                                    firstBtn.onclick = () => {
                                        currentPage = 1;
                                        renderRooms();
                                        renderPagination();
                                    };
                                    pagination.appendChild(firstBtn);

                                    const dots = document.createElement('span');
                                    dots.className = 'px-2';
                                    dots.textContent = '...';
                                    pagination.appendChild(dots);
                                }

                                for (let i = startPage; i <= endPage; i++) {
                                    const pageBtn = document.createElement('button');
                                    pageBtn.type = 'button';
                                    pageBtn.className = `btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-outline-secondary'}`;
                                    pageBtn.textContent = i;
                                    pageBtn.onclick = () => {
                                        currentPage = i;
                                        renderRooms();
                                        renderPagination();
                                    };
                                    pagination.appendChild(pageBtn);
                                }

                                if (endPage < totalPages) {
                                    const dots = document.createElement('span');
                                    dots.className = 'px-2';
                                    dots.textContent = '...';
                                    pagination.appendChild(dots);

                                    const lastBtn = document.createElement('button');
                                    lastBtn.type = 'button';
                                    lastBtn.className = 'btn btn-sm btn-outline-secondary';
                                    lastBtn.textContent = totalPages;
                                    lastBtn.onclick = () => {
                                        currentPage = totalPages;
                                        renderRooms();
                                        renderPagination();
                                    };
                                    pagination.appendChild(lastBtn);
                                }

                                // Next button
                                if (currentPage < totalPages) {
                                    const nextBtn = document.createElement('button');
                                    nextBtn.type = 'button';
                                    nextBtn.className = 'btn btn-sm btn-outline-secondary';
                                    nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
                                    nextBtn.onclick = () => {
                                        currentPage++;
                                        renderRooms();
                                        renderPagination();
                                    };
                                    pagination.appendChild(nextBtn);
                                }
                            }

                            // Setup search functionality
                            function setupSearch() {
                                const searchInput = document.getElementById('roomSearch');
                                searchInput.addEventListener('input', function() {
                                    const query = this.value.toLowerCase();
                                    filteredRooms = allRooms.filter(room =>
                                        room.room_number.toLowerCase().includes(query)
                                    );
                                    currentPage = 1;
                                    renderRooms();
                                    renderPagination();
                                });
                            }

                            // Initialize on page load
                            document.addEventListener('DOMContentLoaded', initRoomGrid);
                        </script>

                        <div class="mb-3">
                            <label class="form-label">Locker <span class="text-danger">*</span></label>

                            <!-- Search Filter -->
                            <div class="mb-2">
                                <input type="text" id="lockerSearch" class="form-control form-control-sm"
                                       placeholder="Search locker number...">
                            </div>

                            <!-- Locker Grid with Pagination -->
                            <div id="lockerGrid" class="locker-grid">
                                <!-- Lockers will be loaded here via JavaScript -->
                            </div>

                            <!-- Pagination Controls -->
                            <div id="lockerPagination" class="mt-3 d-flex justify-content-center gap-1">
                                <!-- Pagination buttons will be generated here -->
                            </div>

                            <!-- Hidden input to hold selected locker -->
                            <input type="hidden" id="locker_id" name="locker_id" value="" required>

                            @error('locker_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <script>
                            // Locker data from backend
                            const allLockers = {!! json_encode($availableLockers->toArray()) !!};
                            let filteredLockers = [...allLockers];
                            let currentLockerPage = 1;
                            const lockersPerPage = 10; // Show 10 lockers per page

                            // Initialize locker grid
                            function initLockerGrid() {
                                renderLockers();
                                renderLockerPagination();
                                setupLockerSearch();
                            }

                            // Render lockers for current page
                            function renderLockers() {
                                const lockerGrid = document.getElementById('lockerGrid');
                                lockerGrid.innerHTML = '';

                                const start = (currentLockerPage - 1) * lockersPerPage;
                                const end = start + lockersPerPage;
                                const lockersToShow = filteredLockers.slice(start, end);

                                lockersToShow.forEach(locker => {
                                    const label = document.createElement('label');
                                    label.className = 'locker-selector';
                                    const occupancy = locker.current_occupancy || 0;
                                    const capacity = locker.capacity;
                                    const statusClass = occupancy >= capacity ? 'danger' : 'success';
                                    
                                    label.innerHTML = `
                                        <input type="radio" name="locker_selector" value="${locker.id}"
                                               class="locker-input" data-locker="${locker.locker_number}">
                                        <div class="locker-box">
                                            <div class="locker-number">${locker.locker_number}</div>
                                            <div class="locker-occupancy">
                                                <small class="text-${statusClass === 'danger' ? 'danger' : 'success'} fw-bold">${occupancy}/${capacity}</small>
                                            </div>
                                        </div>
                                    `;

                                    label.addEventListener('click', function() {
                                        document.getElementById('locker_id').value = locker.id;
                                        // Update all radio states
                                        document.querySelectorAll('.locker-input').forEach(input => {
                                            input.checked = false;
                                        });
                                        this.querySelector('.locker-input').checked = true;
                                    });

                                    lockerGrid.appendChild(label);
                                });

                                // Show message if no lockers
                                if (lockersToShow.length === 0) {
                                    lockerGrid.innerHTML = '<div class="col-12 text-center text-muted py-4">No lockers available</div>';
                                }
                            }

                            // Render locker pagination buttons
                            function renderLockerPagination() {
                                const pagination = document.getElementById('lockerPagination');
                                pagination.innerHTML = '';

                                const totalPages = Math.ceil(filteredLockers.length / lockersPerPage);

                                if (totalPages <= 1) return;

                                // Previous button
                                if (currentLockerPage > 1) {
                                    const prevBtn = document.createElement('button');
                                    prevBtn.type = 'button';
                                    prevBtn.className = 'btn btn-sm btn-outline-secondary';
                                    prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
                                    prevBtn.onclick = () => {
                                        currentLockerPage--;
                                        renderLockers();
                                        renderLockerPagination();
                                    };
                                    pagination.appendChild(prevBtn);
                                }

                                // Page numbers
                                const startPage = Math.max(1, currentLockerPage - 2);
                                const endPage = Math.min(totalPages, currentLockerPage + 2);

                                if (startPage > 1) {
                                    const firstBtn = document.createElement('button');
                                    firstBtn.type = 'button';
                                    firstBtn.className = 'btn btn-sm btn-outline-secondary';
                                    firstBtn.textContent = '1';
                                    firstBtn.onclick = () => {
                                        currentLockerPage = 1;
                                        renderLockers();
                                        renderLockerPagination();
                                    };
                                    pagination.appendChild(firstBtn);

                                    const dots = document.createElement('span');
                                    dots.className = 'px-2';
                                    dots.textContent = '...';
                                    pagination.appendChild(dots);
                                }

                                for (let i = startPage; i <= endPage; i++) {
                                    const pageBtn = document.createElement('button');
                                    pageBtn.type = 'button';
                                    pageBtn.className = `btn btn-sm ${i === currentLockerPage ? 'btn-primary' : 'btn-outline-secondary'}`;
                                    pageBtn.textContent = i;
                                    pageBtn.onclick = () => {
                                        currentLockerPage = i;
                                        renderLockers();
                                        renderLockerPagination();
                                    };
                                    pagination.appendChild(pageBtn);
                                }

                                if (endPage < totalPages) {
                                    const dots = document.createElement('span');
                                    dots.className = 'px-2';
                                    dots.textContent = '...';
                                    pagination.appendChild(dots);

                                    const lastBtn = document.createElement('button');
                                    lastBtn.type = 'button';
                                    lastBtn.className = 'btn btn-sm btn-outline-secondary';
                                    lastBtn.textContent = totalPages;
                                    lastBtn.onclick = () => {
                                        currentLockerPage = totalPages;
                                        renderLockers();
                                        renderLockerPagination();
                                    };
                                    pagination.appendChild(lastBtn);
                                }

                                // Next button
                                if (currentLockerPage < totalPages) {
                                    const nextBtn = document.createElement('button');
                                    nextBtn.type = 'button';
                                    nextBtn.className = 'btn btn-sm btn-outline-secondary';
                                    nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
                                    nextBtn.onclick = () => {
                                        currentLockerPage++;
                                        renderLockers();
                                        renderLockerPagination();
                                    };
                                    pagination.appendChild(nextBtn);
                                }
                            }

                            // Setup locker search functionality
                            function setupLockerSearch() {
                                const searchInput = document.getElementById('lockerSearch');
                                searchInput.addEventListener('input', function() {
                                    const query = this.value.toLowerCase();
                                    filteredLockers = allLockers.filter(locker =>
                                        locker.locker_number.toLowerCase().includes(query)
                                    );
                                    currentLockerPage = 1;
                                    renderLockers();
                                    renderLockerPagination();
                                });
                            }

                            // Initialize locker grid on page load
                            document.addEventListener('DOMContentLoaded', initLockerGrid);
                        </script>

                        <div class="mb-3">
                            <label for="check_in_time" class="form-label">Check-in Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('check_in_time') is-invalid @enderror"
                                   id="check_in_time" name="check_in_time" value="{{ old('check_in_time', now()->format('Y-m-d\TH:i')) }}" required>
                            <input type="hidden" id="check_in_time_formatted" name="check_in_time_formatted">
                            @error('check_in_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Default: Current time</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-sign-in-alt"></i> Process Check-in
                            </button>
                            <a href="{{ route('checkins.index') }}" class="btn btn-outline-secondary">
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
    // Handle form submission - convert datetime-local format to Y-m-d H:i
    document.getElementById('checkinForm').addEventListener('submit', function(e) {
        const checkInTimeInput = document.getElementById('check_in_time').value;

        if (checkInTimeInput) {
            // Convert from Y-m-d\TH:i to Y-m-d H:i
            // Input format: 2025-12-07T14:30 → Output format: 2025-12-07 14:30
            const formatted = checkInTimeInput.replace('T', ' ');

            // Create hidden input with correct format
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'check_in_time';
            hiddenInput.value = formatted;

            // Remove original datetime-local input from form data
            document.getElementById('check_in_time').name = 'check_in_time_display';

            // Add formatted hidden input
            this.appendChild(hiddenInput);
        }
    });

    let lastScanTime = 0;
    const SCAN_DELAY = 500; // Prevent duplicate scans within 500ms

    // Auto-scan when user taps/scans ID card
    // NFC reader typically sends ID card data followed by Enter key
    document.getElementById('scan_id_card').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();

            // Prevent duplicate scans
            const now = Date.now();
            if (now - lastScanTime < SCAN_DELAY) {
                return;
            }
            lastScanTime = now;

            performScan();
        }
    });

    // If page opened with ?id_card=..., auto-perform scan once
    document.addEventListener('DOMContentLoaded', function() {
        try {
            const params = new URLSearchParams(window.location.search);
            const idCard = params.get('id_card');
            if (idCard) {
                const input = document.getElementById('scan_id_card');
                if (input) {
                    input.value = idCard;
                    // small delay to allow other init (rooms) to load
                    setTimeout(() => {
                        performScan();
                    }, 300);
                }
            }
        } catch (e) {
            // ignore
        }
    });

    // Manual scan button click
    document.getElementById('scanBtn').addEventListener('click', function() {
        performScan();
    });

    function performScan() {
        const idCard = document.getElementById('scan_id_card').value.trim();

        if (!idCard) {
            showScanError('Please scan or enter ID card number');
            return;
        }

        // Clear previous result
        document.getElementById('scanResult').innerHTML = '';

        // Show loading state
        const scanBtn = document.getElementById('scanBtn');
        const originalText = scanBtn.innerHTML;
        scanBtn.disabled = true;
        scanBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Scanning...';

        fetch('{{ route("checkins.scan-card") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id_card: idCard })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showScanSuccess(data.driver);

                // Auto-fill driver_id
                document.getElementById('driver_id').value = data.driver.id;
                document.getElementById('driver_id').dispatchEvent(new Event('change'));

                // Clear scan input and focus to room selection
                document.getElementById('scan_id_card').value = '';
                setTimeout(() => {
                    document.getElementById('room_id').focus();
                }, 500);
            } else {
                showScanError(data.message);
                document.getElementById('scan_id_card').value = '';
                document.getElementById('scan_id_card').focus();
            }
        })
        .catch(error => {
            showScanError('Error: ' + error.message);
            document.getElementById('scan_id_card').value = '';
            document.getElementById('scan_id_card').focus();
        })
        .finally(() => {
            scanBtn.disabled = false;
            scanBtn.innerHTML = originalText;
        });
    }

    function showScanSuccess(driver) {
        const resultDiv = document.getElementById('scanResult');
        resultDiv.innerHTML = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i>
                <strong>Scan Successful!</strong><br>
                Driver: <strong>${driver.name}</strong><br>
                ID Card: <strong>${driver.id_card}</strong><br>
                <small class="text-muted">Select room and continue...</small>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Auto-dismiss after 3 seconds
        setTimeout(() => {
            resultDiv.innerHTML = '';
        }, 3000);
    }

    function showScanError(message) {
        const resultDiv = document.getElementById('scanResult');
        resultDiv.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <strong>Scan Failed!</strong><br>
                ${message}<br>
                <small class="text-muted">Please try again...</small>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            resultDiv.innerHTML = '';
        }, 5000);
    }

    // Optional: Support direct NFC reader simulation (for testing)
    // Uncomment if you want to test with keyboard input like 'DRV-12345'
    /*
    document.addEventListener('keydown', function(e) {
        // If user presses Ctrl+Alt+N, show simulated NFC input
        if (e.ctrlKey && e.altKey && e.key === 'N') {
            const testId = prompt('Enter test ID card (e.g., DRV-00001):');
            if (testId) {
                document.getElementById('scan_id_card').value = testId;
                document.getElementById('scanBtn').click();
            }
        }
    });
    */
</script>
@endpush

<style>
    .room-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(85px, 1fr));
        gap: 10px;
        margin-bottom: 15px;
        max-height: 400px;
    }

    .locker-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
        margin-bottom: 15px;
        max-height: 500px;
    }

    #roomSearch, #lockerSearch {
        border-color: #e5e7eb;
        font-size: 0.875rem;
    }

    #roomSearch:focus, #lockerSearch:focus {
        border-color: #FEC905;
        box-shadow: 0 0 0 0.2rem rgba(254, 201, 5, 0.25);
    }

    .room-selector, .locker-selector {
        cursor: pointer;
    }

    .room-selector input[type="radio"], .locker-selector input[type="radio"] {
        display: none;
    }

    .room-box, .locker-box {
        background: linear-gradient(135deg, #fdf2f8 0%, #fef3c7 100%);
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        min-height: 75px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .locker-box {
        min-height: 85px;
    }

    .room-box:hover, .locker-box:hover {
        border-color: #FEC905;
        box-shadow: 0 2px 8px rgba(254, 201, 5, 0.2);
        transform: translateY(-2px);
    }

    .room-number, .locker-number {
        font-weight: 700;
        font-size: 15px;
        color: #1f2937;
        margin-bottom: 3px;
        line-height: 1;
    }

    .room-capacity, .locker-occupancy {
        font-size: 11px;
        color: #6b7280;
        line-height: 1.2;
        font-weight: 600;
    }

    .locker-occupancy {
        color: #059669;
    }

    .locker-occupancy.danger {
        color: #dc2626;
    }

    .room-selector input[type="radio"]:checked + .room-box,
    .locker-selector input[type="radio"]:checked + .locker-box {
        background: linear-gradient(135deg, #FEC905 0%, #f4a801 100%);
        border-color: #FEC905;
        transform: scale(1.08);
        box-shadow: 0 4px 12px rgba(254, 201, 5, 0.35);
    }

    .room-selector input[type="radio"]:checked + .room-box .room-number,
    .locker-selector input[type="radio"]:checked + .locker-box .locker-number {
        color: #fff;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .room-selector input[type="radio"]:checked + .room-box .room-capacity,
    .locker-selector input[type="radio"]:checked + .locker-box .locker-occupancy {
        color: rgba(255, 255, 255, 0.95);
    }

    #roomPagination, #lockerPagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 15px;
    }

    #roomPagination .btn-primary, #lockerPagination .btn-primary {
        background-color: #FEC905;
        border-color: #FEC905;
        color: #000;
        font-weight: 600;
    }

    #roomPagination .btn-primary:hover, #lockerPagination .btn-primary:hover {
        background-color: #f4a801;
        border-color: #f4a801;
        color: #000;
    }

    #roomPagination .btn-outline-secondary, #lockerPagination .btn-outline-secondary {
        border-color: #d1d5db;
        color: #6b7280;
    }

    #roomPagination .btn-outline-secondary:hover, #lockerPagination .btn-outline-secondary:hover {
        border-color: #FEC905;
        background-color: #FEC905;
        color: #000;
    }

    #roomPagination .px-2, #lockerPagination .px-2 {
        align-self: center;
        color: #6b7280;
    }

    @media (max-width: 576px) {
        .room-grid, .locker-grid {
            grid-template-columns: repeat(auto-fill, minmax(75px, 1fr));
            gap: 8px;
        }

        .room-box, .locker-box {
            padding: 8px 6px;
            min-height: 70px;
        }

        .room-number, .locker-number {
            font-size: 13px;
        }

        .room-capacity, .locker-occupancy {
            font-size: 9px;
        }

        #roomSearch, #lockerSearch {
            font-size: 0.8rem;
        }
    }
</style>

@endsection
