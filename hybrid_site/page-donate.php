<?php
/* Template Name: Donate Page */

get_header();
?>

<div id="main-content">
    <div class="container" style="padding: 50px 0;">
        
        <!-- Hero Section -->
        <div style="text-align: center; padding: 60px 20px; background: linear-gradient(135deg, #0066CC 0%, #004A99 100%); border-radius: 15px; margin-bottom: 50px; color: white;">
            <h1 style="font-size: 3rem; margin-bottom: 20px; color: white;">💙 Support Choose90</h1>
            <p style="font-size: 1.3rem; max-width: 700px; margin: 0 auto; line-height: 1.6;">
                Choose90 is free for everyone, always.<br>
                But it costs real money to keep running.
            </p>
        </div>

        <!-- Donation Form Section -->
        <div style="max-width: 900px; margin: 0 auto;">
            
            <?php
            // Check if WooCommerce is active
            if (class_exists('WooCommerce')) {
                // Include donation form component
                $donation_form_path = get_stylesheet_directory() . '/../hybrid_site/components/donation-form.html';
                if (file_exists($donation_form_path)) {
                    echo file_get_contents($donation_form_path);
                } else {
                    // Fallback: Show WooCommerce products
                    echo '<div style="text-align: center; padding: 40px;">';
                    echo '<h2>Donation Options</h2>';
                    echo do_shortcode('[products category="donations" columns="2"]');
                    echo '</div>';
                }
            } else {
                // WooCommerce not active - show message
                ?>
                <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 30px; border-radius: 10px; text-align: center;">
                    <h2 style="color: #856404; margin-top: 0;">WooCommerce Not Configured</h2>
                    <p style="color: #856404; font-size: 1.1rem;">
                        To enable donations, please:
                    </p>
                    <ol style="text-align: left; max-width: 500px; margin: 20px auto; color: #856404;">
                        <li>Install and activate WooCommerce plugin</li>
                        <li>Configure payment gateways (Stripe/PayPal)</li>
                        <li>Create donation products</li>
                    </ol>
                    <p style="color: #856404;">
                        See <strong>DONATION_PAGE_SETUP.md</strong> for detailed instructions.
                    </p>
                </div>
                <?php
            }
            ?>

            <!-- Impact Message -->
            <div style="margin-top: 40px; padding: 30px; background: #f0f8ff; border-radius: 10px; text-align: center; border-left: 4px solid #0066CC;">
                <h3 style="color: #0066CC; margin-top: 0;">100% of donations go to Choose90 operations</h3>
                <p style="color: #555; margin-bottom: 0;">
                    Your support helps us keep Choose90 free for everyone and expand our positive impact.
                    All donations are tax-deductible.
                </p>
            </div>

        </div>

    </div>
</div>

<?php get_footer(); ?>

