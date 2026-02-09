# Colorful Theme CSS - Complete Class Reference

## Overview
The colorful theme provides vibrant, consistent styling across all views in SiteLedger. All classes use CSS custom properties (variables) and `!important` flags to ensure they work independently without conflicts.

## Color Palette

### Primary Colors
- `--color-primary`: #6366F1 (Indigo)
- `--color-secondary`: #EC4899 (Pink)
- `--color-success`: #10B981 (Green)
- `--color-warning`: #F97316 (Orange)
- `--color-danger`: #EF4444 (Red)
- `--color-info`: #3B82F6 (Blue)
- `--color-purple`: #8B5CF6 (Purple)

## CSS Classes

### Button Classes
```
.btn-colorful-primary    - Indigo gradient button
.btn-colorful-secondary  - Pink gradient button
.btn-colorful-success    - Green gradient button
.btn-colorful-warning    - Orange gradient button
.btn-colorful-danger     - Red gradient button
```

### Card Classes
```
.card-colorful-primary    - Indigo gradient card
.card-colorful-secondary  - Pink gradient card
.card-colorful-success    - Green gradient card
.card-colorful-warning    - Orange gradient card
.card-colorful-danger     - Red gradient card
.card-colorful-purple     - Purple gradient card
.card-colorful-sunset     - Sunset gradient card
.card-colorful-ocean      - Ocean gradient card
.card-colorful-rainbow    - Rainbow animated card
```

### Badge Classes
```
.badge-colorful-primary   - Indigo badge
.badge-colorful-success   - Green badge
.badge-colorful-warning   - Orange badge
.badge-colorful-danger    - Red badge
.badge-colorful-info      - Blue badge
```

### Stat Card Classes
```
.stat-card-primary        - Indigo stat card
.stat-card-secondary      - Pink stat card
.stat-card-success        - Green stat card
.stat-card-warning        - Orange stat card
.stat-card-danger         - Red stat card
.stat-card-info           - Blue stat card
.stat-card-purple         - Purple stat card
.stat-card-sunset         - Sunset stat card
.stat-card-ocean          - Ocean stat card

Classes can be combined with:
.stat-card-colorful       - Base stat card styling
```

### Table Classes
```
.table-colorful           - Colorful table with gradient header
```

### Alert Classes
```
.alert-colorful           - Base alert styling
.alert-colorful-success   - Green success alert
.alert-colorful-warning   - Orange warning alert
.alert-colorful-danger    - Red danger alert
.alert-colorful-info      - Blue info alert
```

### Form Classes
```
.form-control-colorful    - Colorful form input with focus effects
```

### Text Color Classes
```
.text-primary     - Indigo text
.text-secondary   - Pink text
.text-success     - Green text
.text-warning     - Orange text
.text-danger      - Red text
.text-info        - Blue text
```

### Background Color Classes
```
.bg-primary       - Indigo background with white text
.bg-primary-light - Light indigo background
.bg-secondary     - Pink background with white text
.bg-secondary-light - Light pink background
.bg-success       - Green background with white text
.bg-success-light - Light green background
.bg-warning       - Orange background with white text
.bg-warning-light - Light orange background
.bg-danger        - Red background with white text
.bg-danger-light  - Light red background
.bg-info          - Blue background with white text
.bg-info-light    - Light blue background
```

### Border Color Classes
```
.border-primary   - Indigo border
.border-secondary - Pink border
.border-success   - Green border
.border-warning   - Orange border
.border-danger    - Red border
.border-info      - Blue border
```

### Animation Classes
```
.animate-pulse-colorful   - Pulse animation
.animate-bounce-colorful  - Bounce animation
.animate-glow             - Glow animation
```

## Usage Examples

### Basic Button
```html
<button class="btn-colorful-primary">Primary Button</button>
<button class="btn-colorful-success">Success Button</button>
```

### Card with Stats
```html
<div class="card-colorful-primary">
    <h3>Dashboard Widget</h3>
    <p>Colorful card content</p>
</div>
```

### Table
```html
<table class="table-colorful">
    <thead>
        <tr>
            <th>Name</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Example</td>
            <td>123</td>
        </tr>
    </tbody>
</table>
```

### Form Input
```html
<input type="text" class="form-control-colorful" placeholder="Enter text">
```

### Alert
```html
<div class="alert-colorful alert-colorful-success">
    Success message
</div>
```

### Badges
```html
<span class="badge-colorful-primary">Primary</span>
<span class="badge-colorful-success">Success</span>
```

## CSS Variables Available

All colors are defined as CSS variables and can be used in custom styles:

```css
:root {
    --color-primary: #6366F1;
    --color-primary-dark: #4F46E5;
    --color-primary-light: #E0E7FF;
    
    --color-secondary: #EC4899;
    --color-secondary-dark: #DB2777;
    --color-secondary-light: #FCE7F3;
    
    --color-success: #10B981;
    --color-success-dark: #059669;
    --color-success-light: #D1FAE5;
    
    --color-warning: #F97316;
    --color-warning-dark: #EA580C;
    --color-warning-light: #FFEDD5;
    
    --color-danger: #EF4444;
    --color-danger-dark: #DC2626;
    --color-danger-light: #FEE2E2;
    
    --color-info: #3B82F6;
    --color-info-dark: #1D4ED8;
    --color-info-light: #DBEAFE;
    
    --color-purple: #8B5CF6;
    --color-purple-dark: #7C3AED;
    --color-purple-light: #F3E8FF;
    
    /* Gradients */
    --gradient-primary: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
    --gradient-secondary: linear-gradient(135deg, #EC4899 0%, #DB2777 100%);
    --gradient-success: linear-gradient(135deg, #10B981 0%, #059669 100%);
    --gradient-warning: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
    --gradient-danger: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    --gradient-sunset: linear-gradient(135deg, #F97316 0%, #EC4899 50%, #8B5CF6 100%);
    --gradient-ocean: linear-gradient(135deg, #06B6D4 0%, #3B82F6 50%, #6366F1 100%);
    --gradient-rainbow: linear-gradient(135deg, #6366F1 0%, #8B5CF6 25%, #EC4899 50%, #F59E0B 75%, #10B981 100%);
    
    /* Shadows */
    --shadow-primary: 0 10px 25px rgba(99, 102, 241, 0.3);
    --shadow-secondary: 0 10px 25px rgba(236, 72, 153, 0.3);
    --shadow-success: 0 10px 25px rgba(16, 185, 129, 0.3);
    --shadow-warning: 0 10px 25px rgba(249, 115, 22, 0.3);
    --shadow-danger: 0 10px 25px rgba(239, 68, 68, 0.3);
}
```

## Integration Notes

1. **Load Order**: colorful-theme.css loads BEFORE modern.css to ensure base coloring works
2. **Specificity**: All rules use `!important` to prevent conflicts with other CSS
3. **Responsive**: All classes include responsive adjustments for mobile devices
4. **Cross-Browser**: Compatible with all modern browsers
5. **Accessibility**: Colors meet WCAG contrast ratios for readability

## File Locations

- Source: `/resources/css/colorful-theme.css`
- Public: `/public/css/colorful-theme.css`
- Loaded in: All main layout files (app.blade.php, admin.blade.php, dashboard-modern.blade.php, dashboard.blade.php)
