/**
 * Analytics Dashboard JavaScript
 * Handles charts, real-time updates, and data visualization
 */

(function($) {
    'use strict';

    let charts = {};
    let refreshInterval = null;

    // Initialize dashboard
    $(document).ready(function() {
        initializeCharts();
        setupAutoRefresh();
        setupRefreshButton();
    });

    /**
     * Initialize all charts
     */
    function initializeCharts() {
        const data = window.choose90AnalyticsData || {};

        // Pledge Trend Chart
        if ($('#pledge-trend-chart').length) {
            charts.pledgeTrend = createLineChart('pledge-trend-chart', {
                labels: getDateLabels(30),
                datasets: [{
                    label: 'Pledges',
                    data: data.pledge?.trend_data || [],
                    borderColor: '#2271b1',
                    backgroundColor: 'rgba(34, 113, 177, 0.1)',
                    tension: 0.4
                }]
            });
        }

        // Pledge Sources Chart
        if ($('#pledge-sources-chart').length) {
            charts.pledgeSources = createDoughnutChart('pledge-sources-chart', {
                labels: Object.keys(data.pledge?.sources || {}),
                data: Object.values(data.pledge?.sources || {})
            });
        }

        // Social Platform Chart
        if ($('#social-platform-chart').length) {
            charts.socialPlatform = createPieChart('social-platform-chart', {
                labels: Object.keys(data.social?.platform_breakdown || {}),
                data: Object.values(data.social?.platform_breakdown || {})
            });
        }

        // Social Trend Chart
        if ($('#social-trend-chart').length) {
            charts.socialTrend = createLineChart('social-trend-chart', {
                labels: getDateLabels(30),
                datasets: [{
                    label: 'Social Engagement',
                    data: data.social?.engagement_trend || [],
                    borderColor: '#00a32a',
                    backgroundColor: 'rgba(0, 163, 42, 0.1)',
                    tension: 0.4
                }]
            });
        }

        // Category Performance Chart
        if ($('#category-performance-chart').length) {
            charts.categoryPerformance = createBarChart('category-performance-chart', {
                labels: Object.keys(data.resources?.category_performance || {}),
                data: Object.values(data.resources?.category_performance || {})
            });
        }

        // Generation Types Chart
        if ($('#generation-types-chart').length) {
            charts.generationTypes = createDoughnutChart('generation-types-chart', {
                labels: Object.keys(data.tools?.generation_types || {}),
                data: Object.values(data.tools?.generation_types || {})
            });
        }

        // Signup Locations Chart
        if ($('#signup-locations-chart').length) {
            charts.signupLocations = createPieChart('signup-locations-chart', {
                labels: Object.keys(data.newsletter?.signup_locations || {}),
                data: Object.values(data.newsletter?.signup_locations || {})
            });
        }

        // Newsletter Trend Chart
        if ($('#newsletter-trend-chart').length) {
            charts.newsletterTrend = createLineChart('newsletter-trend-chart', {
                labels: getDateLabels(30),
                datasets: [{
                    label: 'Newsletter Signups',
                    data: data.newsletter?.trend_data || [],
                    borderColor: '#d63638',
                    backgroundColor: 'rgba(214, 54, 56, 0.1)',
                    tension: 0.4
                }]
            });
        }

        // Traffic Sources Chart
        if ($('#traffic-sources-chart').length) {
            charts.trafficSources = createBarChart('traffic-sources-chart', {
                labels: ['Organic', 'Direct', 'Social', 'Referral', 'Email', 'Paid'],
                data: [
                    data.traffic?.organic || 0,
                    data.traffic?.direct || 0,
                    data.traffic?.social || 0,
                    data.traffic?.referral || 0,
                    data.traffic?.email || 0,
                    data.traffic?.paid || 0
                ]
            });
        }

        // Populate lists
        populateResourceList(data.resources?.popular_resources || {});
        populateHashtagList(data.social?.hashtag_performance || {});
        populateQueryList(data.seo?.top_queries || []);
    }

    /**
     * Create line chart
     */
    function createLineChart(canvasId, chartData) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: chartData.datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    /**
     * Create bar chart
     */
    function createBarChart(canvasId, chartData) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Count',
                    data: chartData.data,
                    backgroundColor: '#2271b1',
                    borderColor: '#1d5a8f',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    /**
     * Create pie chart
     */
    function createPieChart(canvasId, chartData) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        return new Chart(ctx, {
            type: 'pie',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.data,
                    backgroundColor: [
                        '#2271b1',
                        '#00a32a',
                        '#d63638',
                        '#dba617',
                        '#826eb4',
                        '#72aee6'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    }

    /**
     * Create doughnut chart
     */
    function createDoughnutChart(canvasId, chartData) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        return new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.data,
                    backgroundColor: [
                        '#2271b1',
                        '#00a32a',
                        '#d63638',
                        '#dba617',
                        '#826eb4',
                        '#72aee6'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    }

    /**
     * Get date labels for last N days
     */
    function getDateLabels(days) {
        const labels = [];
        for (let i = days - 1; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        }
        return labels;
    }

    /**
     * Populate resource list
     */
    function populateResourceList(resources) {
        const container = $('#popular-resources');
        if (!container.length) return;

        container.empty();

        const sorted = Object.entries(resources)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 10);

        sorted.forEach(([name, count]) => {
            container.append(`
                <div class="resource-item">
                    <span class="resource-name">${name}</span>
                    <span class="resource-count">${count} views</span>
                </div>
            `);
        });
    }

    /**
     * Populate hashtag list
     */
    function populateHashtagList(hashtags) {
        const container = $('#hashtag-performance');
        if (!container.length) return;

        container.empty();

        const sorted = Object.entries(hashtags)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 10);

        sorted.forEach(([hashtag, count]) => {
            container.append(`
                <div class="hashtag-item">
                    <span class="hashtag-name">#${hashtag}</span>
                    <span class="hashtag-count">${count} mentions</span>
                </div>
            `);
        });
    }

    /**
     * Populate query list
     */
    function populateQueryList(queries) {
        const container = $('#top-queries');
        if (!container.length) return;

        container.empty();

        queries.slice(0, 10).forEach(query => {
            container.append(`
                <div class="query-item">
                    <span class="query-text">${query.query || query}</span>
                    <span class="query-stats">${query.clicks || 0} clicks</span>
                </div>
            `);
        });
    }

    /**
     * Setup auto-refresh
     */
    function setupAutoRefresh() {
        const interval = choose90Analytics.refresh_interval || 60000; // 60 seconds

        refreshInterval = setInterval(function() {
            refreshData(false); // Silent refresh
        }, interval);
    }

    /**
     * Setup refresh button
     */
    function setupRefreshButton() {
        $('#refresh-analytics').on('click', function() {
            refreshData(true);
        });
    }

    /**
     * Refresh data from server
     */
    function refreshData(showLoading) {
        if (showLoading) {
            $('.choose90-analytics-dashboard').addClass('loading');
            $('#refresh-analytics .dashicons-update').addClass('spin');
        }

        $.ajax({
            url: choose90Analytics.ajax_url,
            type: 'POST',
            data: {
                action: 'choose90_analytics_refresh',
                nonce: choose90Analytics.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Update data
                    window.choose90AnalyticsData = response.data;

                    // Update charts
                    updateCharts(response.data);

                    // Update metric cards
                    updateMetricCards(response.data);

                    // Update lists
                    populateResourceList(response.data.resources?.popular_resources || {});
                    populateHashtagList(response.data.social?.hashtag_performance || {});
                    populateQueryList(response.data.seo?.top_queries || []);

                    // Update timestamp
                    $('#last-updated-time').text('Just now');
                }
            },
            error: function() {
                console.error('Failed to refresh analytics data');
            },
            complete: function() {
                if (showLoading) {
                    $('.choose90-analytics-dashboard').removeClass('loading');
                    $('#refresh-analytics .dashicons-update').removeClass('spin');
                }
            }
        });
    }

    /**
     * Update charts with new data
     */
    function updateCharts(data) {
        // Update each chart if it exists
        Object.keys(charts).forEach(key => {
            if (charts[key]) {
                charts[key].destroy();
            }
        });

        // Reinitialize charts
        initializeCharts();
    }

    /**
     * Update metric cards
     */
    function updateMetricCards(data) {
        if (data.overview) {
            $('#visitors-today').text(numberFormat(data.overview.total_visitors_today || 0));
            $('#pledges-week').text(numberFormat(data.pledge?.pledges_last_7_days || 0));
            $('#newsletter-week').text(numberFormat(data.newsletter?.signups_last_7_days || 0));
            $('#bounce-rate').text((data.overview.bounce_rate || 0).toFixed(1) + '%');
        }
    }

    /**
     * Number formatting helper
     */
    function numberFormat(num) {
        return new Intl.NumberFormat().format(num);
    }

    // Cleanup on page unload
    $(window).on('beforeunload', function() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    });

})(jQuery);


