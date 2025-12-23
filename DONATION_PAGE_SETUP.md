# Donation Page Setup Guide

## Current Status
- ✅ Links point to `/donate/` (correct)
- ✅ Donation form component exists (`components/donation-form.html`)
- ⚠️ Need to create WordPress page and template
- ⚠️ Need to configure WooCommerce

## Step 1: Create WordPress Donate Page

1. **Go to WordPress Admin:** https://choose90.org/wp-admin
2. **Navigate to:** Pages → Add New
3. **Create the page:**
   - Title: **"Donate"**
   - Permalink: Should be `/donate/` (WordPress sets this automatically)
   - Template: Select "Donate Page" (if template exists) or "Default Template"
   - Click **"Publish"**

## Step 2: Create Donate Page Template (If Needed)

If you don't have a `page-donate.php` template, you'll need to create one. The template should:
- Include the donation form component
- Match your site's design
- Integrate with WooCommerce

## Step 3: Set Up WooCommerce

### A. Install & Activate WooCommerce
1. Go to **Plugins → Installed Plugins**
2. Find **WooCommerce** (should already be installed per CONTEXT.md)
3. **Activate** if not already active

### B. Run WooCommerce Setup Wizard
1. After activation, you'll see a setup wizard
2. Follow the prompts to:
   - Set store location
   - Configure currency (USD)
   - Set payment methods
   - Choose shipping (if needed - probably not for donations)

### C. Configure Payment Gateways
1. Go to **WooCommerce → Settings → Payments**
2. **Enable Stripe:**
   - Install "WooCommerce Stripe Payment Gateway" plugin
   - Enter your Stripe API keys (from `secrets.json`)
   - Enable "Stripe" payment method
3. **Enable PayPal (Optional):**
   - Install "WooCommerce PayPal Payments" plugin
   - Enter PayPal credentials
   - Enable PayPal payment method

## Step 4: Create Donation Products

### A. Recurring Donation Products (WooCommerce Subscriptions)

1. **Install WooCommerce Subscriptions Plugin:**
   - Go to Plugins → Add New
   - Search "WooCommerce Subscriptions"
   - Install and activate

2. **Create Subscription Products:**
   - Go to **Products → Add New**
   - Create 4 products:
     - **Coffee Supporter** - $5/month (recurring)
     - **Community Builder** - $10/month (recurring)
     - **Movement Maker** - $25/month (recurring)
     - **Champion of Positivity** - $50/month (recurring)
   - For each product:
     - Set Product Type: **Subscription**
     - Set Price: Monthly amount
     - Set Billing Period: Monthly
     - Set Product Category: "Donations"

### B. One-Time Donation Products

1. **Create Simple Products:**
   - Go to **Products → Add New**
   - Create products for:
     - $5 one-time donation
     - $10 one-time donation
     - $25 one-time donation
     - $50 one-time donation
     - $100 one-time donation
   - For each:
     - Set Product Type: **Simple Product**
     - Set Price: Donation amount
     - Set Product Category: "Donations"
     - Enable "Allow customers to enter a custom amount" (if available)

## Step 5: Create Donation Form Template

Create `page-donate.php` in your child theme with the donation form component.

## Step 6: Test

1. Visit: https://choose90.org/donate/
2. Test both recurring and one-time donations
3. Verify payment processing works
4. Check that thank you emails are sent

## Quick Start (Minimal Setup)

If you want to get it working quickly:

1. **Create WordPress Page:**
   - Pages → Add New → Title: "Donate" → Publish

2. **Use WooCommerce Product Shortcode:**
   - Edit the Donate page
   - Add shortcode: `[products category="donations"]`
   - Or create a simple product and use: `[product id="123"]`

3. **Configure Payment:**
   - WooCommerce → Settings → Payments
   - Enable at least one payment method (Stripe recommended)

## Troubleshooting

### "Page Not Found" Error
- Check that the WordPress page exists and is published
- Verify permalink is set to `/donate/`
- Go to Settings → Permalinks → Save (refreshes rewrite rules)

### Payment Not Processing
- Check payment gateway is enabled
- Verify API keys are correct
- Check WooCommerce logs: WooCommerce → Status → Logs

### Products Not Showing
- Verify products are published
- Check product categories match
- Clear WordPress cache

## Next Steps (Advanced)

- [ ] Set up WooCommerce Subscriptions for recurring donations
- [ ] Create custom donation form with amount selection
- [ ] Integrate badge system (award badges on donation)
- [ ] Set up thank you emails
- [ ] Add impact messaging ("Your $10 helps 50 people")

