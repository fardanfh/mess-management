# AI Coding Agent Instructions for Mess Management System

## Project Overview
A Laravel 8 driver mess room management system with NFC ID card simulation, real-time occupancy tracking, and integrated C# hardware reader. Two-tier role system (Petugas/Officer and Management) with permission-based access control.

## Architecture & Key Components

### Core Structure
- **Backend**: Laravel 8 (PHP 7.4+) with Sanctum authentication
- **Database**: MySQL 8 with soft deletes, migrations tracked in `database/migrations/`
- **Frontend**: Blade templates + Tailwind CSS 3 + Laravel Mix
- **Hardware Integration**: C# .NET 6 reader (ACR120UReader) communicates via HTTP to Laravel API

### Data Model - Critical Relationships
```
Driver (id_card, status) → hasMany → Checkin (check_in_time, status)
                          → hasMany → Checkout (check_out_time, cost_total)
                          → hasMany → Invoice (amount, payment_status)

Room (status: tersedia/occupied/maintenance) ← hasOne → Checkin (one room per active checkin)

User (role: admin/petugas/management) — hasMany → Checkin (user_id logs who processed)
```

Key Methods on Driver model:
- `isCheckedIn()` - Returns true if driver has active (status='checked_in') checkin
- `currentCheckin()` - Gets latest active checkin for same driver

### Middleware & Permissions
- `CheckRole` / `CheckPermission` middleware in routes enforced via `Gate` facade
- Two core permissions: "manage-drivers", "view-dashboard"
- All protected routes wrapped in `auth` + `permission` middleware
- Activity logs recorded in `ActivityLog` model for compliance

## Critical Workflows & Integration Points

### NFC Check-in Flow
**Trigger**: Driver card tap on ACR120U reader OR manual ID input + Enter key

**Path**: 
1. C# app detects card via DLL `ACR120U.dll` → Calls Laravel API `POST /api/nfc/checkin`
2. Frontend form: `/checkins/create` with AJAX scan via `POST /api/checkins/scan-card`
3. Controller validates driver exists and not already checked in
4. Returns `{status: 'success', driver_id, driver_name}` → Frontend auto-fills driver select
5. User selects room → Submit → Creates `Checkin` record + updates `Room.status` to 'occupied'

**Key Validations**:
- Driver must exist and be active status
- Driver cannot have 2 concurrent checkins (use `isCheckedIn()`)
- Room must be available (status='tersedia')
- Duplicate scan prevention (<500ms debounce in frontend)

### Checkout & Billing Flow
**Path**: `/checkouts/create` → Selects active checkin → Calculates cost (Rp 2.000/night)

**Auto-Generated**:
- `Checkout` record (check_out_time, duration_nights, cost_total)
- `Invoice` record (linked to checkout, tracks payment_status: 'unpaid'/'paid')
- Updates `Room.status` back to 'tersedia'
- Activity log entry

**Cost Formula**: `cost_total = (hours / 24) * 2000` (stored in `Checkout.cost_total`)

### Dashboard & Reports
- Real-time stats: occupied rooms, active drivers, today's revenue
- Monthly charts via Chart.js (JS in `resources/js/`)
- Export functions: PDF via DOMPDF, Excel via Laravel Excel (if installed)
- Report filtering by date range in controller

## Project-Specific Patterns & Conventions

### File Organization
```
app/
  ├── Http/Controllers/
  │   ├── CheckinController.php       # Main checkin logic
  │   ├── CheckoutController.php      # Billing & checkout
  │   ├── DashboardController.php     # Analytics & reports
  │   ├── DriverController.php        # CRUD + ID card generation
  │   ├── RoomController.php
  │   └── Api/NFCController.php       # NFC simulation endpoints
  ├── Models/
  │   ├── Checkin.php (uses SoftDeletes)
  │   ├── Driver.php (key custom methods: isCheckedIn(), currentCheckin())
  │   └── ActivityLog.php             # Audit trail
  └── Traits/
      └── LogsActivity.php            # Auto-log model changes
```

### Model Conventions
- All models use **SoftDeletes** (don't permanently delete, use `deleted_at`)
- Relations use type hints: `BelongsTo`, `HasMany`, `HasOne`
- Custom query scopes for filters (e.g., `where('status', 'active')`)
- Dates cast to Carbon: `protected $dates = ['check_in_time', 'deleted_at'];`

### Controller Patterns
- Resource controllers follow Laravel convention (index/show/create/store/edit/update/destroy)
- API responses use consistent JSON: `{status: 'success'|'error', message: '', data: {}}`
- Validation with `$request->validate()` returning 422 on failure
- ActivityLog trait auto-logs creates/updates on flagged models

### Blade Template Conventions
- Components in `resources/views/components/`
- Reusable form partials (e.g., driver form used in create/edit)
- Form errors displayed via `@error('field')` directive
- CSRF token on all POST forms: `@csrf`

### JavaScript/Frontend Patterns
- Scan input field uses `@keydown.enter` to trigger AJAX scan
- AJAX calls use axios with CSRF header from `<meta>`
- Success/error alerts with auto-dismiss (3-5 sec)
- Loading states on buttons during async operations
- Tailwind utility classes for styling (no custom CSS unless necessary)

## Build & Deployment Commands

### Development Workflow
```bash
# Setup (first time)
composer install && npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed        # Creates users, drivers, rooms

# Daily development
php artisan serve                 # Runs on http://localhost:8000
npm run watch                     # Watches CSS/JS changes
# OR for hot reload: npm run hot

# Testing
php artisan test                  # PHPUnit (see phpunit.xml)
php artisan test --filter=DriverTest
```

### Production Build
```bash
npm run production               # Minifies assets
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database Commands
- **New migration**: `php artisan make:migration create_xxx_table`
- **Run migrations**: `php artisan migrate` (or `migrate --seed` with seeders)
- **Rollback**: `php artisan migrate:rollback` (undoes last batch)
- **Fresh**: `php artisan migrate:fresh --seed` (caution: deletes all data)

## Critical Integration: C# Hardware Reader

The ACR120UReader (at `c:\Users\IT 02\ACR120UReader\`) is a standalone .NET 6 console app:

### How It Works
1. Continuously polls USB/COM ports for ACR120U NFC reader device
2. On card detect: Extracts UID → Calls `POST http://localhost/api/nfc/checkin` with `{id_card: "uid"}`
3. On card remove: Sends no-card alert
4. **Buzzer trigger** via `ACR120U.WriteUserPort()` (bit 2 for audio feedback)

### Integration Points
- **API endpoint**: `POST /api/nfc/checkin` expects JSON `{id_card, room_id}` 
- **Response**: Must return `{status, message, driver_id}` for C# app to proceed
- **Port binding**: C# app hardcoded to `localhost:80` (or configurable via args)
- **ID format**: Expects 8-char hex string or `DRV-XXXXX` format

### Deployment Note
C# app must run on Windows machine with `ACR120U.dll` in PATH or same directory. See `LARAVEL_SETUP_GUIDE.md` for full setup.

## Common Tasks & Implementation Patterns

### Adding New Role/Permission
1. Create migration: `make:migration add_new_permission_to_permissions_table`
2. In seeder (`database/seeders/PermissionSeeder.php`), insert into `permissions` table
3. Assign to role via `role_permission` pivot table
4. Protect route: `->middleware('permission:new-permission')`

### Adding New Report
1. Add method to `DashboardController`: `public function myReport()` 
2. Query models with `with(['relations'])` for eager loading
3. Return view with data: `return view('reports.myreport', compact('data'))`
4. In view, format with `@foreach` and Blade directives
5. Export: Loop data, generate PDF via DOMPDF or CSV string

### Creating New API Endpoint
1. Add route to `routes/api.php` with `Route::prefix('nfc')` or similar
2. Create controller in `app/Http/Controllers/Api/YourController.php`
3. Return JSON: `response()->json(['status' => 'success', 'data' => $data])`
4. Add validation before processing: `$validated = $request->validate([...])`
5. Test with curl or Postman (remember Bearer token if auth required)

### Debugging Tips
- Check logs: `storage/logs/laravel.log`
- DB query log: Enable in `.env` with `DB_LOG=true` + watch logs
- Activity audit: Query `ActivityLog` table for who did what
- API test: Use QUICK_TEST_REFERENCE.md endpoints
- Frontend console for JS errors: Browser DevTools

## Specific File References

- **Role/Permission setup**: `app/Models/Role.php`, `app/Models/Permission.php`
- **Cost calculation**: See `CheckoutController.php` store() method
- **ID card generation**: `DriverController.generateIdCard()` returns random `DRV-XXXXX`
- **NFC simulation**: `app/Http/Controllers/Api/NFCController.php` 
- **Dashboard queries**: `DashboardController.php` index() with aggregate functions
- **Soft deletes recovery**: Use `withTrashed()` / `onlyTrashed()` on queries

## Testing & Verification

Quick manual test flow:
1. Login as petugas@example.com / password
2. Navigate to Drivers → Generate new ID card (e.g., `DRV-12345`)
3. Go to Check-in → Input ID card or scan
4. Select room → Submit
5. Verify Checkin record created + Room status updated to 'occupied'
6. Go to Check-out → Select driver → Confirm
7. Verify Invoice created with cost_total = (duration_hours / 24) * 2000

See `QUICK_TEST_REFERENCE.md` for SQL verification queries.

## External Documentation
- Laravel 8 docs: https://laravel.com/docs/8.x
- NFC integration details: `docs/NFC_INTEGRATION.md`
- Testing guide: `NFC_TAP_SIMULATION.md`
- Quick reference: `QUICK_TEST_REFERENCE.md`
