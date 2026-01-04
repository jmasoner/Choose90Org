# Create simple placeholder icons for Choose90 extension
# Uses .NET System.Drawing to create PNG icons

Add-Type -AssemblyName System.Drawing

$iconSizes = @(16, 48, 128)
$iconsFolder = "browser-extension\icons"

if (-not (Test-Path $iconsFolder)) {
    New-Item -ItemType Directory -Path $iconsFolder -Force | Out-Null
}

foreach ($size in $iconSizes) {
    # Create a bitmap
    $bitmap = New-Object System.Drawing.Bitmap($size, $size)
    $graphics = [System.Drawing.Graphics]::FromImage($bitmap)
    
    # Use high quality rendering
    $graphics.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::AntiAlias
    $graphics.TextRenderingHint = [System.Drawing.Text.TextRenderingHint]::AntiAlias
    
    # Create gradient background (purple to blue - Choose90 colors)
    $brush = New-Object System.Drawing.Drawing2D.LinearGradientBrush(
        [System.Drawing.Point]::new(0, 0),
        [System.Drawing.Point]::new($size, $size),
        [System.Drawing.Color]::FromArgb(102, 126, 234),  # #667eea
        [System.Drawing.Color]::FromArgb(118, 75, 162)   # #764ba2
    )
    $graphics.FillRectangle($brush, 0, 0, $size, $size)
    
    # Add "90" text if size is large enough
    if ($size -ge 48) {
        $fontSize = [Math]::Floor($size * 0.5)
        $font = New-Object System.Drawing.Font("Arial", $fontSize, [System.Drawing.FontStyle]::Bold)
        $textBrush = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::White)
        
        # Center the text
        $text = "90"
        $textSize = $graphics.MeasureString($text, $font)
        $x = ($size - $textSize.Width) / 2
        $y = ($size - $textSize.Height) / 2
        
        $graphics.DrawString($text, $font, $textBrush, $x, $y)
        
        $font.Dispose()
        $textBrush.Dispose()
    }
    
    # Save the icon
    $iconPath = Join-Path $iconsFolder "icon$size.png"
    $bitmap.Save($iconPath, [System.Drawing.Imaging.ImageFormat]::Png)
    
    Write-Host "Created: $iconPath ($size x $size)" -ForegroundColor Green
    
    # Clean up
    $graphics.Dispose()
    $bitmap.Dispose()
    $brush.Dispose()
}

Write-Host "`n✓ All icons created successfully!" -ForegroundColor Green


