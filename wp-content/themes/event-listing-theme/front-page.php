<?php
// This will use the archive-event.php template for the homepage
if (have_posts()) :
    while (have_posts()) : the_post();
        // Call the event archive template
        get_template_part('archive', 'event');
    endwhile;
else :
    echo '<p>No events found</p>';
endif;
?>
