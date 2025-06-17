<?php
/**
 * Plugin Name: Zip Code Search with DeepSeek & Database
 * Description: Search zip codes from database + DeepSeek API.
 * Version: 1.1
 */

if (!defined('ABSPATH')) exit;

// Load dependencies
require_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/database-search.php';
require_once plugin_dir_path(__FILE__) . 'includes/deepseek-api.php';

// Enqueue assets
add_action('wp_enqueue_scripts', 'zip_code_search_assets');
function zip_code_search_assets() {
    // CSS
    wp_enqueue_style(
        'zip-code-search-css',
        plugin_dir_url(__FILE__) . 'assets/style.css'
    );
    
    // JS
    wp_enqueue_script(
        'zip-code-search-js',
        plugin_dir_url(__FILE__) . 'assets/script.js',
        array('jquery'),
        '1.0',
        true
    );
    
    // Localize script for AJAX URL
    wp_localize_script(
        'zip-code-search-js',
        'zipSearchVars',
        array('ajaxurl' => admin_url('admin-ajax.php'))
    );
}