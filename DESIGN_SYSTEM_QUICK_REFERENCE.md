# Modern Design System - Quick Reference Guide

## 🎨 Common CSS Classes

### Layout
```css
.container              /* Max-width container (1400px) */
.container-fluid        /* Full-width container */
.grid                   /* CSS Grid */
.grid-cols-{1-6}       /* Grid columns (1-6) */
.flex                   /* Flexbox */
.flex-col              /* Flex column direction */
```

### Spacing
```css
.gap-{xs|sm|md|lg|xl}  /* Gap between grid/flex items */
.p-{xs|sm|md|lg|xl}    /* Padding */
.m-{xs|sm|md|lg|xl}    /* Margin */
.mt-*, .mb-*, .ml-*, .mr-* /* Directional spacing */
```

### Text
```css
.text-{xs|sm|base|lg|xl|2xl|3xl}  /* Font sizes */
.font-{light|normal|medium|semibold|bold}  /* Font weights */
.text-{left|center|right}         /* Text alignment */
.text-{primary|secondary|tertiary} /* Text colors */
.text-{success|warning|error|info} /* Semantic colors */
```

### Colors
```css
.bg-{primary|secondary|white}     /* Background colors */
.text-{primary|secondary|success|warning|error}  /* Text colors */
```

### Utilities
```css
.rounded-{sm|md|lg|xl|full}       /* Border radius */
.shadow-{sm|md|lg|xl}             /* Box shadows */
.transition                        /* Smooth transitions */
.cursor-pointer                    /* Pointer cursor */
.w-full                           /* Full width */
.hidden                           /* Hide element */
```

## 🎯 Component Quick Reference

### Buttons
```blade
<a href="#" class="btn btn-primary">Primary</a>
<a href="#" class="btn btn-secondary">Secondary</a>
<a href="#" class="btn btn-outline">Outline</a>
<a href="#" class="btn btn-ghost">Ghost</a>

<!-- Sizes -->
<button class="btn btn-sm">Small</button>
<button class="btn btn-lg">Large</button>
```

### Cards
```blade
<div class="card">
    <div class="card-header">Header</div>
    <div class="card-body">Content</div>
    <div class="card-footer">Footer</div>
</div>
```

### Badges
```blade
<span class="badge badge-primary">Primary</span>
<span class="badge badge-success">Success</span>
<span class="badge badge-warning">Warning</span>
<span class="badge badge-error">Error</span>
```

### Forms
```blade
<div class="form-group">
    <label class="form-label">Label</label>
    <input type="text" class="form-control">
    <div class="form-helper">Helper text</div>
</div>
```

### Tables
```blade
<div class="table-container">
    <table class="table">
        <thead>
            <tr><th>Column</th></tr>
        </thead>
        <tbody>
            <tr><td>Data</td></tr>
        </tbody>
    </table>
</div>
```

## 🔧 Blade Components

### Stat Card
```blade
@include('components.stat-card', [
    'icon' => '🏗️',
    'label' => 'Total Projects',
    'value' => '24',
    'variant' => 'primary'
])
```

### Page Header
```blade
@include('components.page-header', [
    'title' => 'Page Title',
    'subtitle' => 'Description',
    'actions' => [
        ['label' => 'New', 'url' => '#', 'class' => 'btn-primary']
    ]
])
```

### Action Card
```blade
@include('components.action-card', [
    'icon' => '➕',
    'title' => 'Create New',
    'description' => 'Description',
    'link' => route('create')
])
```

## 🎨 Color Variables

### Primary Colors
```css
var(--color-primary-500)    /* #4F46E5 - Main primary */
var(--color-secondary-500)  /* #10B981 - Main secondary */
```

### Semantic Colors
```css
var(--color-success)        /* #10B981 - Green */
var(--color-warning)        /* #F59E0B - Amber */
var(--color-error)          /* #EF4444 - Red */
var(--color-info)           /* #3B82F6 - Blue */
```

### Text Colors
```css
var(--color-text-primary)   /* #111827 - Dark */
var(--color-text-secondary) /* #6B7280 - Medium */
var(--color-text-tertiary)  /* #9CA3AF - Light */
```

### Gradients
```css
var(--gradient-primary)     /* Purple gradient */
var(--gradient-secondary)   /* Green gradient */
var(--gradient-accent)      /* Amber gradient */
```

## 📐 Responsive Grid Examples

### 4-Column Grid (Responsive)
```blade
<div class="grid grid-cols-4 gap-lg">
    <!-- 4 cols desktop, 2 tablet, 1 mobile -->
</div>
```

### Auto-Fit Grid
```blade
<div class="grid grid-auto-fit gap-lg">
    <!-- Automatically fits items -->
</div>
```

### Flexbox Layout
```blade
<div class="flex items-center justify-between gap-md">
    <div>Left</div>
    <div>Right</div>
</div>
```

## 🎭 Animation Classes

```css
.animate-fade-in          /* Fade in */
.animate-fade-in-up       /* Fade in from bottom */
.animate-slide-in-left    /* Slide from left */
.animate-scale-in         /* Scale up */
.animate-pulse            /* Pulsing effect */
```

## 📱 Mobile-First Breakpoints

```css
/* Desktop (default): > 1024px */
/* Tablet: 768px - 1024px */
/* Mobile: < 768px */
/* Small Mobile: < 480px */
```

## 💡 Common Patterns

### Page Structure
```blade
<body class="page-wrapper">
    @include('components.navbar')
    
    <main class="page-content">
        <div class="container">
            @include('components.page-header', [...])
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-4 gap-lg mb-2xl">
                @include('components.stat-card', [...])
            </div>
            
            <!-- Content Cards -->
            <div class="card">
                <div class="card-header">...</div>
                <div class="card-body">...</div>
            </div>
        </div>
    </main>
</body>
```

### Clickable Table Row
```blade
<tr class="clickable" onclick="location.href='{{ route('item.show', $item->id) }}'">
    <td><strong>{{ $item->name }}</strong></td>
</tr>
```

### Empty State
```blade
<div class="empty-state">
    <div class="empty-state-icon">📊</div>
    <h3 class="empty-state-title">No Data</h3>
    <p class="empty-state-message">Description</p>
    <a href="#" class="btn btn-primary">Action</a>
</div>
```

### Form with Validation
```blade
<div class="form-group">
    <label class="form-label required">Name</label>
    <input type="text" class="form-control @error('name') border-error @enderror">
    @error('name')
        <div class="form-error">{{ $message }}</div>
    @enderror
</div>
```

## 🔥 Pro Tips

1. **Always use the container**: Wrap content in `.container` for proper spacing
2. **Animate on scroll**: Add animation classes with delays for nice effects
3. **Use semantic colors**: Choose colors based on meaning (success, warning, etc.)
4. **Mobile-first**: Test on mobile, then tablet, then desktop
5. **Reuse components**: Use Blade components for consistency
6. **Check contrast**: Ensure text is readable on all backgrounds
7. **Add loading states**: Use `.spinner` for async operations

## 📋 Checklist for New Pages

- [ ] Include modern.css
- [ ] Include Inter font from Google Fonts
- [ ] Wrap in `.page-wrapper`
- [ ] Include navbar component
- [ ] Use `.page-content` for main content
- [ ] Add `.container` wrapper
- [ ] Include page header component
- [ ] Use grid/flexbox for layouts
- [ ] Add animation classes for polish
- [ ] Test on mobile devices
- [ ] Check accessibility (focus states, contrast)
- [ ] Verify all links and buttons work

---

**Quick Start Template:**

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Title - SiteLedger</title>
    <link rel="stylesheet" href="{{ asset('css/modern.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="page-wrapper">
    @include('components.navbar')

    <main class="page-content">
        <div class="container">
            @include('components.page-header', [
                'title' => 'Your Page Title',
                'subtitle' => 'Description'
            ])

            <!-- Your content here -->
        </div>
    </main>
</body>
</html>
```
