# Choose90 Donation System

## Donation Options

### Recurring Donations (Pre-selected, Highlighted)
- **Default:** Recurring checkbox is CHECKED
- **Options:**
  - $5/month - "Coffee Supporter"
  - $10/month - "Community Builder"
  - $25/month - "Movement Maker"
  - $50/month - "Champion of Positivity"
- **Highlight:** Blue border, larger text, "Recommended" badge
- **Benefits:** Predictable revenue, supporter badges

### One-Time Donations
- **Minimum:** $5
- **Options:**
  - Quick amounts: $5, $10, $25, $50, $100
  - Custom amount: User can enter any amount $5+
- **Highlight:** Standard styling, "One-Time" label
- **Benefits:** Flexibility, no commitment

## UI Design

```
┌─────────────────────────────────────────┐
│  💙 Support Choose90                    │
├─────────────────────────────────────────┤
│                                         │
│  Choose90 is free for everyone, always. │
│  But it costs real money to keep        │
│  running.                               │
│                                         │
│  ┌───────────────────────────────────┐ │
│  │ ⦿ RECURRING (Recommended)         │ │ ← Pre-checked, highlighted
│  │                                   │ │
│  │ ○ $5/month  - Coffee Supporter    │ │
│  │ ○ $10/month - Community Builder   │ │
│  │ ○ $25/month - Movement Maker      │ │
│  │ ○ $50/month - Champion            │ │
│  └───────────────────────────────────┘ │
│                                         │
│  ┌───────────────────────────────────┐ │
│  │ ○ ONE-TIME                        │ │
│  │                                   │ │
│  │ [ $5 ] [ $10 ] [ $25 ] [ $50 ]    │ │
│  │ [ $100 ] [ Custom: $____ ]        │ │
│  └───────────────────────────────────┘ │
│                                         │
│  [ Donate Securely ]                    │
│                                         │
│  100% of donations go to Choose90      │
│  operations. Tax-deductible.            │
└─────────────────────────────────────────┘
```

## Implementation

- WooCommerce Subscriptions for recurring
- WooCommerce Products for one-time
- Stripe/PayPal integration
- Automatic badge assignment
- Thank you email with badge notification
- Impact messaging: "Your $10/month helps 50 people"

## Donation Prompts

Show after:
- Completing 3rd resource
- Earning a badge
- Completing a challenge
- Monthly "impact report" email

