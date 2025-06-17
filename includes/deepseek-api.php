<?php
/**
 * Fetches AI-powered responses from DeepSeek API.
 */

function fetch_deepseek_data($query) {
    $api_url = 'https://api.deepseek.com/v1/chat/completions';
    $api_key = 'sk-a5d59fe1cf9f490c95a1ba119c7857a6'; // Replace with your key
    
    $response = wp_remote_post($api_url, array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_key,
        ),
        'body' => json_encode(array(
            'model' => 'deepseek-chat',
            'messages' => array(
                array(
                    'role' => 'user',
                    'content' => "Provide insights about zip code {$query}."
                )
            ),
            'max_tokens' => 500,
        )),
    ));
    
    if (is_wp_error($response)) {
        return false;
    }
    
    $body = json_decode(wp_remote_retrieve_body($response), true);
    return $body['choices'][0]['message']['content'] ?? false;
}