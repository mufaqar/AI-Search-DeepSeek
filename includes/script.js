jQuery(document).ready(function($) {
    $('#zip-code-search-form').on('submit', function(e) {
        e.preventDefault();
        const zipCode = $('input[name="zip_code"]').val();
        
        $.ajax({
            url: zipSearchVars.ajaxurl,
            type: 'POST',
            data: {
                action: 'zip_code_search',
                zip_code: zipCode
            },
            beforeSend: function() {
                $('#zip-code-results').html('<p>Searching...</p>');
            },
            success: function(response) {
                if (response.success) {
                    $('#zip-code-results').html(response.data);
                }
            },
            error: function() {
                $('#zip-code-results').html('<p>Error fetching data.</p>');
            }
        });
    });
});