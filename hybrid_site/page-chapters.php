<?php
/* Template Name: Chapters Directory */
get_header();
?>

<div id="main-content">

    <!-- HERO & PITCH SECTION (Always Visible) -->
    <div class="chapters-pitch" style="max-width: 900px; margin: 0 auto; padding: 50px 20px;">

        <!-- Hero Section -->
        <div style="text-align: center; margin-bottom: 60px;">
            <h1 style="font-size: 3.5rem; font-weight: 800; color: #0066CC; margin-bottom: 20px; line-height: 1.1;">
                Stop Waiting for Community.<br><span style="color: #E8B93E;">Build It.</span></h1>
            <p style="font-size: 1.25rem; color: #555; max-width: 700px; margin: 0 auto 30px;">
                Be the spark that starts the movement in your neighborhood.
            </p>

            <div class="hero-actions" style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="/host-starter-kit/" class="btn btn-starfield"
                    style="padding: 15px 35px; font-size: 1.2rem; border-radius: 50px; box-shadow: 0 10px 20px rgba(0,102,204,0.2);">
                    Get the Host Starter Kit
                </a>
                <a href="#chapter-directory" class="btn btn-outline"
                    style="padding: 15px 35px; font-size: 1.2rem; border-radius: 50px; border: 2px solid #0066CC; color: #0066CC;">
                    Find a Chapter
                </a>
            </div>
        </div>

        <!-- 3 Benefits Grid -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-bottom: 60px;">
            <div
                style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 5px solid #0066CC;">
                <h3 style="color: #0066CC; margin-top: 0; font-size: 1.4rem;">1. Cure Your Own Loneliness</h3>
                <p style="color: #666; font-size: 0.95rem; line-height: 1.6;">The secret to connection isn't finding
                    it—it's creating it. By gathering neighbors for positive conversations, you'll fill your own cup
                    while serving others.</p>
            </div>
            <div
                style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 5px solid #E8B93E;">
                <h3 style="color: #E8B93E; margin-top: 0; font-size: 1.4rem;">2. Be a Beacon of Light</h3>
                <p style="color: #666; font-size: 0.95rem; line-height: 1.6;">For just one hour a month, create a space
                    where hope outweighs headlines. Your coffee shop corner becomes an oasis where people rediscover
                    shared humanity.</p>
            </div>
            <div
                style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 5px solid #0066CC;">
                <h3 style="color: #0066CC; margin-top: 0; font-size: 1.4rem;">3. Do Something Real</h3>
                <p style="color: #666; font-size: 0.95rem; line-height: 1.6;">Swap scrolling for sitting together. Trade
                    online debates for eye contact. Choose90 turns passive concern into active connection.</p>
            </div>
        </div>

        <!-- How It Works Section -->
        <div style="background: #f0f4ff; padding: 50px; border-radius: 20px; text-align: center; margin-bottom: 40px;">
            <h2 style="color: #0066CC; margin-bottom: 40px; font-size: 2.2rem;">How It Works <span
                    style="font-size: 1rem; color: #666; font-weight: normal; display: block; margin-top: 10px;">(It's
                    Simpler Than You Think)</span></h2>
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; text-align: left;">
                <div style="flex: 1; min-width: 250px;">
                    <h4 style="font-size: 1.2rem; margin-bottom: 10px; color: #0066CC;">Step 1: Apply (5 mins)</h4>
                    <p style="font-size: 0.95rem;">Tell us you're interested. No resume, no experience needed. We're
                        looking for hearts, not credentials.</p>
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <h4 style="font-size: 1.2rem; margin-bottom: 10px; color: #E8B93E;">Step 2: Get Your Kit (Free)</h4>
                    <p style="font-size: 0.95rem;">We'll send you discussion guides, hosting tips, and a warm welcome
                        package. Everything is designed for regular people.</p>
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <h4 style="font-size: 1.2rem; margin-bottom: 10px; color: #0066CC;">Step 3: Host (1 hr/month)</h4>
                    <p style="font-size: 0.95rem;">Pick a coffee shop, show up, and welcome people. You're not teaching
                        or preaching—you're facilitating.</p>
                </div>
            </div>
        </div>

    </div>

    <!-- CHAPTER DIRECTORY (Grid) -->
    <div id="chapter-directory" class="container" style="padding: 50px 0; border-top: 1px solid #eee;">

        <h2 class="section-title" style="text-align: center; margin-bottom: 30px; color: #333;">Active Community
            Chapters</h2>

        <!-- Search and Filter Section -->
        <div style="max-width: 800px; margin: 0 auto 40px; padding: 25px; background: #f9fafb; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px; align-items: end;">
                <!-- Search Box -->
                <div>
                    <label for="chapter-search" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">🔍 Search Chapters</label>
                    <input type="text" id="chapter-search" placeholder="Search by city, name, or location..." 
                           style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px;"
                           onkeyup="filterChapters()">
                </div>
                
                <!-- State Filter -->
                <div>
                    <label for="chapter-state-filter" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">📍 Filter by State</label>
                    <select id="chapter-state-filter" onchange="filterChapters()"
                            style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px; cursor: pointer;">
                        <option value="">All States</option>
                        <?php
                        // Get all unique states from published chapters
                        $all_chapters = get_posts(array(
                            'post_type' => 'chapter',
                            'post_status' => 'publish',
                            'posts_per_page' => -1,
                            'meta_query' => array(
                                array(
                                    'key' => '_chapter_status',
                                    'value' => 'active',
                                    'compare' => '='
                                )
                            )
                        ));
                        
                        $states = array();
                        foreach ($all_chapters as $chapter) {
                            $state = get_post_meta($chapter->ID, '_chapter_state', true);
                            if ($state && !in_array($state, $states)) {
                                $states[] = $state;
                            }
                        }
                        sort($states);
                        
                        $us_states = array(
                            'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
                            'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
                            'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho',
                            'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
                            'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
                            'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
                            'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
                            'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
                            'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
                            'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
                            'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
                            'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
                            'WI' => 'Wisconsin', 'WY' => 'Wyoming', 'DC' => 'District of Columbia'
                        );
                        
                        foreach ($states as $state_code) {
                            $state_name = isset($us_states[$state_code]) ? $us_states[$state_code] : $state_code;
                            echo '<option value="' . esc_attr($state_code) . '">' . esc_html($state_name) . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            
            <!-- Results Count -->
            <div id="filter-results" style="margin-top: 15px; text-align: center; color: #666; font-size: 14px;">
                <!-- Will be updated by JavaScript -->
            </div>
            
            <!-- Clear Filters -->
            <div style="text-align: center; margin-top: 15px;">
                <button onclick="clearFilters()" 
                        style="background: none; border: none; color: #0066CC; cursor: pointer; text-decoration: underline; font-size: 14px;">
                    Clear Filters
                </button>
            </div>
        </div>

        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
            'post_type' => 'chapter',
            'posts_per_page' => 12,
            'paged' => $paged,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_chapter_status',
                    'value' => 'active',
                    'compare' => '='
                )
            )
        );
        $chapters_query = new WP_Query($args);

        if ($chapters_query->have_posts()): ?>

            <div id="chapters-grid" class="chapters-grid"
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
                <?php while ($chapters_query->have_posts()):
                    $chapters_query->the_post();
                    $chapter_state = get_post_meta(get_the_ID(), '_chapter_state', true);
                    $chapter_city = get_post_meta(get_the_ID(), '_chapter_city', true);
                    $chapter_title = get_the_title();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('chapter-card'); ?>
                        data-state="<?php echo esc_attr($chapter_state); ?>"
                        data-city="<?php echo esc_attr(strtolower($chapter_city)); ?>"
                        data-title="<?php echo esc_attr(strtolower($chapter_title)); ?>"
                        style="border: 1px solid #eee; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); background: white; transition: transform 0.2s, box-shadow 0.2s;"
                        onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 12px rgba(0,0,0,0.1)';"
                        onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.05)';">

                        <h2 class="entry-title" style="font-size: 1.5rem; margin-bottom: 10px; color: #0066CC;">
                            <a href="<?php the_permalink(); ?>"
                                style="text-decoration: none; color: #0066CC; font-weight: 700;"><?php the_title(); ?></a>
                        </h2>

                        <?php
                        // Get location info
                        $city = get_post_meta(get_the_ID(), '_chapter_city', true);
                        $state = get_post_meta(get_the_ID(), '_chapter_state', true);
                        $meeting_pattern = get_post_meta(get_the_ID(), '_chapter_meeting_pattern', true);
                        $location_name = get_post_meta(get_the_ID(), '_chapter_location_name', true);
                        $member_count = get_post_meta(get_the_ID(), '_chapter_member_count', true);
                        
                        // Display location
                        if ($city || $state) {
                            echo '<p style="margin-bottom: 10px; color: #666; font-size: 0.9rem;">';
                            echo '📍 ';
                            if ($city && $state) {
                                echo esc_html($city) . ', ' . esc_html($state);
                            } elseif ($city) {
                                echo esc_html($city);
                            } elseif ($state) {
                                echo esc_html($state);
                            }
                            echo '</p>';
                        }
                        ?>

                        <?php if (has_post_thumbnail()): ?>
                            <div class="chapter-image" style="margin-bottom: 15px;">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium', array('style' => 'width:100%; height:auto; border-radius: 5px;')); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content" style="font-size: 0.95rem; color: #666; margin-bottom: 15px; line-height: 1.6;">
                            <?php the_excerpt(); ?>
                        </div>

                        <?php
                        // Display meeting info if available
                        if ($meeting_pattern || $location_name) {
                            echo '<div style="margin-bottom: 15px; padding: 10px; background: #f0f8ff; border-radius: 5px; font-size: 0.85rem;">';
                            if ($meeting_pattern) {
                                echo '<strong style="color: #004A99;">📅</strong> ' . esc_html($meeting_pattern);
                                if ($location_name) {
                                    echo '<br>';
                                }
                            }
                            if ($location_name) {
                                echo '<strong style="color: #004A99;">📍</strong> ' . esc_html($location_name);
                            }
                            echo '</div>';
                        }
                        
                        // Display member count if available
                        if ($member_count && $member_count > 0) {
                            echo '<p style="font-size: 0.85rem; color: #666; margin-bottom: 15px;">';
                            echo '👥 ' . esc_html($member_count) . ' active members';
                            echo '</p>';
                        }
                        ?>

                        <a href="<?php the_permalink(); ?>" class="btn btn-outline"
                            style="display: inline-block; padding: 10px 24px; border: 2px solid #0066CC; color: #0066CC; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.2s;"
                            onmouseover="this.style.background='#0066CC'; this.style.color='white';"
                            onmouseout="this.style.background='transparent'; this.style.color='#0066CC';">
                            View Chapter →
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="pagination" style="text-align: center; margin-top: 40px;">
                <?php echo paginate_links(array('total' => $chapters_query->max_num_pages)); ?>
            </div>

        <?php else: ?>

            <p style="text-align: center; font-size: 1.2rem; color: #666;">No active chapters yet. <a href="#top"
                    style="color: #0066CC;">Start one today!</a></p>

        <?php endif;
        wp_reset_postdata(); ?>

    </div>
</div>

<script>
function filterChapters() {
    const searchTerm = document.getElementById('chapter-search').value.toLowerCase();
    const stateFilter = document.getElementById('chapter-state-filter').value;
    const cards = document.querySelectorAll('.chapter-card');
    let visibleCount = 0;
    
    cards.forEach(function(card) {
        const cardState = card.getAttribute('data-state') || '';
        const cardCity = card.getAttribute('data-city') || '';
        const cardTitle = card.getAttribute('data-title') || '';
        const cardText = card.textContent.toLowerCase();
        
        // Check state filter
        const stateMatch = !stateFilter || cardState === stateFilter;
        
        // Check search term
        const searchMatch = !searchTerm || 
            cardTitle.includes(searchTerm) ||
            cardCity.includes(searchTerm) ||
            cardText.includes(searchTerm);
        
        // Show/hide card
        if (stateMatch && searchMatch) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Update results count
    const resultsDiv = document.getElementById('filter-results');
    if (resultsDiv) {
        if (visibleCount === cards.length) {
            resultsDiv.innerHTML = 'Showing all ' + cards.length + ' chapters';
        } else {
            resultsDiv.innerHTML = 'Showing ' + visibleCount + ' of ' + cards.length + ' chapters';
        }
    }
}

function clearFilters() {
    document.getElementById('chapter-search').value = '';
    document.getElementById('chapter-state-filter').value = '';
    filterChapters();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    filterChapters();
});
</script>

<?php get_footer(); ?>