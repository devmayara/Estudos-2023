<?php
function bp_theme_styles() {
    wp_enqueue_style('theme_css', get_template_directory_uri(). '/assets/css/theme.css');

    wp_enqueue_script('theme_js', get_template_directory_uri(). '/assets/js/script.js', array('jquery'), '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'bp_theme_styles');