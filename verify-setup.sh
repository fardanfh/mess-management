#!/bin/bash
# Mess Management System - Setup Verification Script

echo "🔍 Verifying Mess Management System Setup..."
echo "=============================================="
echo ""

# Check PHP
echo "✓ Checking PHP Installation..."
php -v | head -n 1

# Check Composer
echo "✓ Checking Composer Installation..."
composer --version

# Check Database Connection
echo "✓ Checking Database Configuration..."
php artisan db:show 2>/dev/null && echo "  Database: Connected ✅" || echo "  Database: Not connected ⚠️"

# Check Migrations
echo ""
echo "✓ Checking Migrations Status..."
php artisan migrate:status | head -20

# Check Storage Permissions
echo ""
echo "✓ Checking Storage Permissions..."
[ -w storage ] && echo "  storage/: Writable ✅" || echo "  storage/: Not writable ⚠️"
[ -w bootstrap/cache ] && echo "  bootstrap/cache/: Writable ✅" || echo "  bootstrap/cache/: Not writable ⚠️"

# Check Routes
echo ""
echo "✓ Routes Overview:"
php artisan route:list | grep -E "dashboard|drivers|rooms|checkins|checkouts" | head -15

# Check Models
echo ""
echo "✓ Models Status:"
php artisan tinker << 'EOF'
echo "Users: " . App\Models\User::count() . "\n";
echo "Roles: " . App\Models\Role::count() . "\n";
echo "Permissions: " . App\Models\Permission::count() . "\n";
echo "Drivers: " . App\Models\Driver::count() . "\n";
echo "Rooms: " . App\Models\Room::count() . "\n";
echo "Checkins: " . App\Models\Checkin::count() . "\n";
echo "Checkouts: " . App\Models\Checkout::count() . "\n";
echo "Invoices: " . App\Models\Invoice::count() . "\n";
echo "Activity Logs: " . App\Models\ActivityLog::count() . "\n";
exit;
EOF

echo ""
echo "=============================================="
echo "✅ Verification Complete!"
echo ""
echo "Next Steps:"
echo "1. Run: php artisan serve"
echo "2. Visit: http://localhost:8000"
echo "3. Login with:"
echo "   - Petugas: petugas@example.com (password: password)"
echo "   - Management: management@example.com (password: password)"
echo ""
