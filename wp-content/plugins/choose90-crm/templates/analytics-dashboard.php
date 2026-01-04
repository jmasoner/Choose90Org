<?php
/**
 * Analytics Dashboard Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap choose90-analytics-dashboard">
    <h1><?php _e('Analytics Dashboard', 'choose90-crm'); ?></h1>
    
    <div class="analytics-header">
        <div class="refresh-controls">
            <button id="refresh-analytics" class="button button-primary">
                <span class="dashicons dashicons-update"></span> Refresh Data
            </button>
            <span class="last-updated">Last updated: <span id="last-updated-time">Just now</span></span>
        </div>
        <div class="settings-link">
            <a href="<?php echo admin_url('admin.php?page=choose90-crm-analytics-settings'); ?>" class="button">
                <span class="dashicons dashicons-admin-settings"></span> Settings
            </a>
        </div>
    </div>
    
    <!-- Overview Cards -->
    <div class="analytics-section">
        <h2><?php _e('Overview', 'choose90-crm'); ?></h2>
        <div class="overview-cards">
            <div class="metric-card">
                <div class="metric-icon">👥</div>
                <div class="metric-content">
                    <div class="metric-value" id="visitors-today"><?php echo number_format($overview_data['total_visitors_today'] ?? 0); ?></div>
                    <div class="metric-label">Visitors Today</div>
                    <div class="metric-change">
                        <span class="change-value" id="visitors-change">+0%</span> vs last week
                    </div>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-icon">✍️</div>
                <div class="metric-content">
                    <div class="metric-value" id="pledges-week"><?php echo number_format($pledge_data['pledges_last_7_days'] ?? 0); ?></div>
                    <div class="metric-label">Pledges (7 days)</div>
                    <div class="metric-change">
                        <span class="change-value" id="pledges-change">+0%</span> vs previous week
                    </div>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-icon">📧</div>
                <div class="metric-content">
                    <div class="metric-value" id="newsletter-week"><?php echo number_format($newsletter_data['signups_last_7_days'] ?? 0); ?></div>
                    <div class="metric-label">Newsletter Signups (7 days)</div>
                    <div class="metric-change">
                        <span class="change-value" id="newsletter-change">+0%</span> vs previous week
                    </div>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-icon">📊</div>
                <div class="metric-content">
                    <div class="metric-value" id="bounce-rate"><?php echo number_format($overview_data['bounce_rate'] ?? 0, 1); ?>%</div>
                    <div class="metric-label">Bounce Rate</div>
                    <div class="metric-change">
                        <span class="change-value" id="bounce-change">0%</span> vs last week
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pledge Analytics -->
    <div class="analytics-section">
        <h2><?php _e('Pledge Analytics', 'choose90-crm'); ?></h2>
        <div class="analytics-grid">
            <div class="analytics-card">
                <h3>Pledge Conversions</h3>
                <div class="chart-container">
                    <canvas id="pledge-trend-chart"></canvas>
                </div>
            </div>
            
            <div class="analytics-card">
                <h3>Conversion Sources</h3>
                <div class="chart-container">
                    <canvas id="pledge-sources-chart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="analytics-card full-width">
            <h3>Pledge Statistics</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value"><?php echo number_format($pledge_data['total_pledges'] ?? 0); ?></div>
                    <div class="stat-label">Total Pledges</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?php echo number_format($pledge_data['pledges_last_30_days'] ?? 0); ?></div>
                    <div class="stat-label">Last 30 Days</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?php echo number_format($pledge_data['conversion_rate'] ?? 0, 2); ?>%</div>
                    <div class="stat-label">Conversion Rate</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Social Media Performance -->
    <div class="analytics-section">
        <h2><?php _e('Social Media Performance', 'choose90-crm'); ?></h2>
        <div class="analytics-grid">
            <div class="analytics-card">
                <h3>Platform Breakdown</h3>
                <div class="chart-container">
                    <canvas id="social-platform-chart"></canvas>
                </div>
            </div>
            
            <div class="analytics-card">
                <h3>Hashtag Performance</h3>
                <div class="hashtag-list" id="hashtag-performance">
                    <!-- Populated by JavaScript -->
                </div>
            </div>
        </div>
        
        <div class="analytics-card full-width">
            <h3>Social Engagement Trend</h3>
            <div class="chart-container">
                <canvas id="social-trend-chart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Resource Engagement -->
    <div class="analytics-section">
        <h2><?php _e('Resource Engagement', 'choose90-crm'); ?></h2>
        <div class="analytics-grid">
            <div class="analytics-card">
                <h3>Most Popular Resources</h3>
                <div class="resource-list" id="popular-resources">
                    <!-- Populated by JavaScript -->
                </div>
            </div>
            
            <div class="analytics-card">
                <h3>Category Performance</h3>
                <div class="chart-container">
                    <canvas id="category-performance-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tool Usage -->
    <div class="analytics-section">
        <h2><?php _e('Tool Usage', 'choose90-crm'); ?></h2>
        <div class="analytics-grid">
            <div class="analytics-card">
                <h3>Content Generator Usage</h3>
                <div class="stat-value-large"><?php echo number_format($tool_data['content_generator_uses'] ?? 0); ?></div>
                <div class="chart-container">
                    <canvas id="generation-types-chart"></canvas>
                </div>
            </div>
            
            <div class="analytics-card">
                <h3>Extension & PWA</h3>
                <div class="tool-stats">
                    <div class="tool-stat">
                        <div class="tool-stat-value"><?php echo number_format($tool_data['extension_downloads'] ?? 0); ?></div>
                        <div class="tool-stat-label">Extension Downloads</div>
                    </div>
                    <div class="tool-stat">
                        <div class="tool-stat-value"><?php echo number_format($tool_data['pwa_installs'] ?? 0); ?></div>
                        <div class="tool-stat-label">PWA Installs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Newsletter Metrics -->
    <div class="analytics-section">
        <h2><?php _e('Newsletter Metrics', 'choose90-crm'); ?></h2>
        <div class="analytics-grid">
            <div class="analytics-card">
                <h3>Signup Locations</h3>
                <div class="chart-container">
                    <canvas id="signup-locations-chart"></canvas>
                </div>
            </div>
            
            <div class="analytics-card">
                <h3>Signup Trend</h3>
                <div class="chart-container">
                    <canvas id="newsletter-trend-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- SEO Performance -->
    <?php if ($seo_data['enabled'] ?? false): ?>
    <div class="analytics-section">
        <h2><?php _e('SEO Performance', 'choose90-crm'); ?></h2>
        <div class="analytics-grid">
            <div class="analytics-card">
                <h3>Top Search Queries</h3>
                <div class="query-list" id="top-queries">
                    <!-- Populated by JavaScript -->
                </div>
            </div>
            
            <div class="analytics-card">
                <h3>SEO Metrics</h3>
                <div class="seo-metrics">
                    <div class="seo-metric">
                        <div class="seo-metric-label">Total Clicks</div>
                        <div class="seo-metric-value"><?php echo number_format($seo_data['total_clicks'] ?? 0); ?></div>
                    </div>
                    <div class="seo-metric">
                        <div class="seo-metric-label">Total Impressions</div>
                        <div class="seo-metric-value"><?php echo number_format($seo_data['total_impressions'] ?? 0); ?></div>
                    </div>
                    <div class="seo-metric">
                        <div class="seo-metric-label">Average CTR</div>
                        <div class="seo-metric-value"><?php echo number_format($seo_data['average_ctr'] ?? 0, 2); ?>%</div>
                    </div>
                    <div class="seo-metric">
                        <div class="seo-metric-label">Average Position</div>
                        <div class="seo-metric-value"><?php echo number_format($seo_data['average_position'] ?? 0, 1); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="analytics-section">
        <div class="notice notice-info">
            <p><?php _e('Search Console is not configured. Configure it in Settings to see SEO performance data.', 'choose90-crm'); ?></p>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Campaign Performance -->
    <div class="analytics-section">
        <h2><?php _e('Campaign Performance', 'choose90-crm'); ?></h2>
        <div class="campaign-grid">
            <?php foreach ($campaign_data as $campaign_name => $campaign): ?>
            <div class="campaign-card">
                <h3><?php echo ucfirst(str_replace('_', ' ', $campaign_name)); ?></h3>
                <div class="campaign-stats">
                    <div class="campaign-stat">
                        <div class="campaign-stat-value"><?php echo number_format($campaign['views'] ?? 0); ?></div>
                        <div class="campaign-stat-label">Views</div>
                    </div>
                    <div class="campaign-stat">
                        <div class="campaign-stat-value"><?php echo number_format($campaign['cta_clicks'] ?? 0); ?></div>
                        <div class="campaign-stat-label">CTA Clicks</div>
                    </div>
                    <div class="campaign-stat">
                        <div class="campaign-stat-value"><?php echo number_format($campaign['conversion_rate'] ?? 0, 2); ?>%</div>
                        <div class="campaign-stat-label">Conversion Rate</div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Traffic Sources -->
    <div class="analytics-section">
        <h2><?php _e('Traffic Sources', 'choose90-crm'); ?></h2>
        <div class="analytics-card full-width">
            <div class="chart-container">
                <canvas id="traffic-sources-chart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Pass PHP data to JavaScript
window.choose90AnalyticsData = {
    overview: <?php echo json_encode($overview_data); ?>,
    pledge: <?php echo json_encode($pledge_data); ?>,
    social: <?php echo json_encode($social_data); ?>,
    resources: <?php echo json_encode($resource_data); ?>,
    tools: <?php echo json_encode($tool_data); ?>,
    newsletter: <?php echo json_encode($newsletter_data); ?>,
    seo: <?php echo json_encode($seo_data); ?>,
    campaigns: <?php echo json_encode($campaign_data); ?>,
    traffic: <?php echo json_encode($traffic_data); ?>
};
</script>


