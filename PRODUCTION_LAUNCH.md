# Choose90.org Production Launch - Beta Testing

**Launch Date:** December 2025  
**Status:** ✅ **BETA TESTING NOW OPEN**

## 🎉 We're Live!

Choose90.org is now open for beta testing! After months of development, we're excited to share our platform with the community.

## What's Live

### Core Features
✅ **Phone Setup Optimizer** - AI-powered, device-specific instructions  
✅ **Resources Hub** - 9+ downloadable guides and toolkits  
✅ **Pledge Form** - User registration and commitment  
✅ **Chapters Directory** - Find or start local chapters  
✅ **Mobile Responsive** - Works on all devices  

### Production Security
✅ **Rate Limiting** - API endpoints protected (20 requests/hour per IP)  
✅ **CORS Protection** - Restricted to choose90.org domains  
✅ **Security Headers** - XSS, clickjacking, and MIME type protection  
✅ **Input Sanitization** - All user inputs validated and sanitized  
✅ **SSL Verification** - Proper certificate handling  

## Beta Testing

### For Testers
- **Landing Page:** https://choose90.org/beta-testing.html
- **Guide:** See `BETA_TESTING_GUIDE.md`
- **Feedback:** feedback@choose90.org

### What We're Testing
1. Phone Setup Optimizer with AI integration
2. Resources download and usability
3. Pledge form functionality
4. Chapter directory navigation
5. Overall site performance and user experience

## Deployment

### Production Deployment
- **Script:** `deploy_production.ps1`
- **Automatically excludes:** Test files (test-api-keys.php, etc.)
- **Deployed to:** Z:\ (WebDAV)

### Files Deployed
- All HTML/PHP pages
- CSS and JavaScript
- API endpoints
- Resources (guides, toolkits)
- Production security configuration

### Files Excluded (Test Only)
- `test-api-keys.php`
- `test-api-keys-production.php`

## Security Features

### .htaccess Configuration
- Security headers (X-Frame-Options, X-Content-Type-Options, etc.)
- Content Security Policy
- File protection (secrets.json, .env)
- Compression and caching

### API Security
- Rate limiting: 20 requests/hour per IP
- CORS: Restricted to choose90.org domains only
- Input validation and sanitization
- SSL certificate verification
- Error handling (no sensitive data exposed)

## Monitoring

### What to Watch
- API rate limit hits
- Error logs
- User feedback
- Performance metrics
- Security alerts (GitGuardian)

## Coming Soon

### Phase 1 (In Development)
- Badge system for accomplishments
- Social sharing for pledges
- Donation system (one-time and recurring)

### Phase 2 (Planned)
- Daily Journal feature
- SMS reminders (90+ days out)
- Enhanced personalization

## Quick Links

- **Homepage:** https://choose90.org
- **Beta Testing:** https://choose90.org/beta-testing.html
- **Pledge:** https://choose90.org/page-pledge.php
- **Resources:** https://choose90.org/resources-hub.html
- **Chapters:** https://choose90.org/page-chapters.php

## Support

- **Feedback:** feedback@choose90.org
- **Support:** https://choose90.org/support.html
- **Issues:** Check error logs and GitGuardian alerts

## Next Steps

1. ✅ Monitor beta testing feedback
2. ✅ Address reported issues
3. ✅ Gather user insights
4. ⏳ Plan Phase 1 feature rollout
5. ⏳ Transition from beta to full launch

---

**Thank you to all beta testers!** Your feedback is helping us build a better platform for positive change. ☀️

