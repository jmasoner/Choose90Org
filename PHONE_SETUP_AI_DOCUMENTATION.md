# Phone Setup AI Integration Documentation

## Overview

The Phone Setup Optimizer now includes AI-powered, device-specific instructions using DeepSeek API. Users can input their phone brand, model, and software version to receive personalized step-by-step instructions for each setup task.

## Files Created

### 1. `hybrid_site/api/phone-setup-ai.php`
- **Purpose**: PHP endpoint that handles AI requests
- **Security**: Should be protected and rate-limited in production
- **Features**:
  - Validates device information
  - Calls DeepSeek API with device-specific prompts
  - Returns formatted instructions
  - Handles errors gracefully
  - Uses proper SSL verification (with fallback for development)

### 2. `hybrid_site/js/phone-setup-ai.js`
- **Purpose**: Client-side JavaScript for interactive AI integration
- **Features**:
  - Adds "Get AI-Powered Instructions" buttons to each step
  - Collects device information via modal
  - Makes AJAX requests to API endpoint
  - Displays AI instructions in branded format
  - Saves device info to localStorage for future use
  - Handles loading states and errors

### 3. `hybrid_site/resources/phone-setup-optimizer.html`
- **Updated**: Added AI integration styles and script
- **Features**:
  - AI buttons on each step box
  - Modal for device information collection
  - Styled AI instruction display
  - Loading and error states

### 4. `hybrid_site/test-api-keys-production.php`
- **Purpose**: Production-ready API test script with proper SSL verification
- **Features**:
  - Proper SSL certificate verification
  - Enhanced security headers
  - Password protection
  - CA certificate bundle detection
  - Clear error messages

## How It Works

### User Flow

1. **User visits Phone Setup Optimizer page**
   - Sees general instructions for iPhone and Android

2. **User clicks "🤖 Get AI-Powered Instructions" button**
   - If device info not collected: Modal appears asking for:
     - Phone Brand (dropdown)
     - Phone Model (text input)
     - Software Version (optional text input)
   - If device info already collected: Directly requests AI instructions

3. **AI generates personalized instructions**
   - API sends device info + task to DeepSeek
   - DeepSeek returns step-by-step instructions specific to that device
   - Instructions are displayed in a branded green box

4. **User can use instructions**
   - Instructions are formatted clearly
   - User can close the AI instructions box
   - Device info is saved to localStorage for future visits

### Technical Flow

```
User clicks button
    ↓
JavaScript checks for device info
    ↓
If missing: Show modal → Collect info → Save to localStorage
    ↓
POST request to /api/phone-setup-ai.php
    ↓
PHP validates input and loads secrets.json
    ↓
PHP builds AI prompt with device info + task
    ↓
PHP calls DeepSeek API with proper SSL
    ↓
DeepSeek returns instructions
    ↓
PHP formats and returns JSON
    ↓
JavaScript displays instructions in branded box
```

## API Endpoint Details

### Request Format
```json
{
  "brand": "Apple",
  "model": "iPhone 15 Pro",
  "software_version": "iOS 17.2",
  "step": "1",
  "task": "Step 1: Enable Screen Time & Set Limits"
}
```

### Response Format (Success)
```json
{
  "success": true,
  "step": "1",
  "task": "Step 1: Enable Screen Time & Set Limits",
  "instructions": "1. Go to Settings → Screen Time...",
  "device": {
    "brand": "Apple",
    "model": "iPhone 15 Pro",
    "software_version": "iOS 17.2"
  }
}
```

### Response Format (Error)
```json
{
  "error": "Missing required fields: brand, model, step, task"
}
```

## Configuration

### Required: `secrets.json`
```json
{
  "api_keys": {
    "deepseek": {
      "api_key": "sk-your-key-here",
      "base_url": "https://api.deepseek.com/v1",
      "model": "deepseek-chat",
      "enabled": true
    }
  }
}
```

## Security Considerations

1. **API Endpoint Protection**
   - Add rate limiting (e.g., max 10 requests per IP per hour)
   - Consider adding authentication token
   - Log all requests for monitoring

2. **Input Validation**
   - Currently validates required fields
   - Consider adding length limits
   - Sanitize all user input

3. **SSL Verification**
   - Production script uses proper SSL verification
   - Falls back to non-verified for development if needed
   - Download `cacert.pem` for production servers

4. **Error Handling**
   - Never expose API keys in error messages
   - Log errors server-side
   - Show user-friendly error messages

## Testing

### Test API Keys
```bash
# Production test (with proper SSL)
php hybrid_site/test-api-keys-production.php

# Or visit in browser:
https://choose90.org/test-api-keys-production.php?password=YOUR_PASSWORD
```

### Test AI Integration
1. Open `phone-setup-optimizer.html` in browser
2. Click "Get AI-Powered Instructions" on any step
3. Enter device information
4. Verify instructions appear correctly

## Deployment

1. **Deploy API endpoint:**
   ```powershell
   # Ensure api/phone-setup-ai.php is deployed
   .\deploy_hybrid_site.ps1
   ```

2. **Deploy JavaScript:**
   ```powershell
   # Ensure js/phone-setup-ai.js is deployed
   .\deploy_hybrid_site.ps1
   ```

3. **Deploy updated HTML:**
   ```powershell
   # Ensure resources/phone-setup-optimizer.html is deployed
   .\deploy_resources.ps1
   ```

4. **Test on live server:**
   - Visit: `https://choose90.org/resources/phone-setup-optimizer.html`
   - Test AI functionality
   - Verify SSL works correctly

## Troubleshooting

### "API connection failed"
- Check `secrets.json` exists and is valid
- Verify API key is correct
- Check network connectivity
- Review server error logs

### "SSL certificate problem"
- Download `cacert.pem` from https://curl.se/ca/cacert.pem
- Place in project root
- Update `phone-setup-ai.php` if path differs

### "Invalid API response"
- Check DeepSeek API status
- Verify model name is correct
- Check API quota/limits

### Instructions not appearing
- Check browser console for JavaScript errors
- Verify API endpoint path is correct
- Check network tab for failed requests

## Future Enhancements

1. **Caching**: Cache common device instructions to reduce API calls
2. **Rate Limiting**: Implement client-side and server-side rate limiting
3. **Analytics**: Track which steps users request most
4. **Feedback**: Allow users to rate instruction quality
5. **Offline Mode**: Cache last instructions for offline viewing
6. **Multi-language**: Support instructions in multiple languages

## Support

For issues or questions:
- Check server error logs
- Review browser console
- Test API keys separately
- Verify `secrets.json` structure

