# Choose90 Counter Auto-Increment Setup

## Overview
The counter automatically increments to show growth:
- **13 per hour** between 8am-8pm (daytime hours)
- **8 per hour** between 8pm-8am (evening/night hours)
- This runs **in addition to** real subscriptions

## Files Created
- `hybrid_site/api/increment-counter.php` - Script that increments the counter based on time of day

## Setup Instructions

### Option 1: Cron Job (Linux/Unix Server)
Add this to your crontab to run every hour:

```bash
# Run counter increment every hour at minute 0
0 * * * * /usr/bin/php /path/to/your/site/hybrid_site/api/increment-counter.php >/dev/null 2>&1
```

Or using `wget`/`curl`:
```bash
# Run counter increment every hour at minute 0
0 * * * * /usr/bin/wget -q -O - https://choose90.org/api/increment-counter.php?cron=1 >/dev/null 2>&1
```

Or using `curl`:
```bash
# Run counter increment every hour at minute 0
0 * * * * /usr/bin/curl -s https://choose90.org/api/increment-counter.php?cron=1 >/dev/null 2>&1
```

### Option 2: Windows Task Scheduler
1. Open Task Scheduler
2. Create Basic Task
3. Name: "Choose90 Counter Increment"
4. Trigger: Daily, Repeat every 1 hour, for a duration of: Indefinitely
5. Action: Start a program
6. Program: `C:\path\to\php.exe`
7. Arguments: `C:\path\to\hybrid_site\api\increment-counter.php`
8. Start in: `C:\path\to\hybrid_site\api\`

### Option 3: cPanel Cron Jobs
1. Log into cPanel
2. Go to "Cron Jobs"
3. Add new cron job:
   - Minute: `0`
   - Hour: `*`
   - Day: `*`
   - Month: `*`
   - Weekday: `*`
   - Command: `/usr/bin/php /home/username/public_html/hybrid_site/api/increment-counter.php`
   - Or: `/usr/bin/wget -q -O - https://choose90.org/api/increment-counter.php?cron=1`

### Option 4: WordPress Cron (wp-cron)
Add to your theme's `functions.php`:

```php
// Schedule hourly counter increment
if (!wp_next_scheduled('choose90_increment_counter')) {
    wp_schedule_event(time(), 'hourly', 'choose90_increment_counter');
}

add_action('choose90_increment_counter', function() {
    // Use wp_remote_get to call the increment script
    wp_remote_get(home_url('/api/increment-counter.php?cron=1'), [
        'timeout' => 5,
        'blocking' => false
    ]);
});
```

## Testing

### Test the increment script:
Visit in browser or run via command line:
```bash
php hybrid_site/api/increment-counter.php
```

Or visit:
```
https://choose90.org/api/increment-counter.php?cron=1
```

### Check the log:
View the increment log at:
```
hybrid_site/data/counter-increments.log
```

### Verify counter file:
Check `pledge-count.txt` to see the updated count.

## Calculation Example

**Daily Growth:**
- Daytime (8am-8pm): 12 hours × 13 = **156 per day**
- Nighttime (8pm-8am): 12 hours × 8 = **96 per day**
- **Total: 252 per day** (plus real subscriptions)

**Weekly Growth:**
- 252 × 7 = **1,764 per week** (plus real subscriptions)

**Monthly Growth:**
- 252 × 30 = **7,560 per month** (plus real subscriptions)

This creates a sense of momentum and FOMO (fear of missing out) to encourage people to join.

## Notes
- The script reads from `pledge-count.txt`
- It maintains a minimum base count of 3,363
- Real subscriptions are still added on top of this
- The increment is logged for tracking purposes
- The script is idempotent (safe to run multiple times per hour, though cron should only run it once)
