# 📋 QUICK REFERENCE - ID CARD TESTING

## FASTEST WAY TO TEST

### 1️⃣ Login
```
URL: http://localhost:8000/login
Email: petugas@example.com
Password: password
```

### 2️⃣ Go to Add Driver
```
URL: http://localhost:8000/drivers/create
```

### 3️⃣ Click "Generate" Button
- Expected: ID Card fills with format `DRV-XXXXX`
- Example: `DRV-45829`

### 4️⃣ Fill Other Fields
```
Name: Test Driver
Phone: 081234567890
Email: driver@test.com
Address: Jl. Test No.1
```

### 5️⃣ Click "Save Driver"
- Expected: Success message + redirect to drivers list

### 6️⃣ Verify Created
- ID Card saved successfully in database

---

## TEST RESULTS CHECKLIST

| # | Test | Status | Notes |
|---|------|--------|-------|
| 1 | Generate produces DRV-XXXXX format | ⬜ | |
| 2 | ID Card auto-fills in input field | ⬜ | |
| 3 | Multiple generates = different IDs | ⬜ | |
| 4 | No duplicate IDs in database | ⬜ | |
| 5 | Manual input also works | ⬜ | |
| 6 | Form submission successful | ⬜ | |
| 7 | Works in Chrome/Firefox | ⬜ | |
| 8 | Requires authentication | ⬜ | |
| 9 | Error handling works | ⬜ | |
| 10 | API returns correct JSON | ⬜ | |

---

## QUICK VERIFICATION QUERIES

### Check all ID cards in database
```sql
SELECT id, id_card, name FROM drivers ORDER BY id DESC;
```

### Check for duplicates
```sql
SELECT id_card, COUNT(*) as count FROM drivers GROUP BY id_card HAVING count > 1;
```
- Expected: **Empty result** (no duplicates)

### Count total drivers
```sql
SELECT COUNT(*) as total FROM drivers;
```

---

## API ENDPOINT TESTING

### Method
```
GET /api/drivers/generate-id-card
```

### Headers
```
Accept: application/json
Cookie: [Laravel session]
```

### Response (200 OK)
```json
{
  "id_card": "DRV-45829"
}
```

### Response (401 Unauthorized)
```
Redirect to: /login
```

---

## COMMON ISSUES & FIXES

| Issue | Fix |
|-------|-----|
| Generate button not working | Clear cache (Ctrl+Shift+Delete) + Reload |
| CSRF token error | Check @csrf in form |
| ID Card not saved | Check validation rules |
| Duplicate IDs exist | Run migration fresh |
| Route not found | Run `php artisan route:list` |

---

## EXPECTED FILES MODIFIED

✅ `resources/views/drivers/create.blade.php`
- Added Generate button
- Added JavaScript for API call

✅ `app/Http/Controllers/DriverController.php`
- Added generateIdCard() method

✅ `routes/web.php`
- Added generate-id-card route

---

## SUCCESS INDICATORS

✅ **UI Level:**
- Button appears with magic wand icon
- Loading state shows spinner
- Success feedback (green border)
- No JavaScript errors in console

✅ **Database Level:**
- ID Card saved correctly
- All IDs unique
- No NULL values

✅ **API Level:**
- Returns JSON format
- HTTP 200 status
- Valid ID Card in response

---

**Overall Test Status:** _____________________

**Tested by:** _________________________ **Date:** ________

**Approved by:** _________________________ **Date:** ________

