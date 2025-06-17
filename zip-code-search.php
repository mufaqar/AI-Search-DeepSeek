<?php
/**
 * Plugin Name: Zip Code Search with DeepSeek
 * Description: Search for location data by zip code using DeepSeek API.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) exit; // Prevent direct access

// Load necessary files
require_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';

// Enqueue CSS
add_action('wp_enqueue_scripts', 'zip_code_search_enqueue_styles');
function zip_code_search_enqueue_styles() {
    wp_enqueue_style(
        'zip-code-search-css',
        plugin_dir_url(__FILE__) . 'assets/style.css',
        array(),
        '1.0'
    );
}