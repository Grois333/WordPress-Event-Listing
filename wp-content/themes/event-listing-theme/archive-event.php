<?php
// Template for displaying custom post type events in chronological order
get_header();

$args = array(
    'post_type' => 'event',
    'meta_key' => 'event_date',
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'meta_type' => 'DATE',
    'posts_per_page' => 4, // Show 4 events at a time
);

$event_query = new WP_Query($args);

if ($event_query->have_posts()) :
    echo '<div id="event-list" class="event-list">'; // Wrapper for the event list
    while ($event_query->have_posts()) : $event_query->the_post();
        $event_date = get_field('event_date');
        $event_venue = get_field('event_venue');
        ?>
        <div class="event-item"> <!-- Individual event item -->
            <h2><?php the_title(); ?></h2>
            <div class="event-date"><strong>Date:</strong> <?php echo esc_html($event_date); ?></div>
            <div class="event-venue"><strong>Venue:</strong> <?php echo esc_html($event_venue); ?></div>
            <a href="<?php the_permalink(); ?>">View Event Details</a>
        </div>
        <?php
    endwhile;
    echo '</div>'; // Close the event list wrapper

    // Loader element
    echo '<div id="loader" style="display:none; text-align: center;">Loading...</div>';

    // "Load More" button
    if ($event_query->max_num_pages > 1) { // If there's more than one page of results
        echo '<button id="load-more" data-page="1" data-max="' . $event_query->max_num_pages . '">Load More</button>';
    }

    wp_reset_postdata();
else :
    echo '<p>No upcoming events found</p>';
endif;

get_footer();
