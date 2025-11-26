# Mida WordPress Plugin

**Version:** 1.3.0  
**Author:** difteriya  
**Website:** [xudiyev.com](https://xudiyev.com)  
**Description:** MIDA oyunu üçün lazım olan hər şey

## 🎯 Overview

A comprehensive WordPress plugin for the MIDA housing selection game/competition system. This plugin provides a complete apartment selection interface with timing, user restrictions, rankings, and administrative controls.

- **54 sample apartments** with filtering capabilities

- **Dynamic project management** (Layihələr)## Usage

- **Payment method selection** (Nağd/İpoteka)

- **Floor and room selection** filtersDescribe how to use your plugin here.



### ⏱️ Timer & Competition## Shortcodes

- Timer starts when clicking "Başla" button

- Updates every 10ms for accurate millisecond tracking- `[mida_shortcode]` - Description of what this shortcode does

- Stops automatically when apartment is selected

- Displays in top-left corner during selection## Hooks & Filters

- Shows last 3 personal times on start screen

### Actions

### 🏆 Rankings System- `mida_init` - Runs when the plugin initializes

- **Global Top 10** - Fastest selection times across all users- `mida_before_action` - Runs before a specific action

- **Personal Top 10** - User's best times

- Tabbed interface for easy navigation### Filters

- Medal display (🥇🥈🥉) for top 3 positions- `mida_filter_data` - Filters the plugin data

- Auto-refresh after each selection

## Changelog

### 👥 User Restrictions (Admin)

Administrators can set mandatory options for each user:### 1.0.0

- **Layihə** (Project) - Which project they must select- Initial release

- **Ödəniş üsulu** (Payment method) - Nağd or İpoteka

- **Mərtəbə** (Floor range) - e.g., 1-5 or 1,2,3## Support

- **Otaq sayı** (Room count) - e.g., 2,3,4

For support, please contact [your email] or visit [your website]

If users violate restrictions:

- ⚠️ Warning is logged## License

- ❌ Time excluded from rankings

- 📊 Admin can view all violationsThis plugin is licensed under the GPL v2 or later.


### 🎛️ Admin Dashboard

**Mida → User Restrictions**
- Set mandatory selection criteria per user
- Define allowed projects, payment methods, floors, rooms
- Real-time restriction enforcement

**Mida → Projects**
- Add/edit/delete projects dynamically
- Enable/disable projects (disabled = shown but grayed out)
- Projects appear in Step 1 dropdown

**Mida → Warnings Log**
- View all restriction violations
- See expected vs actual selections
- Track user compliance

### 🗄️ Database Structure

**wp_mida_submissions**
- `id` - Auto-increment primary key
- `user_id` - WordPress user ID (required)
- `selection_time_ms` - Time in milliseconds
- `selection_time_display` - Formatted time (MM:SS:MMM)
- `layihe` - Selected project
- `odenish_usulu` - Payment method
- `mertebe` - Floor number
- `otaq_sayi` - Room count
- `has_warning` - Boolean flag (1 = excluded from rankings)
- `submitted_at` - Timestamp

**wp_mida_warnings**
- `id` - Auto-increment primary key
- `submission_id` - Reference to submission
- `user_id` - User who violated restriction
- `warning_type` - Which field violated (Layihə, Ödəniş üsulu, etc.)
- `expected_value` - What admin required
- `actual_value` - What user selected
- `created_at` - Timestamp

## 📋 Shortcodes

### [mida_house_form]
Displays the main apartment selection form with:
- Start screen with last 3 personal times
- Multi-step selection process
- Timer display
- Apartment list with filtering

### [mida_rankings]
Shows the rankings tables:
- Global Top 10 (all users)
- Personal Top 10 (logged-in user)
- Tabbed interface
- Medal display for top 3

### [mida_debug_db]
Admin debugging tool to view:
- Database table structure
- Recent records
- Total record count

## 🚀 Installation

1. Upload the `Mida` folder to `/wp-content/plugins/`
2. Activate the plugin through WordPress Admin → Plugins
3. Go to **Mida → Projects** to add your projects
4. Go to **Mida → User Restrictions** to set user requirements
5. Add `[mida_house_form]` shortcode to a page
6. Add `[mida_rankings]` shortcode to another page

## 🔧 Configuration

### Adding Projects
1. Go to **Admin → Mida → Projects**
2. Enter project name and click "Add Project"
3. Enable/disable projects as needed
4. Projects appear in dropdown in the order listed

### Setting User Restrictions
1. Go to **Admin → Mida → User Restrictions**
2. For each user, select:
   - Required project
   - Required payment method
   - Allowed floor range (optional)
   - Allowed room counts (optional)
3. Click "Save Restrictions"

### Viewing Warnings
1. Go to **Admin → Mida → Warnings Log**
2. See all violations with:
   - Date/time
   - User details
   - Warning type
   - Expected vs actual values
   - Selection time (not counted in rankings)

## 🎮 How It Works

1. **User logs in** (required for participation)
2. **Starts selection** by clicking "Başla"
3. **Timer begins** counting in MM:SS:MMM format
4. **Selects options:**
   - Project (Layihə)
   - Payment method (Ödəniş üsulu)
   - Selection method (Parametrlər üzrə)
5. **Searches apartments** based on criteria
6. **Clicks apartment** from filtered list
7. **Timer stops** automatically
8. **System validates:**
   - ✅ If matches restrictions → Added to rankings
   - ❌ If violates restrictions → Warning logged, excluded from rankings
9. **Page refreshes** after 1 second
10. **User can try again** to beat their time

## 🏅 Ranking Logic

- Only submissions with `has_warning = 0` count
- Sorted by `selection_time_ms` ASC (fastest first)
- Global rankings show top 10 across all users
- Personal rankings show user's top 10 attempts
- Medals display for positions 1-3 in global rankings

## 🔐 Security

- User authentication required for participation
- Nonce verification on all AJAX requests
- Input sanitization and validation
- SQL injection protection via prepared statements
- Admin-only access to settings pages

## 🎨 Styling

- Google Fonts (Inter family) for consistency
- Bootstrap-based responsive layout
- Custom CSS for pixel-perfect MIDA design
- Smooth animations and transitions
- Mobile-friendly interface

## 📦 Files Structure

```
Mida/
├── mida.php                 # Main plugin file
├── README.md               # This file
├── assets/
│   ├── css/               # Stylesheets
│   │   ├── fonts.css
│   │   ├── variables.css
│   │   ├── index.css
│   │   └── ...
│   └── js/                # JavaScript files
│       ├── script.js      # Main functionality
│       └── target-functions.js
```

## 🔄 Version History

## 🔄 Version History

### 1.3.0 (2025-11-26)
- ✅ **NEW:** Comprehensive Timing Logs page with performance analytics
- ✅ User performance summary with best/slowest/average times
- ✅ Performance improvement tracking (slowest to best)
- ✅ Performance distribution analysis (Fast/Medium/Slow attempts)
- ✅ Detailed attempts log with 200 most recent entries
- ✅ User filtering for individual performance analysis
- ✅ Visual indicators for performance categories
- ✅ Struggle pattern identification

### 1.2.1 (2025-11-26)
- ✅ Added GitHub auto-update functionality
- ✅ Added manual update check page in admin dashboard
- ✅ Added user filtering to Warnings Log
- ✅ Added statistics by user to Warnings Log
- ✅ Enhanced Warnings Log UI with color coding

### 1.2.0 (2025-11-26)
- ✅ Added admin settings for user restrictions
- ✅ Implemented dynamic projects management
- ✅ Added warnings log system
- ✅ Integrated restriction validation
- ✅ Excluded violations from rankings
- ✅ Fixed project name matching
- ✅ Updated payment method handling
- ✅ Added last 3 times display on start screen

### 1.0.0 (Initial Release)
- ✅ Basic apartment selection form
- ✅ Timer functionality
- ✅ Rankings display
- ✅ Sample apartments data

## 👨‍💻 Developer

**difteriya**  
Website: [xudiyev.com](https://xudiyev.com)

## 📄 License

GPL v2 or later

## 🤝 Support

For issues, questions, or feature requests, please contact through [xudiyev.com](https://xudiyev.com)

---

**MIDA oyunu üçün lazım olan hər şey** 🏆
