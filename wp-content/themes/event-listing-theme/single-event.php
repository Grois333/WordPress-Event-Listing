<?php
// Template for displaying a single event
get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        $event_date = get_field('event_date');
        $event_venue = get_field('event_venue');
        ?>
        <div class="single-event">
            <h1 class="single-event-title"><?php the_title(); ?></h1>
            <div class="single-event-date"><strong>Date:</strong> <?php echo esc_html($event_date); ?></div>
            <div class="single-event-venue"><strong>Venue:</strong> <?php echo esc_html($event_venue); ?></div>
            <div class="single-event-description">
                <strong>Description:</strong>
                <p><?php the_content(); ?></p>
            </div>
        </div>
        <?php
    endwhile;
else :
    echo '<p>No event details found</p>';
endif;

get_footer();
