# Star Field Animation Component

A beautiful, reusable animated star field with mouse clustering effect.

## Features

- ✨ 150 animated stars that cluster around your mouse pointer
- 🌟 Sparkle effect on some stars
- 🎨 Customizable colors, count, and speed
- 📱 Responsive and performant
- ♻️ Reusable anywhere on your site

## Usage

### Basic Usage

```html
<!-- Add the container -->
<div id="starField" class="star-field"></div>

<!-- Include CSS -->
<link rel="stylesheet" href="/css/star-field.css">

<!-- Include JavaScript -->
<script src="/js/star-field.js"></script>

<!-- Initialize -->
<script>
    new StarField('starField');
</script>
```

### Advanced Usage

```javascript
const starField = new StarField('starField', {
    starCount: 200,        // Number of stars (default: 150)
    maxDistance: 400,       // Max attraction distance (default: 300)
    baseSpeed: 0.02,       // Animation speed (default: 0.015)
    sparkleRatio: 0.15,    // Ratio of sparkle stars (default: 0.1)
    colors: ['white', '#0066CC', '#E8B93E']  // Star colors
});
```

### Background Variants

```html
<!-- Dark blue gradient (default) -->
<div id="starField" class="star-field gradient-blue"></div>

<!-- Purple gradient -->
<div id="starField" class="star-field gradient-purple"></div>

<!-- Dark gradient -->
<div id="starField" class="star-field gradient-dark"></div>

<!-- Light mode -->
<div id="starField" class="star-field light"></div>
```

## Where It's Used

1. **Login Page** (`/login/`) - Full page background
2. **Pledge Page** - Can be added as background
3. **Donate Page** - Can be added as background
4. **Any custom page** - Just include the component!

## Customization

### Change Star Count
```javascript
new StarField('starField', { starCount: 300 });
```

### Change Colors
```javascript
new StarField('starField', { 
    colors: ['#0066CC', '#E8B93E', '#ffffff'] 
});
```

### Adjust Clustering
```javascript
new StarField('starField', { 
    maxDistance: 500,  // Stars cluster from further away
    baseSpeed: 0.03    // Faster movement
});
```

## Performance

- Uses `requestAnimationFrame` for smooth 60fps animation
- CSS `will-change` for GPU acceleration
- Efficient distance calculations
- Auto-cleanup on page unload

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Requires JavaScript enabled
- Works on mobile (touch events)

## Example: Add to Any Page

```html
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/css/star-field.css">
</head>
<body>
    <div id="starField" class="star-field"></div>
    
    <div style="position: relative; z-index: 10;">
        <!-- Your content here -->
    </div>
    
    <script src="/js/star-field.js"></script>
    <script>
        new StarField('starField');
    </script>
</body>
</html>
```

