<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); // Hook for including styles and scripts ?>
</head>
<body <?php body_class(); ?>>
<header>
    <div class="site-title">
        <a href="<?php echo esc_url(home_url('/')); ?>">
            <h1>
               <?php bloginfo('name'); ?>
            </h1>
        </a>
        <a href="<?php echo esc_url(home_url('/event-listing/add-event/')); ?>">Add New Event</a>
    </div>
    <?php /*<nav>
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary', // Make sure to register this menu location
            'menu_class' => 'primary-menu',
        ));
        ?>
    </nav> */ ?>
</header>
