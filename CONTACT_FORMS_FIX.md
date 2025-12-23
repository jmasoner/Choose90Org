# Contact Forms Fix

## Problem
Contact forms were not working - they were using `mailto:` links instead of actual forms.

## Solution

### 1. Chapter Contact Forms ✓
Created `hybrid_site/components/chapter-contact-form.php`:
- Replaces mailto links with actual contact forms
- Two forms: "Contact Host" and "Join Chapter"
- Validates input, sends email to chapters@choose90.org
- Shows success/error messages
- Falls back to mailto if form component not found

### 2. Host Starter Kit Form
The form in `page-host-starter-kit.php` should work - it uses PHP POST submission. If it's not working, check:
- WordPress email configuration (wp_mail)
- SMTP plugin settings (GoSMTP Pro)
- Server email capabilities

## Deployment

### Step 1: Deploy Contact Form Component
```powershell
.\setup_child_theme.ps1
```

This will deploy:
- `single-chapter.php` (updated to use contact form)
- `chapter-contact-form.php` (new component)

### Step 2: Verify File Locations
The contact form component should be in:
- Theme directory: `Z:\wp-content\themes\Divi-choose90\components\chapter-contact-form.php`
- OR: `Z:\wp-content\themes\Divi-choose90\../hybrid_site/components/chapter-contact-form.php`

### Step 3: Test
1. Visit a chapter page: https://choose90.org/chapter/[chapter-name]/
2. Scroll to "Contact Host" and "Join Chapter" sections
3. Fill out and submit the form
4. Check that email is received at chapters@choose90.org

## Troubleshooting

### Form Not Showing
- Check that `chapter-contact-form.php` is in the correct location
- Verify the include path in `single-chapter.php` is correct
- Check WordPress error logs

### Form Submits But No Email
- Check WordPress email settings
- Verify SMTP plugin (GoSMTP Pro) is configured
- Check server email logs
- Test wp_mail() function

### Form Shows Error
- Check nonce verification
- Verify form fields match POST data
- Check PHP error logs

## Email Configuration

Make sure WordPress can send emails:
1. **Via SMTP Plugin (Recommended):**
   - Go to WordPress Admin → GoSMTP Pro
   - Configure SMTP settings
   - Test email sending

2. **Via Server:**
   - Contact hosting provider to enable PHP mail
   - Or configure server's sendmail/postfix

## Next Steps

- [ ] Deploy contact form component
- [ ] Test form submission
- [ ] Verify emails are received
- [ ] Add spam protection (reCAPTCHA) if needed
- [ ] Create contact form for support page (if needed)

