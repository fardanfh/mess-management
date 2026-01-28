# NFC / RFID Reader Integration — Notes

This file summarizes the recent NFC/ACR120U reader integration changes, how to test, and next steps. Keep this in the repo so the project remembers the intended behaviour.

---

## Summary of changes made

1. Reader app (C#): `c:\Users\IT 02\ACR120UReader\Program.cs`
   - Performs `GET http://127.0.0.1:8000/api/nfc/read/{uid}` on card read.
   - If card is not recognized (404): automatically opens the driver create form:
     `http://127.0.0.1:8000/drivers/create?id_card={UID}` (once per UID while card remains).
   - If card is recognized and `is_checked_in == false`: opens check-in UI once:
     `http://127.0.0.1:8000/checkins/create?id_card={UID}`.
   - If card is recognized and `is_checked_in == true`: calls `POST /api/nfc/checkout` to get current_checkin, then opens
     `http://127.0.0.1:8000/checkins/{checkinId}/checkout-form?id_card={UID}` (once per UID).
   - Adds capture events to `POST /api/rfid-capture` with `status` values: `recognized`, `not_found`, `ready_checkout`, `no_card`.
   - Prevents repeated browser opens with `lastOpenedUid` and resets on card removal.

2. Views (Laravel):
   - `resources/views/drivers/create.blade.php`
     - `id_card` input now uses `value="{{ old('id_card', request('id_card')) }}"`
     - JS fallback to prefill from query-string and focus/select the field.
   - `resources/views/checkins/create.blade.php`
     - `scan_id_card` input prefilled from `request('id_card')` and will auto-trigger `performScan()` when present.
   - `resources/views/checkins/checkout.blade.php`
     - SweetAlert2 confirmation added on checkout submit (loads from CDN if missing), converts `datetime-local` value to server format then submits.

3. Other
   - Small state logic and capture helper `SendCaptureEvent(uid, status)` added to the reader app.

---

## How to test (local dev)

1. Start Laravel app (Laragon or artisan serve):

```powershell
cd c:\laragon\www\mess-management
php artisan serve --host=127.0.0.1 --port=8000
```

2. Build & run reader app (on Windows PowerShell):

```powershell
cd 'c:\Users\IT 02\ACR120UReader'
dotnet build
dotnet run --project 'c:\Users\IT 02\ACR120UReader\ACR120UReader.csproj'
```

3. Test scenarios:
- Scan unknown card: console should beep, open `/drivers/create?id_card=...` once, and `id_card` field should be prefilled.
- Scan known card not checked-in: console should open `/checkins/create?id_card=...` once and the check-in form will auto-scan & prefill driver.
- Scan known card already checked-in: console will POST `/api/nfc/checkout`, open `/checkins/{id}/checkout-form?id_card=...` once and show SweetAlert on confirm.
- Remove card and re-scan to reset the opener behaviour.

---

## Notes / Next steps

- The reader app posts to `/api/rfid-capture`. Ensure backend has handler to broadcast or store these events if you want real-time UI alerts (SweetAlert on dashboard). Currently the front-end expects these events (you may need to implement broadcasting or polling).

- Build & test step is not performed here. Before deploying, run `dotnet build` and test on the actual machine connected to the ACR120U reader.

- If you later want fully automatic check-in / checkout (no UI), we can add direct POST behavior; however this requires a default room policy and is riskier.

- If you change route names or controller responses, update `Program.cs` accordingly (it parses `is_checked_in` and `current_checkin.id` from JSON responses).

---

## Where to look (modified files)

- `c:\Users\IT 02\ACR120UReader\Program.cs`
- `c:\laragon\www\mess-management\resources\views\drivers\create.blade.php`
- `c:\laragon\www\mess-management\resources\views\checkins\create.blade.php`
- `c:\laragon\www\mess-management\resources\views\checkins\checkout.blade.php`

---

If you want, I can also:
- Run the reader build now and report errors.
- Add logging to the reader app to record API responses to a local file for debugging.
- Implement server-side `/api/rfid-capture` handler to broadcast via Pusher/laravel-echo.

Say which you prefer next.