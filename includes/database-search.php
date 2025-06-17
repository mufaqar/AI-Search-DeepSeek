<?php
/**
 * Searches custom database tables or WordPress posts by zip code.
 */

function search_database_by_zip($zip_code) {
    global $wpdb;
    
    // // Example: Query a custom table (modify as needed)
    // $results = $wpdb->get_results(
    //     $wpdb->prepare(
    //         "SELECT * FROM {$wpdb->prefix}custom_locations 
    //          WHERE zip_code = %s",
    //         $zip_code
    //     )
    // );
    
    // Alternative: Query WordPress posts with zip meta
    $results = get_posts(array(
        'meta_key' => 'zip_code',
        'meta_value' => $zip_code,
        'post_type' => 'providers',
    ));
    
    if (empty($results)) {
        return false;
    }
    
    return $results;
}