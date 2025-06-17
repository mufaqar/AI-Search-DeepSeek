<?php
/**
 * Searches 'providers' post type by zip code and custom fields.
 */

function search_providers_by_zip($zip_code, $args = array()) {
    $defaults = array(
        'speed' => '',  // Optional: Filter by speed
        'price' => '',  // Optional: Filter by price
    );
    $filters = wp_parse_args($args, $defaults);

    $query_args = array(
        'post_type' => 'providers',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key'     => 'zip_code', // Assuming zip is stored here
                'value'   => $zip_code,
                'compare' => '=',
            ),
        ),
    );

    // Add speed filter if provided
    if (!empty($filters['speed'])) {
        $query_args['meta_query'][] = array(
            'key'     => 'speed',
            'value'   => $filters['speed'],
            'compare' => '>=', // Example: "At least X speed"
            'type'    => 'NUMERIC',
        );
    }

    // Add price filter if provided
    if (!empty($filters['price'])) {
        $query_args['meta_query'][] = array(
            'key'     => 'price',
            'value'   => $filters['price'],
            'compare' => '<=', // Example: "Up to $X price"
            'type'    => 'NUMERIC',
        );
    }

    $providers = get_posts($query_args);

    if (empty($providers)) {
        return false;
    }

    // Format results
    $results = array();
    foreach ($providers as $provider) {
        $results[] = array(
            'id'      => $provider->ID,
            'title'   => $provider->post_title,
            'speed'   => get_post_meta($provider->ID, 'speed', true),
            'price'   => get_post_meta($provider->ID, 'price', true),
            'address' => get_post_meta($provider->ID, 'address', true),
        );
    }

    return $results;
}