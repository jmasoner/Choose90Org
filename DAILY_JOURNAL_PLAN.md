# Choose90 Daily Journal - Implementation Plan

## 🎯 Vision

Create a **Daily Journal** feature that:

- Ties to user accounts (username/password)
- Integrates with the Pledge system
- Remains **free to use** for all members
- Includes **gentle, ethical donation prompts** (weekly)
- Provides real value that encourages voluntary support

---

## 🏗️ System Architecture

### **Phase 1: User Account System**

#### **WordPress User Integration**

Since you're already using WordPress, we'll leverage its built-in user system:

```
User Account Features:
├── Registration (tied to Pledge)
├── Login/Logout
├── Profile Management
├── Password Reset
└── Role-based Access (Member, Donor, Admin)
```

#### **User Roles & Permissions**

| Role | Access | Features |
|------|--------|----------|
| **Guest** | Public pages only | Can view content, take pledge |
| **Member** | All free features | Daily Journal, Resources, Community |
| **Supporter** | Member + Badge | Special "Supporter" badge, recognition |
| **Admin** | Full access | Manage content, users, donations |

---

## 📔 Daily Journal Features

### **Core Functionality**

#### **1. Daily Prompts**

- **Rotating Questions** tied to Choose90 principles
- **7-Day Cycle** aligned with Digital Detox Guide
- **Reflection Categories**:
  - Media Consumption
  - Positive Contributions
  - Gratitude
  - Challenges & Growth
  - Community Impact

#### **2. Journal Entry Interface**

```
Daily Journal Entry:
├── Date (auto-populated)
├── Prompt of the Day
├── Text Area (rich text editor)
├── Mood Tracker (emoji/slider)
├── Media Consumption Log (optional)
├── Positive Actions Counter
├── Tags (custom or suggested)
└── Privacy Settings (Private/Share with Community)
```

#### **3. Journal History**

- **Calendar View** - See all past entries
- **Search & Filter** - Find entries by date, tag, mood
- **Export** - Download as PDF or text file
- **Streaks** - Track consecutive days of journaling
- **Stats Dashboard** - Visualize progress over time

#### **4. Privacy & Security**

- **Private by Default** - Only user can see their entries
- **Optional Sharing** - Share specific entries with community
- **Data Encryption** - Secure storage
- **Data Ownership** - Users can export/delete anytime

---

## 🔗 Integration with Pledge System

### **Unified Registration Flow**

```
User Journey:
1. User visits site → Takes Pledge
2. Prompted to create account (optional but encouraged)
3. Account created → Automatically marked as "Pledged"
4. Welcome email with Daily Journal intro
5. First journal prompt appears
```

### **Pledge Dashboard**

```
My Choose90 Dashboard:
├── Pledge Status (Active, Date Taken)
├── Daily Journal (Quick Entry)
├── My Stats
│   ├── Days Journaling: 45
│   ├── Positive Posts This Week: 12
│   ├── Current Streak: 7 days
│   └── Total Reflections: 156
├── Community Feed (shared entries)
├── Resources (guides, tools)
└── Support Choose90 (donation prompt)
```

---

## 💰 Ethical Donation Strategy

### **Gentle, Value-First Approach**

#### **Donation Prompt Frequency**

- **Weekly Reminder** - Every 7 journal entries
- **Milestone Celebrations** - After 30, 90, 365 days
- **Never Intrusive** - Easy to dismiss, no guilt
- **Value Demonstration** - Show impact before asking

#### **Prompt Design Philosophy**

```
✅ DO:
- Celebrate user's progress first
- Show community impact
- Offer specific amounts ($5, $10, $25, $50, $100)
- Make it optional and easy to skip
- Express genuine gratitude
- Explain where money goes

❌ DON'T:
- Guilt or shame
- Lock features behind paywall
- Interrupt critical workflows
- Ask too frequently
- Use manipulative language
```

### **Sample Donation Prompts**

#### **Weekly Prompt (After 7 Entries)**

```
┌─────────────────────────────────────────────┐
│  🎉 You've journaled for 7 days straight!   │
│                                             │
│  Your commitment to Choose90 is inspiring.  │
│  You've logged 42 positive actions this     │
│  week and are part of a movement of 1,247   │
│  people choosing positivity.                │
│                                             │
│  Choose90 is free for everyone, always.     │
│  But it costs real money to maintain.       │
│                                             │
│  If this journal has added value to your    │
│  life, would you consider supporting it?    │
│                                             │
│  [💚 Donate $5]  [💙 Donate $10]            │
│  [💜 Donate $25] [⭐ Donate $50]            │
│  [🌟 Custom Amount]                         │
│                                             │
│  [Maybe Later] [Don't Ask Again This Month] │
└─────────────────────────────────────────────┘
```

#### **30-Day Milestone**

```
┌─────────────────────────────────────────────┐
│  🏆 30 Days of Choosing 90! 🏆              │
│                                             │
│  You've journaled for 30 consecutive days.  │
│  That's incredible dedication!              │
│                                             │
│  In that time, you've:                      │
│  • Logged 247 positive actions              │
│  • Reflected on 30 days of growth           │
│  • Contributed to a community of hope       │
│                                             │
│  Your $10 donation could help 10 more       │
│  people discover Choose90 this month.       │
│                                             │
│  [Support the Movement]  [Continue Free]    │
└─────────────────────────────────────────────┘
```

#### **Gratitude-Based (Random, Rare)**

```
┌─────────────────────────────────────────────┐
│  💙 A Quick Thank You                       │
│                                             │
│  We're grateful you're part of Choose90.    │
│  Your daily reflections matter.             │
│                                             │
│  If you've found value here and can spare   │
│  even $5, it helps keep the lights on and   │
│  the journal free for everyone.             │
│                                             │
│  [Donate] [Not Right Now]                   │
│                                             │
│  (We won't ask again for 30 days)           │
└─────────────────────────────────────────────┘
```

---

## 🛠️ Technical Implementation

### **Technology Stack**

#### **Option 1: WordPress Plugin Ecosystem** (Recommended)

**Pros**: Integrates with existing site, mature ecosystem, easier maintenance
**Cons**: May need custom development for unique features

```
Core Plugins:
├── User Management: Built-in WordPress Users
├── Journal: Custom Post Type (private posts)
├── Forms: Gravity Forms (already installed)
├── Donations: WooCommerce + Stripe/PayPal
├── Membership: MemberPress or Restrict Content Pro
└── Analytics: MonsterInsights
```

#### **Option 2: Custom Web App** (Advanced)

**Pros**: Full control, modern tech, better UX
**Cons**: More development time, separate maintenance

```
Tech Stack:
├── Frontend: React or Vue.js
├── Backend: Node.js + Express
├── Database: PostgreSQL or MongoDB
├── Auth: Auth0 or Firebase Auth
├── Payments: Stripe API
└── Hosting: Vercel/Netlify + AWS/DigitalOcean
```

### **Recommended: Hybrid Approach**

Use WordPress for:

- User accounts & authentication
- Pledge management
- Donation processing (WooCommerce)
- Content management

Build custom journal as:

- WordPress plugin with custom post type
- React-based frontend for better UX
- REST API for data management

---

## 📊 Database Schema (Simplified)

### **Users Table** (WordPress `wp_users`)

```sql
- ID
- username
- email
- password (hashed)
- registration_date
- pledge_date
- role (member, supporter, admin)
```

### **Journal Entries** (Custom Post Type)

```sql
- entry_id
- user_id (foreign key)
- entry_date
- prompt_id
- content (text)
- mood (1-5 or emoji)
- media_consumption (JSON)
- positive_actions_count
- tags (array)
- is_shared (boolean)
- created_at
- updated_at
```

### **Donation Prompts** (Custom Table)

```sql
- prompt_id
- user_id
- prompt_type (weekly, milestone, gratitude)
- shown_at
- action_taken (donated, dismissed, snoozed)
- amount_donated (if applicable)
```

---

## 🎨 User Interface Design

### **Daily Journal Page**

```
┌─────────────────────────────────────────────────────────┐
│  Choose90 Daily Journal                    [Profile ▼]  │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  📅 Wednesday, December 11, 2025                         │
│  🔥 7-Day Streak!                                        │
│                                                          │
│  ┌────────────────────────────────────────────────────┐ │
│  │  Today's Prompt:                                   │ │
│  │  "What positive action did you take today that     │ │
│  │   you're most proud of?"                           │ │
│  └────────────────────────────────────────────────────┘ │
│                                                          │
│  ┌────────────────────────────────────────────────────┐ │
│  │  [Rich Text Editor Area]                           │ │
│  │                                                     │ │
│  │  I called my mom today just to check in...         │ │
│  │                                                     │ │
│  └────────────────────────────────────────────────────┘ │
│                                                          │
│  How are you feeling today?                             │
│  😢 😕 😐 🙂 😊  [Selected: 😊]                         │
│                                                          │
│  Positive actions today: [3] ▲▼                         │
│                                                          │
│  Tags: #gratitude #family #connection                   │
│                                                          │
│  Privacy: ⦿ Private  ○ Share with Community             │
│                                                          │
│  [Save Entry]  [View Past Entries]                      │
│                                                          │
├─────────────────────────────────────────────────────────┤
│  Quick Stats:                                           │
│  📝 Total Entries: 45  |  🔥 Current Streak: 7 days     │
│  💚 Positive Actions This Week: 23                      │
└─────────────────────────────────────────────────────────┘
```

### **Journal History (Calendar View)**

```
┌─────────────────────────────────────────────────────────┐
│  My Journal History                                     │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  December 2025                          [◀ November]    │
│  ┌──────────────────────────────────────────────────┐  │
│  │ Sun  Mon  Tue  Wed  Thu  Fri  Sat               │  │
│  │  1💚  2💚  3💚  4💚  5💚  6💚  7💚              │  │
│  │  8💚  9💚  10💚 11💚 12   13   14               │  │
│  │  15   16   17   18   19   20   21               │  │
│  │  22   23   24   25   26   27   28               │  │
│  │  29   30   31                                    │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  💚 = Entry completed  |  Click date to view/edit       │
│                                                          │
│  [Export All Entries]  [View Stats]                     │
└─────────────────────────────────────────────────────────┘
```

---

## 🚀 Implementation Phases

### **Phase 1: Foundation (Weeks 1-2)**

- [ ] Set up WordPress user registration
- [ ] Create Pledge form with account creation
- [ ] Design database schema
- [ ] Build basic journal entry form
- [ ] Implement private storage

### **Phase 2: Core Features (Weeks 3-4)**

- [ ] Daily prompts system
- [ ] Journal history/calendar view
- [ ] Mood tracking
- [ ] Search & filter
- [ ] Export functionality

### **Phase 3: Engagement (Weeks 5-6)**

- [ ] Streak tracking
- [ ] Stats dashboard
- [ ] Community sharing (optional)
- [ ] Email reminders
- [ ] Mobile responsiveness

### **Phase 4: Monetization (Week 7)**

- [ ] Donation prompt system
- [ ] WooCommerce integration
- [ ] Stripe/PayPal setup
- [ ] Supporter badges
- [ ] Thank you emails

### **Phase 5: Polish & Launch (Week 8)**

- [ ] User testing
- [ ] Bug fixes
- [ ] Performance optimization
- [ ] Documentation
- [ ] Marketing materials

---

## 💡 Additional Features (Future)

### **Community Features**

- **Shared Reflections** - Public feed of shared entries
- **Encouragement System** - Like/comment on shared entries
- **Accountability Partners** - Connect with other members
- **Group Challenges** - 30-day journaling challenges

### **Gamification**

- **Badges** - Earn for streaks, milestones
- **Leaderboards** - Most positive actions (opt-in)
- **Challenges** - Weekly themed prompts

### **Analytics**

- **Personal Insights** - Mood trends, word clouds
- **Progress Reports** - Monthly summaries
- **Community Impact** - Collective positive actions

### **Premium Features** (Optional)

- **Advanced Analytics** - Deeper insights
- **Custom Prompts** - Create your own
- **Priority Support** - Faster help
- **Ad-Free Experience** - No donation prompts

**Note**: Keep core journal FREE. Premium is optional enhancement.

---

## 📈 Success Metrics

### **Engagement**

- Daily Active Users (DAU)
- Journal entries per user per week
- Average streak length
- Retention rate (30-day, 90-day)

### **Community**

- Total pledges taken
- Shared entries per week
- Community interactions

### **Financial**

- Donation conversion rate
- Average donation amount
- Monthly recurring donors
- Supporter retention

### **Impact**

- Total positive actions logged
- User-reported mood improvements
- Testimonials & success stories

---

## 🎯 Key Principles

1. **Free First** - Never lock core features
2. **Value Before Ask** - Demonstrate impact before requesting support
3. **Gentle Prompts** - Respectful, easy to dismiss
4. **Transparency** - Show where money goes
5. **Gratitude** - Thank supporters genuinely
6. **Privacy** - Protect user data fiercely
7. **Community** - Foster connection, not competition

---

## 💰 Donation Prompt Schedule

### **Frequency Rules**

```javascript
const donationPromptRules = {
  weekly: {
    frequency: 'Every 7 journal entries',
    canDismiss: true,
    snoozeOptions: ['1 week', '1 month', 'Don\'t ask again']
  },
  milestone: {
    triggers: [30, 90, 180, 365], // days
    canDismiss: true,
    showOnce: true // per milestone
  },
  gratitude: {
    frequency: 'Random, max once per 30 days',
    probability: 0.1, // 10% chance on any entry
    canDismiss: true
  },
  supporter: {
    frequency: 'Never',
    note: 'Don\'t show prompts to recent donors (90 days)'
  }
};
```

### **User Preferences**

Allow users to control prompts:

- ✅ Show weekly reminders
- ✅ Show milestone celebrations
- ❌ Show random gratitude prompts
- Set custom snooze duration

---

## 🎨 Donation Page Design

### **Simple, Transparent, Impactful**

```
┌─────────────────────────────────────────────────────────┐
│  💙 Support Choose90                                    │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Choose90 is free for everyone, always.                 │
│  But it costs real money to keep running.               │
│                                                          │
│  Your donation helps:                                   │
│  ✅ Keep the Daily Journal free                         │
│  ✅ Host resources & guides                             │
│  ✅ Build new community features                        │
│  ✅ Support local chapters                              │
│                                                          │
│  ┌────────────────────────────────────────────────────┐ │
│  │  Choose Your Impact:                               │ │
│  │                                                     │ │
│  │  ⦿ $5/month   - Coffee supporter                   │ │
│  │  ○ $10/month  - Community builder                  │ │
│  │  ○ $25/month  - Movement maker                     │ │
│  │  ○ $50/month  - Champion of positivity             │ │
│  │  ○ Custom amount: [____]                           │ │
│  │                                                     │ │
│  │  ○ One-time donation                               │ │
│  └────────────────────────────────────────────────────┘ │
│                                                          │
│  💳 [Donate Securely with Stripe]                       │
│                                                          │
│  100% of donations go to Choose90 operations.           │
│  We're a registered nonprofit. Tax-deductible.          │
│                                                          │
│  [Maybe Later]                                          │
└─────────────────────────────────────────────────────────┘
```

---

## 🔐 Security & Privacy

### **Data Protection**

- HTTPS everywhere
- Password hashing (bcrypt)
- SQL injection prevention
- XSS protection
- CSRF tokens
- Regular backups
- GDPR compliance

### **User Rights**

- Export all data
- Delete account & data
- Control sharing preferences
- Opt out of emails
- Manage donation preferences

---

## 📧 Email Strategy

### **Automated Emails**

1. **Welcome Email** (After registration)
   - Thank you for pledging
   - Introduce Daily Journal
   - First prompt included

2. **Streak Reminders** (If user misses a day)
   - Gentle nudge, not guilt
   - "We missed you yesterday"
   - Link to today's prompt

3. **Milestone Celebrations**
   - 7, 30, 90, 365 days
   - Celebrate progress
   - Share stats
   - Optional donation ask

4. **Monthly Summary**
   - Your stats this month
   - Community impact
   - Featured shared entries

5. **Thank You** (After donation)
   - Genuine gratitude
   - Impact explanation
   - Supporter badge notification

---

## 🎯 Next Steps

### **Immediate Actions**

1. **Review this plan** - Adjust based on your vision
2. **Choose tech stack** - WordPress plugin vs custom app
3. **Design mockups** - Visual design for journal interface
4. **Set up development environment**
5. **Create project timeline**

### **Questions to Decide**

- [ ] WordPress plugin or separate app?
- [ ] Which payment processor? (Stripe, PayPal, both?)
- [ ] Nonprofit status? (affects tax-deductibility)
- [ ] Email service? (Mailchimp, SendGrid, ConvertKit?)
- [ ] Analytics platform? (Google Analytics, Mixpanel?)

---

## 💬 Let's Discuss

I'd love to hear your thoughts on:

1. **Tech approach** - WordPress plugin or custom app?
2. **Donation amounts** - Are $5-$100 the right tiers?
3. **Prompt frequency** - Weekly too often? Too rare?
4. **Premium features** - Should we offer any paid upgrades?
5. **Timeline** - When do you want to launch?

This is your vision. Let's refine it together! 🚀
