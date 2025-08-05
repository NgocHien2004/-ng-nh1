jQuery(document).ready(function($) {
    // Auto-complete cho search
    $('.phone-search-form input[name="s"]').on('input', function() {
        var searchTerm = $(this).val();
        if (searchTerm.length >= 2) {
            $.ajax({
                url: phonestore_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'phone_search_autocomplete',
                    term: searchTerm,
                    nonce: phonestore_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        showSearchSuggestions(response.data);
                    }
                }
            });
        }
    });
    
    function showSearchSuggestions(suggestions) {
        var suggestionsHtml = '<div class="search-suggestions">';
        suggestions.forEach(function(item) {
            suggestionsHtml += '<div class="suggestion-item" data-url="' + item.url + '">';
            suggestionsHtml += '<img src="' + item.image + '" width="30">';
            suggestionsHtml += '<span>' + item.title + '</span>';
            suggestionsHtml += '<span class="price">' + item.price + '</span>';
            suggestionsHtml += '</div>';
        });
        suggestionsHtml += '</div>';
        
        $('.phone-search-form').append(suggestionsHtml);
    }
    
    // Click vào suggestion
    $(document).on('click', '.suggestion-item', function() {
        window.location.href = $(this).data('url');
    });
    
    // AJAX filter cho sản phẩm
    $('.phone-filters select').on('change', function() {
        var formData = $('.phone-search-form').serialize();
        
        $.ajax({
            url: phonestore_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_products',
                filters: formData,
                nonce: phonestore_ajax.nonce
            },
            beforeSend: function() {
                $('.products').html('<div class="loading">Đang tải...</div>');
            },
            success: function(response) {
                if (response.success) {
                    $('.products').html(response.data);
                }
            }
        });
    });
});