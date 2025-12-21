# Chapters Email Updates

## Changes Made

### Email Centralization Strategy
- **Primary Contact:** `chapters@choose90.org`
- **Distribution Lists:** Will route to appropriate chapter leaders
- **Support:** `support@choose90.org` (general support inquiries)

### Files Updated

1. **`hybrid_site/single-chapter.php`**
   - Contact Host button: `mailto:chapters@choose90.org`
   - Join Chapter button: `mailto:chapters@choose90.org`
   - Both use subject line with chapter name for routing

2. **`hybrid_site/page-host-starter-kit.php`**
   - Host application notifications sent to: `chapters@choose90.org`
   - Email includes host email for distribution list assignment
   - Still collects individual host email (for distribution list)

3. **`hybrid_site/host-starter-kit.html`** (static version)
   - Mailto link updated to: `chapters@choose90.org`

4. **`hybrid_site/support.html`**
   - Support email updated to: `support@choose90.org`
   - Keeps general support separate from chapter-specific emails

## Email Routing Strategy (For CRM Implementation)

### Distribution List Pattern
- **General inquiries:** `chapters@choose90.org`
- **Chapter-specific (future):** `chapters+austin@choose90.org` → Austin distribution list
- **Join requests:** Subject line "Join Chapter: [Chapter Name]" for routing
- **Host applications:** Subject line "New Chapter Application: [Location]"

### Next Steps (CRM Phase)
1. Set up email forwarding rules
2. Create distribution lists in email system
3. Implement email capture/parsing
4. Build routing logic based on subject/recipient

## Notes

- Individual host emails are still collected (needed for distribution lists)
- All public-facing contact uses centralized `chapters@choose90.org`
- Support emails use separate `support@choose90.org` address
- Email routing will be handled by CRM system (Phase 1-2)

