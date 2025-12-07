# DataTables Implementation Guide

**Date**: December 7, 2025  
**Status**: ✅ Complete

## Overview
Implemented DataTables library untuk semua tabel index pages dengan Bootstrap 5 integration dan Indonesian language support.

---

## Features

### Core Features
✅ **Responsive Design** - Automatically adapts to screen size  
✅ **Search/Filter** - Global search across all columns  
✅ **Pagination** - Dynamic page length selector (5, 10, 25, 50, 100)  
✅ **Sorting** - Click column headers to sort  
✅ **Indonesian Language** - All text in Indonesian  
✅ **Bootstrap 5 Integration** - Native Bootstrap styling  

### Display Features
- **Default Page Length**: 10 rows
- **Responsive Table**: Works on mobile/tablet
- **Auto-Width**: Columns adjust to content
- **Empty State**: Custom "Tidak ada data" message

---

## Implementation Details

### Libraries Added to `layouts/admin.blade.php`

**CSS:**
```html
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
```

**JavaScript:**
```html
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
```

### Configuration

**Default Settings:**
- **responsive**: true - Mobile responsive mode
- **autoWidth**: false - Let DataTables handle width
- **pageLength**: 10 - Show 10 rows by default
- **lengthMenu**: [5, 10, 25, 50, 100] - Page options

**Indonesian Language:**
```javascript
language: {
    emptyTable: 'Tidak ada data',
    info: 'Menampilkan _START_ hingga _END_ dari _TOTAL_ entri',
    infoEmpty: 'Menampilkan 0 hingga 0 dari 0 entri',
    infoFiltered: '(disaring dari _MAX_ total entri)',
    lengthMenu: 'Tampilkan _MENU_ entri',
    loadingRecords: 'Memuat...',
    processing: 'Memproses...',
    search: 'Cari:',
    zeroRecords: 'Tidak ada entri yang cocok ditemukan',
    paginate: {
        first: 'Pertama',
        last: 'Terakhir',
        next: 'Selanjutnya',
        previous: 'Sebelumnya'
    }
}
```

---

## Pages with DataTables

### 1. **Drivers Index**
- File: `resources/views/drivers/index.blade.php`
- Table class: `datatable`
- Columns: ID Card, Name, Phone, Email, Status, Created, Actions

### 2. **Rooms Index**
- File: `resources/views/rooms/index.blade.php`
- Table class: `datatable`
- Columns: Room Number, Capacity, Occupancy, Status, Created, Actions

### 3. **Check-ins Index**
- File: `resources/views/checkins/index.blade.php`
- Table class: `datatable`
- Columns: Driver, Room, Check-in, Check-out, Status, Recorded By, Actions

### 4. **Check-outs Index**
- File: `resources/views/checkouts/index.blade.php`
- Table class: `datatable`
- Columns: Driver, Room, Check-out, Nights, Cost, Payment Status, Actions

### 5. **Checkouts Report**
- File: `resources/views/checkouts/report.blade.php`
- Table class: `datatable`
- Columns: Driver, Room, Date, Nights, Cost, Payment Status, Invoice

---

## How to Use

### How to Add DataTables to a New Table

1. Add `datatable` class to your `<table>` element:
```blade
<table class="table table-hover datatable">
    <thead>
        <!-- headers -->
    </thead>
    <tbody>
        <!-- rows -->
    </tbody>
</table>
```

2. That's it! DataTables will automatically initialize when page loads.

### Search Functionality
- Users can type in the "Cari:" input box to filter table
- Search works across all visible columns
- Results update in real-time

### Pagination
- Users can select rows per page: 5, 10, 25, 50, or 100
- Navigation buttons: Pertama, Sebelumnya, Selanjutnya, Terakhir
- Shows current range: "Menampilkan X hingga Y dari Z entri"

### Sorting
- Click any column header to sort ascending/descending
- Arrow indicator shows sort direction
- Click again to reverse sort

---

## Browser Support

✅ Chrome/Edge (latest)  
✅ Firefox (latest)  
✅ Safari (latest)  
✅ Mobile browsers  

---

## Performance

- **Loading Time**: Fast (CDN cached)
- **Memory**: Minimal impact on page load
- **Rendering**: Handles 100+ rows smoothly
- **Mobile**: Fully responsive and touch-friendly

---

## Customization Examples

### Change Default Page Length
Modify in `admin.blade.php`:
```javascript
pageLength: 25  // Default to 25 rows
```

### Change Length Menu Options
```javascript
lengthMenu: [10, 25, 50, 100, 500]
```

### Disable Responsive Mode
```javascript
responsive: false
```

### Add Column Visibility Toggle
Add to DataTables config:
```javascript
buttons: ['colvis']
```

---

## Troubleshooting

### DataTables not working?
1. Check browser console for errors
2. Verify jQuery is loaded before DataTables
3. Ensure table has class `datatable`
4. Check CDN links are accessible

### Search not working?
- Ensure all `<th>` headers are present
- Verify table structure is valid
- Clear browser cache and reload

### Pagination buttons missing?
- Check if table has more than pageLength rows
- Verify Bootstrap 5 CSS is loaded
- Check for JavaScript errors in console

---

## Future Enhancements

Possible additions:
- [ ] Export to Excel/CSV buttons
- [ ] Column visibility selector
- [ ] Advanced filtering UI
- [ ] Server-side processing for large datasets
- [ ] Custom styling for specific columns

---

## References

- **DataTables Documentation**: https://datatables.net/
- **Bootstrap 5 Integration**: https://datatables.net/examples/styling/bootstrap5
- **jQuery**: https://jquery.com/

---

## Version Information

- **DataTables**: 1.13.7
- **jQuery**: 3.7.0
- **Bootstrap**: 5.3.0
- **Responsive Extension**: 2.5.0

---

## Notes

1. All tables are automatically initialized on page load
2. No manual configuration needed per table
3. Language is automatically set to Indonesian
4. Responsive design works on all devices
5. Search is case-insensitive and matches partial text
