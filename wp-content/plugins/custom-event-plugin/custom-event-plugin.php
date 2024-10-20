<?php
/*
Plugin Name: Custom Event Plugin
Description: Adds a custom post type and meta boxes for managing events.
Version: 1.0
Author: Isaac Groisman
*/


function create_event_post_type() {
    $labels = array(
        'name' => 'Events',
        'singular_name' => 'Event',
        'add_new' => 'Add New Event',
        'add_new_item' => 'Add New Event',
        'edit_item' => 'Edit Event',
        'new_item' => 'New Event',
        'all_items' => 'All Events',
        'view_item' => 'View Event',
        'search_items' => 'Search Events',
        'not_found' =>  'No events found',
        'not_found_in_trash' => 'No events found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'Events'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'rewrite' => array('slug' => 'events'),
        'show_ui' => true, // Ensures it shows up in the admin
        'show_in_menu' => true, // Ensures it is in the admin menu
        'menu_position' => 5, // Position it below Posts in the menu
    );

    register_post_type('event', $args);
}
add_action('init', 'create_event_post_type');

//enqueueing assets
function enqueue_event_plugin_assets() {
    wp_enqueue_script('jquery');
    wp_enqueue_style('event-plugin-styles', plugins_url('assets/css/style.css', __FILE__));
    wp_enqueue_script('event-plugin-scripts', plugins_url('assets/js/script.js', __FILE__));

    // Pass AJAX URL to the script
    wp_localize_script('event-plugin-scripts', 'ajaxurl', admin_url('admin-ajax.php'));
}
add_action('wp_enqueue_scripts', 'enqueue_event_plugin_assets');

// Save ACF JSON in the plugin's acf-json folder
add_filter('acf/settings/save_json', 'my_acf_json_save_point');
function my_acf_json_save_point( $path ) {
    // Set the path to the acf-json folder in your plugin
    $path = plugin_dir_path(__FILE__) . 'acf-json';
    return $path;
}

// Load ACF JSON from the plugin's acf-json folder
add_filter('acf/settings/load_json', 'my_acf_json_load_point');
function my_acf_json_load_point( $paths ) {
    // Remove original path (optional, can keep to load from multiple places)
    unset($paths[0]);
    
    // Append your plugin's acf-json folder
    $paths[] = plugin_dir_path(__FILE__) . 'acf-json';
    return $paths;
}

function load_more_events() {
    $paged = $_POST['page']; // Current page number

    // Arguments for the WP Query
    $args = array(
        'post_type' => 'event',
        'meta_key' => 'event_date',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_type' => 'DATE',
        'posts_per_page' => 4, // Load 4 more events
        'paged' => $paged
    );

    $event_query = new WP_Query($args);

    if ($event_query->have_posts()) :
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
    else:
        echo ''; // Send an empty string if no more posts
    endif;

    wp_reset_postdata();
    die(); // Important: End the function here
}
add_action('wp_ajax_load_more_events', 'load_more_events');
add_action('wp_ajax_nopriv_load_more_events', 'load_more_events'); // For non-logged in users

//Add new event via ajax
function add_new_event() {
    // Check nonce for security (if you're using it)
    // check_ajax_referer('your_nonce_name', 'security');

    // Validate fields
    if (empty($_POST['form_data'])) {
        echo json_encode(array('success' => false, 'data' => 'All fields are required.'));
        wp_die(); // Terminate immediately
    }

    parse_str($_POST['form_data'], $form_data); // Parse the serialized string into an array

    // Check for required fields
    if (empty($form_data['event_title']) || empty($form_data['event_date']) || empty($form_data['event_venue'])) {
        echo json_encode(array('success' => false, 'data' => 'All fields are required.'));
        wp_die(); // Terminate immediately
    }

    // Proceed with adding the event (use wp_insert_post or similar)
    $event_id = wp_insert_post(array(
        'post_title' => sanitize_text_field($form_data['event_title']),
        'post_date' => sanitize_text_field($form_data['event_date']),
        'post_type' => 'event',
        'post_status' => 'draft', // Set status to draft
    ));

    // Check if ACF fields are set and save them
    if ($event_id && function_exists('get_field')) {
        update_field('event_venue', sanitize_text_field($form_data['event_venue']), $event_id);
        update_field('event_date', sanitize_text_field($form_data['event_date']), $event_id); // Save the event date
        // Add other ACF fields here
    }

    echo json_encode(array('success' => true, 'data' => 'Event added successfully.'));
    wp_die(); // Terminate immediately
}

add_action('wp_ajax_add_new_event', 'add_new_event');
add_action('wp_ajax_nopriv_add_new_event', 'add_new_event'); // For non-logged in users if needed



//Front Page Redirect
// function redirect_home_to_event_archive() {
//     if (is_front_page()) {
//         wp_redirect(home_url('/events/')); // Change '/events/' to the correct slug for your event archive
//         exit();
//     }
// }
// add_action('template_redirect', 'redirect_home_to_event_archive');




