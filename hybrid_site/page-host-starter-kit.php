<?php
/* Template Name: Host Starter Kit */

$submission_error = '';
$submission_success = false;

// --- FORM HANDLING LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_application'])) {

    // 1. Simple Math CAPTCHA Verification
    $captcha_answer = isset($_POST['captcha_answer']) ? intval($_POST['captcha_answer']) : 0;
    $correct_answer = isset($_POST['captcha_correct']) ? intval($_POST['captcha_correct']) : 0;

    if ($captcha_answer !== $correct_answer) {
        $submission_error = 'Incorrect math answer. Please try again.';
    } else {
        // 2. Sanitize Inputs
        $host_name = sanitize_text_field($_POST['host_name']);
        $host_location = sanitize_text_field($_POST['host_location']); // "City & State" -> Chapter Title
        $host_email = sanitize_email($_POST['host_email']);
        $host_date = sanitize_text_field($_POST['host_date']);

        if (!$host_name || !$host_location || !$host_email) {
            $submission_error = 'Please fill in all required fields.';
        } else {
            // 3. Create "Pending" Chapter Post
            $new_chapter_id = wp_insert_post(array(
                'post_title' => wp_strip_all_tags($host_location), // e.g. "Austin, TX"
                'post_type' => 'chapter',
                'post_status' => 'draft', // Saved as Draft (Pending Review)
                'post_content' => "Host Name: $host_name\nEmail: $host_email\nTarget Start Date: $host_date\n\n(This chapter is pending review. Publish to make it live.)",
            ));

            if ($new_chapter_id && !is_wp_error($new_chapter_id)) {

                // Save meta fields if you created them, or just use content for now.
                // update_post_meta($new_chapter_id, '_chapter_host_email', $host_email);

                $submission_success = true;

                // Optional: Send Admin Email Notification
                wp_mail(get_option('admin_email'), 'New Chapter Application: ' . $host_location, "A new chapter application has been received from $host_name for $host_location.\n\nReview it here: " . admin_url('post.php?post=' . $new_chapter_id . '&action=edit'));

            } else {
                $submission_error = 'Error saving application. Please try again.';
            }
        }
    }
}

// Generate new CAPTCHA for this load
$num1 = rand(1, 9);
$num2 = rand(1, 9);
$captcha_correct = $num1 + $num2;

get_header();
?>

<div id="main-content">

    <div class="container guide-hero" style="text-align: center; padding-top: 50px; padding-bottom: 40px;">
        <h1 style="color: #0066CC; font-size: 3rem; margin-bottom: 10px;">Choose90 Host Starter Kit</h1>
        <p style="font-size: 1.25rem; color: #666;">Everything you need to launch a chapter in your city.</p>
        <div style="margin-top: 20px;">
            <button onclick="window.print()" class="btn btn-outline">🖨️ Print / Save as PDF</button>
        </div>
    </div>

    <!-- The Kit Content -->
    <div class="kit-container"
        style="max-width: 850px; margin: 0 auto 60px; background: white; box-shadow: 0 4px 25px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">

        <!-- Page 1 -->
        <div class="kit-page" style="padding: 60px; border-bottom: 2px dashed #eee;">
            <h2 style="color: #333; font-size: 2.2rem; margin-bottom: 20px;">Page 1: The Mindset</h2>

            <h3 class="section-header"
                style="color: #0066CC; border-left: 5px solid #E8B93E; padding-left: 15px; margin: 30px 0 20px;">Welcome
                to Hosting!</h3>
            <p>Thank you for stepping up to host a Choose90 Chapter! Choose90 is all about shifting our focus to the 90%
                of life that's good—countering the negativity overload from news and social media with gratitude,
                encouragement, and uplifting stories. By hosting, you're creating a local space for everyday people to
                connect, share positivity, and choose a brighter perspective.</p>

            <div class="highlight-box"
                style="background: #f0f4ff; padding: 20px; border-radius: 10px; border: 1px solid #d0e0ff; margin: 20px 0;">
                <h3 style="margin-top: 0; color: #0066CC;">Your Role: Facilitator, Not Teacher</h3>
                <p>You don't need to be an expert, speaker, or positivity guru. Think of yourself as a
                    facilitator—someone who gently guides the group to keep things flowing smoothly.</p>
                <ul class="bullet-list" style="list-style: none; padding: 0;">
                    <li style="position: relative; padding-left: 25px; margin-bottom: 15px;"><strong>Guide the
                            Conversation:</strong> Introduce prompts and encourage sharing.</li>
                    <li style="position: relative; padding-left: 25px; margin-bottom: 15px;"><strong>Keep It
                            Inclusive:</strong> Ensure everyone feels safe and valued.</li>
                    <li style="position: relative; padding-left: 25px; margin-bottom: 15px;"><strong>Stay
                            Neutral:</strong> Let the group drive the discussion; steer away from negativity.</li>
                    <li style="position: relative; padding-left: 25px; margin-bottom: 15px;"><strong>Rely on the
                            Kit:</strong> Use the provided prompts—no need to create content from scratch.</li>
                </ul>
            </div>

            <h3 class="section-header"
                style="color: #0066CC; border-left: 5px solid #E8B93E; padding-left: 15px; margin: 30px 0 20px;">Code of
                Conduct: Keeping It Safe</h3>
            <ul class="bullet-list" style="list-style: none; padding: 0;">
                <li style="position: relative; padding-left: 25px; margin-bottom: 15px;"><strong>No Politics or Divisive
                        Topics:</strong> We focus on the good. Avoid debates on politics, religion, or polarizing news.
                </li>
                <li style="position: relative; padding-left: 25px; margin-bottom: 15px;"><strong>No Arguing or
                        Judgment:</strong> Listen actively, share kindly, and celebrate stories without critique.</li>
                <li style="position: relative; padding-left: 25px; margin-bottom: 15px;"><strong>Confidentiality is
                        Key:</strong> What's shared in the chapter stays in the chapter.</li>
                <li style="position: relative; padding-left: 25px; margin-bottom: 15px;"><strong>Positivity
                        First:</strong> Center on gratitude and kindness. If negativity creeps in, gently pivot back to
                    the 90%.</li>
            </ul>
        </div>

        <!-- Page 2 -->
        <div class="kit-page" style="padding: 60px; border-bottom: 2px dashed #eee;">
            <h2 style="color: #333; font-size: 2.2rem; margin-bottom: 20px;">Page 2: Running Your First Meeting</h2>

            <div class="highlight-box"
                style="background: #f0f4ff; padding: 20px; border-radius: 10px; border: 1px solid #d0e0ff; margin: 20px 0; border-left: 5px solid #28a745;">
                <h3 style="margin-top: 0; color: #28a745;">Quick Prep Tips</h3>
                <p><strong>Focus:</strong> 60-90 minutes monthly.<br>
                    <strong>Venue:</strong> Casual spot like a coffee shop or park.<br>
                    <strong>Supplies:</strong> Just name tags and a timer.
                </p>
            </div>

            <h3 class="section-header"
                style="color: #0066CC; border-left: 5px solid #E8B93E; padding-left: 15px; margin: 30px 0 20px;">Simple
                4-Step Meeting Flow</h3>

            <h4>1. Welcome & Icebreaker (10-15 mins)</h4>
            <p>Greet everyone warmly. Have each person share their name and one quick positive from the day (e.g., "I'm
                grateful for this coffee!"). briefly review the Code of Conduct.</p>

            <h4>2. Main Discussion: Positivity Prompts (30-45 mins)</h4>
            <p>Use these prompts to spark sharing. Go around the circle or let volunteers speak (2-3 mins each).</p>
            <ul class="bullet-list" style="list-style: none; padding: 0;">
                <li style="position: relative; padding-left: 25px; margin-bottom: 15px;"><strong>Gratitude
                        Share:</strong> "What's one thing you're grateful for this week?"</li>
                <li style="position: relative; padding-left: 25px; margin-bottom: 15px;"><strong>Good News
                        Spotlight:</strong> "Share a positive story or something inspiring you've seen."</li>
                <li style="position: relative; padding-left: 25px; margin-bottom: 15px;"><strong>Kindness
                        Chain:</strong> "Describe an act of kindness you've given or received."</li>
            </ul>

            <h4>3. Reflection (10 mins)</h4>
            <p>Wrap up with a group reflection: "What stood out to you today?" or "How can we choose 90% positivity this
                week?"</p>

            <h4>4. Close & Next Steps (5-10 mins)</h4>
            <p>Thank everyone! Announce the next meeting date. End on a high note with a group high-five.</p>

            <div class="agenda-download"
                style="margin-top: 40px; text-align: center; border-top: 1px solid #eee; padding-top: 30px;">
                <h3 style="color: #0066CC; margin-bottom: 10px;">Need a Guide?</h3>
                <p style="margin-bottom: 20px; color: #666;">Download our ready-to-use agenda with script prompts and
                    timing.</p>
                <a href="/assets/docs/choose90-meeting-agenda.html" target="_blank" class="btn btn-primary"
                    style="display: inline-block; padding: 12px 25px; background: #0066CC; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
                    📄 Get the 60-Minute Agenda
                </a>
            </div>
        </div>

        <!-- Host Registration Form Section -->
        <div class="kit-page" style="padding: 60px; background: #fafafa;">
            <h2 style="text-align: center; color: #0066CC;">Ready to Make it Official?</h2>

            <?php if ($submission_success): ?>
                <div class="alert success"
                    style="background: #d4edda; color: #155724; padding: 20px; border-radius: 5px; text-align: center; margin-bottom: 30px;">
                    <h3>Application Received!</h3>
                    <p>Thank you for submitting your chapter application. We will review it shortly and get in touch via
                        email.</p>
                </div>
            <?php else: ?>
                <p style="text-align: center; max-width: 600px; margin: 0 auto 30px;">Once you've read the kit and are ready
                    to schedule your first meeting, fill out this form so we can list your chapter on the website.</p>

                <?php if ($submission_error): ?>
                    <div class="alert error"
                        style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; text-align: center; margin-bottom: 20px;">
                        <?php echo $submission_error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action=""
                    style="max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">

                    <div style="margin-bottom: 20px;">
                        <label for="host_name" style="display: block; margin-bottom: 5px; font-weight: bold;">Your
                            Name</label>
                        <input type="text" name="host_name" id="host_name" required
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"
                            placeholder="John Doe">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="host_location" style="display: block; margin-bottom: 5px; font-weight: bold;">City &
                            State (Chapter Name)</label>
                        <input type="text" name="host_location" id="host_location" required
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"
                            placeholder="e.g. Choose90 Austin">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="host_email" style="display: block; margin-bottom: 5px; font-weight: bold;">Email
                            Address</label>
                        <input type="email" name="host_email" id="host_email" required
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"
                            placeholder="john@example.com">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="host_date" style="display: block; margin-bottom: 5px; font-weight: bold;">Initial
                            Meeting Date (Estimate)</label>
                        <input type="text" name="host_date" id="host_date" required
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"
                            placeholder="e.g., 3rd Tuesday of next month">
                    </div>

                    <!-- Simple Math CAPTCHA -->
                    <div style="margin-bottom: 20px;">
                        <label for="captcha_answer" style="display: block; margin-bottom: 5px; font-weight: bold;">Security
                            Check: What is <?php echo $num1; ?> + <?php echo $num2; ?>?</label>
                        <input type="number" name="captcha_answer" id="captcha_answer" required
                            style="width: 100px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <input type="hidden" name="captcha_correct" value="<?php echo $captcha_correct; ?>">
                    </div>

                    <div style="text-align: center;">
                        <button type="submit" name="submit_application" class="btn btn-primary"
                            style="width: 100%; padding: 15px; font-size: 1.1rem; border: none; cursor: pointer; border-radius: 5px;">Submit
                            Application</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>

    </div>
</div>

<style>
    /* Re-applying necessary styles since this is now a template */
    .kit-page ul.bullet-list li::before {
        content: "•";
        color: #E8B93E;
        font-weight: bold;
        font-size: 1.5em;
        position: absolute;
        left: 0;
        top: -5px;
    }
</style>

<?php get_footer(); ?>