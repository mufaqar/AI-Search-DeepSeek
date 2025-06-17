<?php
// Register shortcode
add_shortcode('zip_code_search', 'zip_code_search_shortcode');

function zip_code_search_shortcode() {
    wp_enqueue_script('jquery'); // Ensure jQuery is loaded
    
    ob_start();
    ?>
    <div class="zip-code-search-container">
        <form id="zip-code-search-form" method="post">
            <input 
                type="text" 
                name="zip_code" 
                placeholder="Enter Zip Code (e.g., 90210)" 
                required 
            />
            <button type="submit">Search</button>
        </form>
        <div id="zip-code-results"></div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('#zip-code-search-form').on('submit', function(e) {
            e.preventDefault();
            var zipCode = $('input[name="zip_code"]').val();
            
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'fetch_zip_code_data',
                    zip_code: zipCode
                },
                success: function(response) {
                    $('#zip-code-results').html(response);
                }
            });
        });
    });
    </script>
    <?php
    return ob_get_clean();
}

// Handle AJAX request
add_action('wp_ajax_fetch_zip_code_data', 'fetch_zip_code_data_callback');
add_action('wp_ajax_nopriv_fetch_zip_code_data', 'fetch_zip_code_data_callback');

function fetch_zip_code_data_callback() {
    if (!isset($_POST['zip_code'])) {
        wp_send_json_error('Zip code missing.');
    }

    $zip_code = sanitize_text_field($_POST['zip_code']);
    $api_url = 'https://api.deepseek.com/v1/zipcode?code=' . $zip_code;
    $api_key = 'YOUR_DEEPSEEK_API_KEY'; // Replace with your key

    // Fallback to Zippopotam.us if DeepSeek doesn't support zip codes
    if (empty($api_key)) {
        $api_url = 'https://api.zippopotam.us/us/' . $zip_code;
    }

    $response = wp_remote_get($api_url, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_key,
        ),
    ));

    if (is_wp_error($response)) {
        echo "Error: Could not fetch data.";
        wp_die();
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    
    // Display results
    if (!empty($data)) {
        echo '<div class="zip-code-result">';
        echo '<h3>Results for Zip Code: ' . esc_html($zip_code) . '</h3>';
        
        if (isset($data['places'])) { // Zippopotam.us format
            foreach ($data['places'] as $place) {
                echo '<p><strong>' . esc_html($place['place name']) . ', ' . esc_html($place['state']) . '</strong></p>';
                echo '<p>Longitude: ' . esc_html($place['longitude']) . '</p>';
                echo '<p>Latitude: ' . esc_html($place['latitude']) . '</p>';
            }
        } elseif (isset($data['location'])) { // DeepSeek format (example)
            echo '<p>Location: ' . esc_html($data['location']) . '</p>';
        }
        
        echo '</div>';
    } else {
        echo '<p>No results found.</p>';
    }
    
    wp_die();
}