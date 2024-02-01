<?php
function enqueue_custom_styles() {
    if (is_singular('portfolio')) {
        wp_enqueue_style('custom-portfolio-style', get_stylesheet_directory_uri() . '/single-portfolio.css', array(), '1.0.0', 'all');
    }
}
add_action('wp_enqueue_scripts', 'enqueue_custom_styles');
