# 🧪 TESTING GUIDE - AUTO-GENERATE ID CARD

## Persiapan Testing

### 1. Start Laravel Server
```bash
cd c:\laragon\www\mess-management
php artisan serve
```
Server akan berjalan di: `http://localhost:8000`

---

## TEST CASE 1: Generate Valid ID Card (Happy Path)

### Steps:
1. **Login ke aplikasi**
   - URL: `http://localhost:8000/login`
   - Email: `petugas@example.com`
   - Password: `password`
   - Klik: **Login**

2. **Navigate ke Add Driver**
   - URL: `http://localhost:8000/drivers/create`
   - Atau: Menu **Drivers** → **New Driver**

3. **Lihat form dan ID Card field**
   - Perhatikan ada input field untuk "ID Card"
   - Ada button **"Generate"** dengan icon magic wand di sebelahnya

4. **Klik button Generate**
   - Tombol akan berubah: **"Generating..."** dengan spinner
   - Tunggu ± 1 detik

5. **Verifikasi hasil**
   - ✅ ID Card field auto-filled dengan format: `DRV-XXXXX`
   - ✅ Contoh hasil: `DRV-45829`, `DRV-00001`, dll
   - ✅ Input field berubah jadi green border (success feedback)
   - ✅ Green color hilang setelah 2 detik

### Expected Result:
```
ID Card field: DRV-45829
Status: ✅ PASS
```

---

## TEST CASE 2: Submit Form Dengan Generated ID Card

### Steps:
1. **Dari test case 1, lanjutkan ke form lain**
   - Full Name: `Budi Santoso`
   - Phone: `082123456789`
   - Email: `budi@example.com`
   - Address: `Jl. Merdeka No. 123`

2. **Klik button "Save Driver"**
   - Tunggu redirect

3. **Verifikasi driver created**
   - ✅ Redirect ke `/drivers`
   - ✅ Ada success message: **"Driver created successfully"**
   - ✅ Driver baru muncul di list dengan ID Card yang di-generate

### Expected Result:
```
Driver: Budi Santoso - DRV-45829
Status: ✅ PASS - Driver created successfully
```

---

## TEST CASE 3: Generate Multiple Times (Uniqueness Check)

### Steps:
1. **Buka `/drivers/create` (create driver page)**

2. **Generate ID Card - Iteration 1**
   - Klik Generate
   - Hasil: `DRV-12345`
   - Jangan submit, biarkan form tetap terbuka

3. **Klik Generate lagi - Iteration 2**
   - Klik Generate button kedua kalinya
   - Hasil: `DRV-98765` (HARUS BERBEDA dari iteration 1)

4. **Lakukan 3-5 kali klik Generate**
   - Setiap kali harus generate ID berbeda
   - Tidak ada yang sama

### Expected Result:
```
Generate 1: DRV-12345
Generate 2: DRV-98765
Generate 3: DRV-54321
Generate 4: DRV-11111
Generate 5: DRV-77777
Status: ✅ PASS - Setiap ID unik
```

---

## TEST CASE 4: Duplicate Prevention Check

### Steps:
1. **Create driver pertama**
   - Go to `/drivers/create`
   - Generate ID: `DRV-00001`
   - Name: `Driver A`
   - Submit & Save

2. **Create driver kedua**
   - Go to `/drivers/create`
   - Generate ID: `DRV-00002`
   - Name: `Driver B`
   - Submit & Save

3. **Create driver ketiga**
   - Go to `/drivers/create`
   - Keep generating until mendapat `DRV-00001` (manual wait & retry)
   - Generate system akan retry otomatis, tidak akan return `DRV-00001`

### Expected Result:
```
Driver 1: DRV-00001 ✅ Created
Driver 2: DRV-00002 ✅ Created
Driver 3: When generate, NEVER akan return DRV-00001 atau DRV-00002
Status: ✅ PASS - Duplicate prevention works
```

---

## TEST CASE 5: Manual Entry (Fallback)

### Steps:
1. **Buka `/drivers/create`**

2. **Manual masukkan ID Card (TANPA generate)**
   - Klik field ID Card
   - Type manually: `DRV-CUSTOM123`
   - Jangan klik Generate button

3. **Lengkapi form**
   - Name: `Manual Driver`
   - Phone: `081999999999`
   - Address: `Jl. Test`

4. **Submit form**
   - Klik "Save Driver"

5. **Verifikasi**
   - ✅ Driver created dengan ID: `DRV-CUSTOM123`
   - ✅ Manual entry tetap diterima (tidak di-override)

### Expected Result:
```
ID Card Manual: DRV-CUSTOM123
Status: ✅ PASS - Manual entry accepted
```

---

## TEST CASE 6: Error Handling (Network Error Simulation)

### Steps:
1. **Buka browser DevTools**
   - Press: **F12** atau **Ctrl+Shift+I**

2. **Go to Network tab**
   - Throttle network ke "Offline"

3. **Buka `/drivers/create`**

4. **Klik Generate button**
   - Button akan loading
   - Setelah 30 detik, alert muncul: **"Failed to generate ID Card"**
   - Button kembali normal

5. **Turn on network lagi**
   - Throttle back to "No throttling"
   - Klik Generate lagi → Sukses

### Expected Result:
```
Network Off → Error alert ✅
Network On → Success ✅
Status: ✅ PASS - Error handling works
```

---

## TEST CASE 7: Browser Compatibility

### Test di browsers berbeda:
- [ ] Chrome/Chromium
- [ ] Firefox
- [ ] Edge
- [ ] Safari (jika available)

### Steps (sama untuk semua browser):
1. Login
2. Go to `/drivers/create`
3. Generate ID Card
4. Verify hasil

### Expected Result:
```
Chrome: ✅ PASS
Firefox: ✅ PASS
Edge: ✅ PASS
Safari: ✅ PASS
```

---

## TEST CASE 8: Authentication Check

### Steps:
1. **Logout dari aplikasi**
   - Klik Logout

2. **Try akses generate endpoint langsung**
   - URL: `http://localhost:8000/api/drivers/generate-id-card`

3. **Verifikasi**
   - ✅ Redirect ke login page
   - ✅ Tidak bisa akses tanpa login

4. **Login dan retry**
   - Login lagi
   - Akses endpoint
   - ✅ Return JSON dengan ID card (200 OK)

### Expected Result:
```
Without Login: ❌ Redirect to login
With Login: ✅ JSON response with ID card
Status: ✅ PASS - Authentication middleware works
```

---

## TEST CASE 9: Database Verification

### Testing via Database:

1. **Open phpMyAdmin atau MySQL Client**
   - URL: `http://localhost/phpmyadmin` (jika menggunakan Laragon)

2. **Query drivers table**
   ```sql
   SELECT id, id_card, name, created_at FROM drivers ORDER BY created_at DESC LIMIT 10;
   ```

3. **Verifikasi**
   - ✅ Semua ID card unik (tidak ada duplikat)
   - ✅ Format konsisten: `DRV-XXXXX`
   - ✅ Setiap driver ada id_card

### Expected Result:
```
id | id_card      | name           | created_at
1  | DRV-12345    | Budi Santoso   | 2025-12-07...
2  | DRV-98765    | Ahmad Wijaya   | 2025-12-07...
3  | DRV-54321    | Siti Nurhaliza | 2025-12-07...
Status: ✅ PASS - All unique in DB
```

---

## TEST CASE 10: API Response Format

### Testing via API Call:

1. **Open Postman / Insomnia / Terminal**

2. **Make GET request**
   ```bash
   curl -X GET "http://localhost:8000/api/drivers/generate-id-card" \
     -H "Accept: application/json" \
     -H "Cookie: XSRF-TOKEN=YOUR_TOKEN; laravel_session=YOUR_SESSION"
   ```

3. **Or gunakan browser console**
   ```javascript
   fetch('/api/drivers/generate-id-card', {
     method: 'GET',
     headers: { 'Accept': 'application/json' }
   })
   .then(r => r.json())
   .then(d => console.log(d))
   ```

4. **Verifikasi response**
   ```json
   {
     "id_card": "DRV-45829"
   }
   ```

### Expected Result:
```
Status Code: 200
Response Body: { "id_card": "DRV-XXXXX" }
Status: ✅ PASS - API returns correct format
```

---

## Checklist Testing

```
✅ Test 1: Generate Valid ID Card
✅ Test 2: Submit Form dengan Generated ID
✅ Test 3: Generate Multiple Times (Unique)
✅ Test 4: Duplicate Prevention
✅ Test 5: Manual Entry (Fallback)
✅ Test 6: Error Handling
✅ Test 7: Browser Compatibility
✅ Test 8: Authentication Check
✅ Test 9: Database Verification
✅ Test 10: API Response Format
```

---

## Quick Testing Script (Manual Testing)

### Copy-paste ini di browser console saat di `/drivers/create`:

```javascript
// Test 1: Generate 5 ID cards
console.log('Testing Generate ID Card Feature...');
const generateBtn = document.getElementById('generateIdCard');
const idCardInput = document.getElementById('id_card');
const results = [];

async function testGenerate() {
    for (let i = 0; i < 5; i++) {
        generateBtn.click();
        await new Promise(r => setTimeout(r, 1500));
        results.push(idCardInput.value);
    }
    console.table(results);
    console.log('Unique IDs:', new Set(results).size === 5 ? '✅ PASS' : '❌ FAIL');
}

testGenerate();
```

---

## Troubleshooting

### Problem: Button Generate tidak berfungsi
- **Solution:** 
  - Clear browser cache (Ctrl+Shift+Delete)
  - Reload page (Ctrl+R)
  - Check browser console (F12 → Console) untuk errors

### Problem: ID Card tidak terisi
- **Solution:**
  - Check network tab (F12 → Network)
  - Verify route terdaftar: `php artisan route:list | grep generate`
  - Check server logs

### Problem: Duplicate ID Card masih bisa terjadi
- **Solution:**
  - Verify database: `SELECT COUNT(DISTINCT id_card) FROM drivers;`
  - Restart Laravel: `php artisan serve` ulang
  - Check validation di controller store()

### Problem: CSRF Token error
- **Solution:**
  - Verify @csrf ada di form
  - Check middleware di Kernel.php
  - Refresh halaman

---

## Performance Metrics

Target testing results:
- ✅ Generate time: < 500ms
- ✅ Success rate: 100%
- ✅ No duplicates: 0%
- ✅ Browser compatibility: 100%
- ✅ User experience: Smooth with loading state

---

## Report Template

```
TESTING RESULT REPORT
=====================
Date: [Tanggal]
Tester: [Nama]
Environment: Laravel 8, PHP 7.4+, MySQL 8.0+
Browser: [Browser name & version]

TEST RESULTS:
✅ Test 1: Generate Valid ID Card - PASS
✅ Test 2: Submit Form - PASS
✅ Test 3: Uniqueness - PASS
✅ Test 4: Duplicate Prevention - PASS
✅ Test 5: Manual Entry - PASS
✅ Test 6: Error Handling - PASS
✅ Test 7: Browser Compatibility - PASS
✅ Test 8: Authentication - PASS
✅ Test 9: Database - PASS
✅ Test 10: API Response - PASS

OVERALL RESULT: ✅ ALL TESTS PASSED

NOTES:
[Catatan tambahan]
```

---

Selamat testing! 🚀
