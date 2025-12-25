<?php
/**
 * Choose90 Dashboard Widget
 * Shows signups and donations statistics
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add dashboard widget
function choose90_add_dashboard_widget() {
    wp_add_dashboard_widget(
        'choose90_stats_widget',
        'Choose90 Statistics',
        'choose90_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'choose90_add_dashboard_widget');

// Dashboard widget content
function choose90_dashboard_widget_content() {
    // Get statistics
    $stats = choose90_get_statistics();
    
    ?>
    <div style="padding: 10px 0;">
        <style>
            .choose90-stats-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
                margin-bottom: 20px;
            }
            .choose90-stat-card {
                background: #f9fafb;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                padding: 15px;
                border-left: 4px solid #0066CC;
            }
            .choose90-stat-card.donations {
                border-left-color: #E8B93E;
            }
            .choose90-stat-label {
                font-size: 12px;
                color: #6b7280;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 5px;
            }
            .choose90-stat-value {
                font-size: 28px;
                font-weight: 700;
                color: #0066CC;
                line-height: 1.2;
            }
            .choose90-stat-value.donations {
                color: #E8B93E;
            }
            .choose90-stat-change {
                font-size: 11px;
                color: #10b981;
                margin-top: 5px;
            }
            .choose90-stat-change.negative {
                color: #ef4444;
            }
        </style>
        
        <div class="choose90-stats-grid">
            <!-- Total Signups -->
            <div class="choose90-stat-card">
                <div class="choose90-stat-label">Total Signups</div>
                <div class="choose90-stat-value"><?php echo number_format($stats['total_signups']); ?></div>
                <div class="choose90-stat-change">
                    <?php echo number_format($stats['signups_today']); ?> new today
                </div>
            </div>
            
            <!-- Signups Today -->
            <div class="choose90-stat-card">
                <div class="choose90-stat-label">New Signups Today</div>
                <div class="choose90-stat-value"><?php echo number_format($stats['signups_today']); ?></div>
                <div class="choose90-stat-change">
                    <?php 
                    if ($stats['signups_yesterday'] > 0) {
                        $change = (($stats['signups_today'] - $stats['signups_yesterday']) / $stats['signups_yesterday']) * 100;
                        echo ($change >= 0 ? '+' : '') . number_format($change, 1) . '% vs yesterday';
                    } else {
                        echo 'First signups!';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Total Donations (Quantity) -->
            <div class="choose90-stat-card donations">
                <div class="choose90-stat-label">Total Donations</div>
                <div class="choose90-stat-value donations"><?php echo number_format($stats['total_donations_count']); ?></div>
                <div class="choose90-stat-change">
                    <?php echo number_format($stats['donations_today_count']); ?> today
                </div>
            </div>
            
            <!-- Total Donations (Dollars) -->
            <div class="choose90-stat-card donations">
                <div class="choose90-stat-label">Total Donations ($)</div>
                <div class="choose90-stat-value donations">$<?php echo number_format($stats['total_donations_amount'], 2); ?></div>
                <div class="choose90-stat-change">
                    $<?php echo number_format($stats['donations_today_amount'], 2); ?> today
                </div>
            </div>
        </div>
        
        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
            <a href="<?php echo admin_url('users.php'); ?>" style="color: #0066CC; text-decoration: none; margin-right: 15px;">
                View All Users →
            </a>
            <?php if (class_exists('WooCommerce')): ?>
                <a href="<?php echo admin_url('edit.php?post_type=shop_order'); ?>" style="color: #E8B93E; text-decoration: none;">
                    View Donations →
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

// Get statistics
function choose90_get_statistics() {
    global $wpdb;
    
    $stats = array();
    
    // Total signups (WordPress users with pledge_date meta)
    $total_signups = $wpdb->get_var("
        SELECT COUNT(DISTINCT u.ID)
        FROM {$wpdb->users} u
        INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
        WHERE um.meta_key = 'pledge_date'
    ");
    $stats['total_signups'] = $total_signups ? intval($total_signups) : 0;
    
    // Signups today
    $today_start = date('Y-m-d 00:00:00');
    $signups_today = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(DISTINCT u.ID)
        FROM {$wpdb->users} u
        INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
        WHERE um.meta_key = 'pledge_date'
        AND um.meta_value >= %s
    ", $today_start));
    $stats['signups_today'] = $signups_today ? intval($signups_today) : 0;
    
    // Signups yesterday (for comparison)
    $yesterday_start = date('Y-m-d 00:00:00', strtotime('-1 day'));
    $yesterday_end = date('Y-m-d 23:59:59', strtotime('-1 day'));
    $signups_yesterday = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(DISTINCT u.ID)
        FROM {$wpdb->users} u
        INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
        WHERE um.meta_key = 'pledge_date'
        AND um.meta_value >= %s
        AND um.meta_value <= %s
    ", $yesterday_start, $yesterday_end));
    $stats['signups_yesterday'] = $signups_yesterday ? intval($signups_yesterday) : 0;
    
    // Donations (WooCommerce orders)
    if (class_exists('WooCommerce')) {
        // Total donations count
        $total_donations = $wpdb->get_var("
            SELECT COUNT(*)
            FROM {$wpdb->posts} p
            WHERE p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
        ");
        $stats['total_donations_count'] = $total_donations ? intval($total_donations) : 0;
        
        // Total donations amount
        $total_amount = $wpdb->get_var("
            SELECT SUM(CAST(pm.meta_value AS DECIMAL(10,2)))
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
            AND pm.meta_key = '_order_total'
        ");
        $stats['total_donations_amount'] = $total_amount ? floatval($total_amount) : 0;
        
        // Donations today count
        $donations_today = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*)
            FROM {$wpdb->posts} p
            WHERE p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
            AND DATE(p.post_date) = %s
        ", date('Y-m-d')));
        $stats['donations_today_count'] = $donations_today ? intval($donations_today) : 0;
        
        // Donations today amount
        $donations_today_amount = $wpdb->get_var($wpdb->prepare("
            SELECT SUM(CAST(pm.meta_value AS DECIMAL(10,2)))
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
            AND pm.meta_key = '_order_total'
            AND DATE(p.post_date) = %s
        ", date('Y-m-d')));
        $stats['donations_today_amount'] = $donations_today_amount ? floatval($donations_today_amount) : 0;
    } else {
        // WooCommerce not active
        $stats['total_donations_count'] = 0;
        $stats['total_donations_amount'] = 0;
        $stats['donations_today_count'] = 0;
        $stats['donations_today_amount'] = 0;
    }
    
    return $stats;
}



