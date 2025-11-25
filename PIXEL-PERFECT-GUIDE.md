# PIXEL PERFECT INTEGRATION GUIDE

## 🎯 IMPORTANT: To Get Exact Pixel-Perfect Result

### Step 1: Copy ALL CSS Files

Go to your saved folder:
`c:\Users\User\Desktop\New folder (3)\Seçimlər - MİDA Qeydiyyat_files\`

**Copy these 11 CSS files:**
1. `index.css`
2. `variables.css`
3. `fonts.css`
4. `buttons.css`
5. `inputs.css`
6. `loader.css`
7. `helper.css`
8. `responsive.css`
9. `callout.css`
10. `alert.css`
11. `breadcrumb.css`

**Paste them into:**
`c:\Users\User\Local Sites\custom-map\app\public\wp-content\plugins\Mida\assets\css\`

### Step 2: Rename Plugin File

1. **Delete** (or rename) the old file: `mida.php`
2. **Rename** `mida-new.php` to `mida.php`

### Step 3: Test

1. Go to WordPress admin
2. Deactivate and reactivate the "Mida" plugin
3. Create a page and add shortcode: `[mida_house_form]`
4. View the page

## ✅ What's Done

- ✅ Exact HTML from target website (pixel-perfect)
- ✅ All JavaScript functions from target
- ✅ CSS import structure ready
- ✅ Form structure identical

## 📝 What You Need to Do

Just copy those 11 CSS files from the saved folder to the plugin's css folder!

## 🔍 Verification

After copying the CSS files, the form should look EXACTLY like the target website:
- Same fonts
- Same colors
- Same spacing
- Same button styles
- Same card layout
- Same breadcrumb design
- Same everything!

## 📂 File Structure

```
Mida/
├── mida-new.php (rename to mida.php)
├── assets/
│   ├── css/
│   │   ├── target-styles.css (imports all CSS)
│   │   ├── index.css (COPY FROM SAVED FOLDER)
│   │   ├── variables.css (COPY FROM SAVED FOLDER)
│   │   ├── fonts.css (COPY FROM SAVED FOLDER)
│   │   ├── buttons.css (COPY FROM SAVED FOLDER)
│   │   ├── inputs.css (COPY FROM SAVED FOLDER)
│   │   ├── loader.css (COPY FROM SAVED FOLDER)
│   │   ├── helper.css (COPY FROM SAVED FOLDER)
│   │   ├── responsive.css (COPY FROM SAVED FOLDER)
│   │   ├── callout.css (COPY FROM SAVED FOLDER)
│   │   ├── alert.css (COPY FROM SAVED FOLDER)
│   │   └── breadcrumb.css (COPY FROM SAVED FOLDER)
│   └── js/
│       └── target-functions.js
└── README.md
```

## 🎨 Font Files

If fonts don't load properly, also copy any font files (.woff, .woff2, .ttf) from the saved folder to:
`c:\Users\User\Local Sites\custom-map\app\public\wp-content\plugins\Mida\assets\fonts\`

And check the `fonts.css` file to make sure paths are correct.

## 🚀 Next Steps

Once CSS is working:
1. Save HTML for Step 2 (Mənzil)
2. Save HTML for Step 3 (Ərizə)
3. I'll integrate them the same way - pixel perfect!
