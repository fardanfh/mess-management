# 🆔 AUTO-GENERATE ID CARD FEATURE

## Overview
Added automatic ID card generation feature untuk Driver Management dengan auto-check untuk uniqueness.

## Fitur

### 1. Frontend - Create Driver Form
- **Location:** `resources/views/drivers/create.blade.php`
- **UI Component:**
  - Input field untuk ID Card
  - Button "Generate" dengan icon magic wand
  - Input group design untuk better UX
  
- **JavaScript Functionality:**
  - Call API endpoint `/api/drivers/generate-id-card`
  - Loading state dengan spinner
  - Show success feedback (green border)
  - Handle errors gracefully
  
### 2. Backend - Controller Method
- **Location:** `app/Http/Controllers/DriverController.php`
- **Method:** `generateIdCard()`
- **Logic:**
  ```php
  // Format: DRV-XXXXX (5 random numbers)
  // Check uniqueness sebelum return
  // Retry jika sudah ada
  ```
- **Return:** JSON response dengan `id_card` value

### 3. API Route
- **URL:** `/api/drivers/generate-id-card`
- **Method:** GET
- **Middleware:** auth (hanya login user)
- **Response:**
  ```json
  {
    "id_card": "DRV-45829"
  }
  ```

## Cara Menggunakan

### Dari UI
1. Buka halaman "Add New Driver" (`/drivers/create`)
2. Klik button **"Generate"** di samping ID Card field
3. System akan auto-fill dengan ID Card unik (format: DRV-XXXXX)
4. Modify jika perlu, atau langsung submit

### Manual
- User bisa tetap input ID Card manual jika tidak ingin generate

## Format ID Card
- **Format:** `DRV-XXXXX`
- **Prefix:** `DRV` (Driver)
- **Suffix:** 5 digit random numbers (00000 - 99999)
- **Contoh:** DRV-00001, DRV-45829, DRV-99999

## Validation
- **Uniqueness Check:** Setiap generated ID card dijamin unik di database
- **Retry Logic:** Jika random number sudah ada, generate ulang otomatis
- **Backend Validation:** Tetap validate unique:drivers di controller store()

## Technical Details

### Files Modified
1. `resources/views/drivers/create.blade.php` - Added generate button & JavaScript
2. `app/Http/Controllers/DriverController.php` - Added generateIdCard() method
3. `routes/web.php` - Added API route

### Dependencies
- jQuery: No (Pure Fetch API)
- Bootstrap 5: Yes (for UI/styling)
- FontAwesome 6: Yes (for icons)

### Security
- Route protected dengan `auth` middleware
- Only authenticated users dapat generate
- No CSRF issues (GET request, read-only)

## Future Enhancements
1. Auto-generate on page load (optional toggle)
2. Bulk generate untuk multiple drivers
3. Custom prefix configuration
4. Format templates (e.g., DRV-YYMMDD-XXXXX)
5. Export generate history

## Testing

### Test 1: Generate Valid ID Card
```
1. Go to /drivers/create
2. Click Generate button
3. Verify ID Card filled with DRV-XXXXX format
4. Submit form
5. Check database for unique entry
```

### Test 2: Multiple Generates
```
1. Click Generate multiple times
2. Each should produce different ID Card
3. Verify no duplicates
```

### Test 3: Manual Entry Still Works
```
1. Manually enter ID Card
2. Leave generate button unchecked
3. Submit form
4. Should accept custom ID Card
```

### Test 4: Duplicate Prevention
```
1. Create driver with ID DRV-12345
2. Click Generate again
3. Should NOT return DRV-12345
4. Should return new unique ID
```
