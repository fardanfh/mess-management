# 🎨 Color Palette Update - Sistem Manajemen Mess

## New Color Scheme Applied

### Primary Colors
- **Light Gray**: `#F7F7F7` - Background, light elements
- **Yellow/Gold**: `#FFC107` - Primary accent, buttons, highlights
- **Black**: `#000000` - Text, borders, dark elements

### Font
- **Primary Font**: Poppins (Google Fonts)
  - Weights: 300, 400, 500, 600, 700, 800
  - Modern, clean, professional appearance

## Where Colors Are Applied

### Background Gradient
```
Gradient: #F7F7F7 → #FFC107 → #000000 (top to bottom)
```

### Components
| Component | Color | Usage |
|-----------|-------|-------|
| **Primary Button** | #FFC107 | Main action buttons, Submit |
| **Secondary Button** | #000000 | Alternate actions, Links |
| **Text** | #000000 | All body text |
| **Accent** | #FFC107 | Icons, highlights, focus states |
| **Card Background** | #FFFFFF | Form containers |
| **Borders** | #DDD / #FFC107 | Form inputs (normal/focus) |
| **Form Focus** | #FFC107 | Input borders on focus |

## Pages Using New Scheme
✅ Login page (`/login`)
✅ Register page (`/register`)  
✅ Password reset pages
✅ Email verification pages
✅ **Dashboard (app layout)** - NEW! Balanced black & yellow
✅ All authenticated pages

## Button Styles

### Primary Button (#FFC107)
- Background: Linear gradient (#FFC107 → #FFD700)
- Text Color: #000000
- Hover: Darker yellow gradient with shadow
- Border Radius: 8px

### Secondary/Outline Buttons (#000000)
- Border: 2px solid #000000
- Text Color: #000000
- Hover: Background #000000, Text #FFC107
- Border Radius: 8px

## Form Controls

### Input Fields
- Border: 1px solid #DDD
- Border Radius: 8px
- Font: Poppins (15px)
- Padding: 12px 16px

### Input Focus State
- Border Color: #FFC107
- Box Shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25)

### Checkboxes
- Border: 2px solid #000000
- Checked: Background #FFC107

## Dashboard Styling (NEW!)

### Sidebar
- **Background**: Dark gradient (#000000 → #1a1a1a)
- **Border**: 3px solid yellow (#FFC107) - right border
- **Brand Text**: Yellow (#FFC107), weight 700
- **Brand Border**: 2px solid yellow (#FFC107)
- **Nav Links**: White text, hover to yellow
- **Nav Icons**: Yellow (#FFC107)
- **Active Link**: Yellow background with yellow left border

### Cards & Headers
- **Card Header**: Yellow gradient (#FFC107 → #FFD700)
- **Header Text**: Black (#000000), weight 600
- **Card Body**: White background
- **Stat Cards**: Yellow left border (4px)
- **Stat Title**: Black (#000000), weight 700

### Navbar
- **Background**: Dark gradient (#000000 → #1a1a1a)
- **Bottom Border**: 2px solid yellow (#FFC107)
- **Brand Text**: Yellow (#FFC107)
- **Nav Links**: White, hover to yellow
- **Shadow**: Yellow-tinted shadow

### Badges & Status
| Badge | Color | Usage |
|-------|-------|-------|
| Available | #27ae60 (Green) | Room tersedia |
| Occupied | #e74c3c (Red) | Room terisi |
| Maintenance | #FFC107 (Yellow) | Room perbaikan |
| Checked In | #27ae60 (Green) | Driver status |

## Typography

All text now uses **Poppins** font with the following weights:

| Element | Font Weight | Color |
|---------|-----------|-------|
| Headings (h1-h6) | 700 | #000000 |
| Labels | 600 | #000000 |
| Body Text | 400 | #000000 |
| Muted Text | 400 | #666666 |
| Button Text | 600 | #000000 (buttons) / #FFC107 (outline) |

## Design Consistency

✅ Gradient background matches the design mockup
✅ Three-color palette throughout all pages
✅ Consistent button styling
✅ Poppins font for modern appearance
✅ Proper contrast for accessibility
✅ Smooth transitions and hover effects
✅ **Dashboard sidebar**: Black background with yellow accents (#FFC107 borders, text, icons)
✅ **Dashboard cards**: Yellow gradient headers with black text
✅ **Dashboard buttons**: Yellow gradient with black text
✅ **Dashboard navbar**: Black background with yellow bottom border
✅ **Balanced color distribution**: Black and yellow elements throughout dashboard

## File Updates Made

1. ✅ `resources/views/layouts/guest.blade.php` - Auth pages layout
2. ✅ `resources/views/layouts/app.blade.php` - App layout
3. ✅ `resources/views/auth/login.blade.php` - Cleaned up structure
4. ✅ `resources/views/auth/register.blade.php` - Cleaned up structure

---

**Status**: All pages now use the new color palette and Poppins font! 🎉
