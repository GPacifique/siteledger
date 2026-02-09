# Modern Design System Documentation
## SiteLedger Professional UI/UX Implementation

---

## 📋 Table of Contents
1. [Overview](#overview)
2. [Color Palette](#color-palette)
3. [Typography](#typography)
4. [Components](#components)
5. [Layout System](#layout-system)
6. [Usage Examples](#usage-examples)
7. [Responsive Design](#responsive-design)
8. [Accessibility](#accessibility)

---

## 🎨 Overview

This modern design system provides a comprehensive, professional UI framework for the SiteLedger Laravel Blade application. It follows SaaS and admin dashboard best practices with a focus on:

- **Clean, Professional Aesthetic**: Balanced colors, modern gradients, and subtle shadows
- **Reusable Components**: Modular Blade components for consistency
- **Responsive Design**: Mobile-first approach with breakpoints for all devices
- **Accessibility**: WCAG 2.1 AA compliant with proper contrast ratios
- **Performance**: Optimized CSS with modern practices (Flexbox/Grid)

---

## 🎨 Color Palette

### Primary Colors
```css
--color-primary: #4F46E5 (Indigo)
--color-primary-50: #EEF2FF (Lightest)
--color-primary-100: #E0E7FF
--color-primary-600: #4F46E5 (Base)
--color-primary-900: #312E81 (Darkest)
```

### Secondary Colors
```css
--color-secondary: #10B981 (Green)
--color-secondary-50: #ECFDF5
--color-secondary-600: #059669
```

### Semantic Colors
- **Success**: `#10B981` (Green) - Used for positive actions, completed states
- **Warning**: `#F59E0B` (Amber) - Used for cautionary messages, pending states
- **Error**: `#EF4444` (Red) - Used for errors, destructive actions
- **Info**: `#3B82F6` (Blue) - Used for informational messages

### Neutral Colors
- **Gray Scale**: 50, 100, 200, 300, 400, 500, 600, 700, 800, 900
- **Text Colors**: Primary (#111827), Secondary (#6B7280), Tertiary (#9CA3AF)

### Gradients
```css
--gradient-primary: linear-gradient(135deg, #667EEA 0%, #764BA2 100%)
--gradient-secondary: linear-gradient(135deg, #10B981 0%, #059669 100%)
--gradient-accent: linear-gradient(135deg, #F59E0B 0%, #D97706 100%)
```

---

## 📝 Typography

### Font Family
- **Primary**: Inter (Google Fonts)
- **Fallback**: System fonts (-apple-system, BlinkMacSystemFont, Segoe UI, Roboto)

### Font Sizes
```css
--font-size-xs: 0.75rem    (12px)
--font-size-sm: 0.875rem   (14px)
--font-size-base: 1rem     (16px)
--font-size-lg: 1.125rem   (18px)
--font-size-xl: 1.25rem    (20px)
--font-size-2xl: 1.5rem    (24px)
--font-size-3xl: 1.875rem  (30px)
--font-size-4xl: 2.25rem   (36px)
--font-size-5xl: 3rem      (48px)
```

### Font Weights
- **Light**: 300
- **Normal**: 400
- **Medium**: 500
- **Semibold**: 600
- **Bold**: 700
- **Extrabold**: 800

### Usage Guidelines
- **Page Titles**: `font-size-4xl` + `font-weight-bold`
- **Section Headers**: `font-size-2xl` + `font-weight-semibold`
- **Body Text**: `font-size-base` + `font-weight-normal`
- **Labels**: `font-size-sm` + `font-weight-medium`

---

## 🧩 Components

### 1. Buttons

#### Primary Button
```html
<a href="#" class="btn btn-primary">Primary Action</a>
```
- **Use**: Main call-to-action buttons
- **Style**: Gradient background, white text, shadow on hover

#### Secondary Button
```html
<a href="#" class="btn btn-secondary">Secondary Action</a>
```
- **Use**: Supporting actions
- **Style**: Green gradient

#### Outline Button
```html
<a href="#" class="btn btn-outline">View More</a>
```
- **Use**: Less prominent actions
- **Style**: Transparent with colored border

#### Ghost Button
```html
<a href="#" class="btn btn-ghost">Cancel</a>
```
- **Use**: Tertiary actions
- **Style**: Transparent, subtle hover

#### Button Sizes
```html
<button class="btn btn-sm">Small</button>
<button class="btn">Default</button>
<button class="btn btn-lg">Large</button>
<button class="btn btn-xl">Extra Large</button>
```

### 2. Cards

#### Basic Card
```html
<div class="card">
    <div class="card-header">
        <h3>Card Title</h3>
    </div>
    <div class="card-body">
        Card content goes here
    </div>
    <div class="card-footer">
        Footer content
    </div>
</div>
```

#### Interactive Card
```html
<div class="card card-interactive">
    <!-- Hover effect with elevation -->
</div>
```

#### Stat Card (Blade Component)
```blade
@include('components.stat-card', [
    'icon' => '🏗️',
    'label' => 'Total Projects',
    'value' => '24',
    'variant' => 'primary',
    'trend' => '12% from last month',
    'trendDirection' => 'positive'
])
```

**Variants**: `primary`, `secondary`, `success`, `warning`, `error`, `info`

### 3. Tables

#### Modern Table
```html
<div class="table-container">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Column 1</th>
                    <th>Column 2</th>
                </tr>
            </thead>
            <tbody>
                <tr class="clickable" onclick="location.href='#'">
                    <td><strong>Data</strong></td>
                    <td>More data</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
```

**Features**:
- Hover effects on rows
- Clickable rows with `.clickable` class
- Responsive horizontal scroll
- Striped variant with `.table-striped`

### 4. Forms

#### Form Group
```html
<div class="form-group">
    <label class="form-label required">Field Name</label>
    <input type="text" class="form-control" placeholder="Enter value">
    <div class="form-helper">Helper text goes here</div>
</div>
```

#### Form Sizes
```html
<input type="text" class="form-control form-control-sm">
<input type="text" class="form-control">
<input type="text" class="form-control form-control-lg">
```

#### Input Group
```html
<div class="input-group">
    <span class="input-group-text">RWF</span>
    <input type="number" class="form-control">
</div>
```

### 5. Badges

```html
<span class="badge badge-primary">Primary</span>
<span class="badge badge-success">Success</span>
<span class="badge badge-warning">Warning</span>
<span class="badge badge-error">Error</span>
<span class="badge badge-info">Info</span>
<span class="badge badge-gray">Gray</span>
```

**Sizes**: `.badge-sm`, `.badge`, `.badge-lg`

### 6. Alerts

```html
<div class="alert alert-success">
    <div class="alert-icon">✓</div>
    <div class="alert-content">
        <div class="alert-title">Success!</div>
        <div class="alert-message">Your action was completed successfully.</div>
    </div>
</div>
```

**Types**: `alert-success`, `alert-warning`, `alert-error`, `alert-info`

### 7. Navbar

The navbar component is already implemented in `resources/views/components/navbar.blade.php` with:
- Sticky positioning
- Mobile-responsive hamburger menu
- User dropdown with avatar
- Gradient background
- Smooth hover animations

### 8. Blade Components

#### Page Header Component
```blade
@include('components.page-header', [
    'title' => 'Page Title',
    'subtitle' => 'Page description',
    'breadcrumbs' => [
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Current Page', 'url' => '#']
    ],
    'actions' => [
        ['label' => 'New Item', 'url' => '#', 'icon' => '➕', 'class' => 'btn-primary']
    ]
])
```

#### Table Card Component
```blade
@include('components.table-card', [
    'title' => 'Recent Data',
    'subtitle' => 'Latest activity',
    'viewAllLink' => route('data.index'),
    'columns' => [
        ['label' => 'Name', 'class' => ''],
        ['label' => 'Status', 'class' => ''],
        ['label' => 'Amount', 'class' => 'text-right']
    ],
    'data' => $items
])
```

#### Action Card Component
```blade
@include('components.action-card', [
    'icon' => '🏗️',
    'title' => 'New Project',
    'description' => 'Start a new construction project',
    'link' => route('projects.create')
])
```

---

## 📐 Layout System

### Container
```html
<div class="container">
    <!-- Max-width: 1400px, centered -->
</div>

<div class="container-sm">
    <!-- Max-width: 640px -->
</div>

<div class="container-fluid">
    <!-- Full width -->
</div>
```

### Grid System
```html
<div class="grid grid-cols-4 gap-lg">
    <div>Column 1</div>
    <div>Column 2</div>
    <div>Column 3</div>
    <div>Column 4</div>
</div>
```

**Grid Classes**: `.grid-cols-1` through `.grid-cols-6`

**Auto Grid**:
```html
<div class="grid grid-auto-fit gap-lg">
    <!-- Automatically fits items (min: 250px) -->
</div>
```

### Flexbox Utilities
```html
<div class="flex items-center justify-between gap-md">
    <div>Item 1</div>
    <div>Item 2</div>
</div>
```

**Flex Classes**:
- `.flex`, `.flex-col`, `.flex-wrap`
- `.items-start`, `.items-center`, `.items-end`
- `.justify-start`, `.justify-center`, `.justify-between`
- `.gap-xs`, `.gap-sm`, `.gap-md`, `.gap-lg`, `.gap-xl`

---

## 📱 Responsive Design

### Breakpoints
- **Desktop**: > 1024px (default)
- **Tablet**: 768px - 1024px
- **Mobile**: < 768px
- **Small Mobile**: < 480px

### Responsive Grid
```html
<!-- 4 cols on desktop, 2 on tablet, 1 on mobile -->
<div class="grid grid-cols-4">
    <!-- Content -->
</div>
```

### Mobile Navigation
The navbar automatically converts to a hamburger menu on mobile devices.

---

## ♿ Accessibility

### Focus States
All interactive elements have visible focus indicators:
```css
:focus-visible {
    outline: 2px solid var(--color-primary-500);
    outline-offset: 2px;
}
```

### Color Contrast
- All text meets WCAG 2.1 AA standards (4.5:1 for normal text, 3:1 for large text)
- Links are distinguishable by color and underline

### Screen Reader Support
```html
<button aria-label="Close dialog">×</button>
<span class="sr-only">Screen reader only text</span>
```

### Reduced Motion
Users who prefer reduced motion will see minimal animations:
```css
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
    }
}
```

---

## 🎯 Usage Examples

### Dashboard Page
See: `resources/views/layouts/dashboard-modern.blade.php`

Features:
- Stat cards grid
- Chart cards
- Recent activity table
- Quick action cards

### Projects Index
See: `resources/views/projects/index.blade.php`

Features:
- Page header with breadcrumbs
- Stats overview
- Responsive data table
- Empty states

### Expenses Index
See: `resources/views/expenses/index.blade.php`

Features:
- Collapsible project sections
- Category-based expense cards
- Summary statistics
- Color-coded expense types

---

## 🚀 Getting Started

### 1. Include the CSS
Add to your Blade layout:
```blade
<link rel="stylesheet" href="{{ asset('css/modern.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
```

### 2. Use the Layout
Extend the modern app layout:
```blade
@extends('layouts.app')

@section('content')
    <!-- Your content -->
@endsection
```

### 3. Include the Navbar
```blade
@include('components.navbar')
```

### 4. Build Your Page
Use the components and utilities:
```blade
<main class="page-content">
    <div class="container">
        @include('components.page-header', [...])
        
        <div class="grid grid-cols-4 gap-lg">
            @include('components.stat-card', [...])
        </div>
        
        <div class="card">
            <!-- Your content -->
        </div>
    </div>
</main>
```

---

## 📚 File Structure

```
public/
└── css/
    └── modern.css          # Main design system CSS

resources/
└── views/
    ├── layouts/
    │   ├── app.blade.php            # Modern base layout
    │   └── dashboard-modern.blade.php # Dashboard example
    │
    ├── components/
    │   ├── navbar.blade.php         # Navigation component
    │   ├── stat-card.blade.php      # Stat card component
    │   ├── table-card.blade.php     # Table card component
    │   ├── action-card.blade.php    # Action card component
    │   └── page-header.blade.php    # Page header component
    │
    ├── projects/
    │   └── index.blade.php          # Modern projects page
    │
    └── expenses/
        └── index.blade.php          # Modern expenses page
```

---

## 🎨 Customization

### Changing Colors
Edit the CSS variables in `modern.css`:
```css
:root {
    --color-primary: #YOUR_COLOR;
    --color-secondary: #YOUR_COLOR;
}
```

### Adding New Components
Follow the established patterns:
1. Use CSS variables for colors
2. Include hover/focus states
3. Add transition animations
4. Ensure responsive behavior
5. Test accessibility

---

## 💡 Best Practices

1. **Consistency**: Always use the design system components
2. **Spacing**: Use the spacing scale (xs, sm, md, lg, xl)
3. **Colors**: Stick to the color palette
4. **Typography**: Use semantic font sizes
5. **Accessibility**: Include aria labels and alt text
6. **Performance**: Minimize custom styles
7. **Responsive**: Test on all device sizes

---

## 📞 Support

For questions or issues with the design system:
- Review this documentation
- Check the example pages in `resources/views/`
- Inspect the CSS in `public/css/modern.css`

---

**Version**: 2.0  
**Last Updated**: February 2026  
**Author**: Senior UI/UX Design Team
