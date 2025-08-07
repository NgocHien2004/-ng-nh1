<?php
/*
Template Name: Compare Products
*/

get_header();
?>

<div class="container">
    <div class="page-header">
        <h1>📊 So sánh sản phẩm</h1>
        <p>So sánh thông số kỹ thuật chi tiết giữa các điện thoại</p>
    </div>
    
    <div id="compare-products">
        <div class="compare-search">
            <h3>Thêm sản phẩm để so sánh (tối đa 4 sản phẩm)</h3>
            <div class="search-container">
                <input type="text" id="product-search" placeholder="Tìm kiếm sản phẩm để thêm vào so sánh...">
                <div id="search-results"></div>
            </div>
        </div>
        
        <div id="compare-table-container" style="display: none;">
            <h3>Bảng so sánh</h3>
            <div class="compare-table-wrapper">
                <table id="compare-table" class="compare-table">
                    <thead>
                        <tr id="product-row">
                            <td class="spec-label">Sản phẩm</td>
                        </tr>
                    </thead>
                    <tbody id="compare-body">
                        <!-- Specs sẽ được load bằng JavaScript -->
                    </tbody>
                </table>
            </div>
            <button id="clear-compare" class="button-clear">🗑️ Xóa tất cả</button>
        </div>
        
        <!-- Quick Compare với sản phẩm có sẵn -->
        <div class="quick-compare-section">
            <h3>📱 So sánh nhanh</h3>
            <p>Chọn 2-3 sản phẩm phổ biến để so sánh ngay:</p>
            
            <div class="quick-compare-products">
                <?php
                $popular_products = wc_get_products([
                    'limit' => 6,
                    'orderby' => 'popularity',
                    'status' => 'publish'
                ]);
                
                foreach ($popular_products as $product):
                ?>
                    <div class="quick-product-item">
                        <div class="product-image">
                            <?php echo $product->get_image('thumbnail'); ?>
                        </div>
                        <div class="product-info">
                            <h4><?php echo $product->get_name(); ?></h4>
                            <div class="price"><?php echo $product->get_price_html(); ?></div>
                        </div>
                        <button class="quick-add-compare" data-product-id="<?php echo $product->get_id(); ?>">
                            + Thêm so sánh
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- CSS Styles -->
<style>
.page-header {
    text-align: center;
    padding: 40px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    margin: 20px 0 40px 0;
}

.page-header h1 {
    font-size: 2.2rem;
    margin-bottom: 10px;
}

.page-header p {
    font-size: 1.1rem;
    opacity: 0.9;
}

.compare-search {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 15px;
    margin: 30px 0;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.compare-search h3 {
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

.search-container {
    position: relative;
    max-width: 600px;
    margin: 0 auto;
}

#product-search {
    width: 100%;
    padding: 15px 20px;
    border: 2px solid #ddd;
    border-radius: 10px;
    font-size: 16px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#product-search:focus {
    outline: none;
    border-color: #007cba;
}

#search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 10px;
    max-height: 400px;
    overflow-y: auto;
    z-index: 100;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    margin-top: 5px;
}

.search-result-item {
    display: flex;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: background 0.3s;
}

.search-result-item:hover {
    background: #f8f9fa;
}

.search-result-item:last-child {
    border-bottom: none;
}

.search-result-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    margin-right: 15px;
    border-radius: 8px;
}

.search-result-item .product-info {
    flex: 1;
}

.search-result-item .product-info h4 {
    margin: 0 0 5px 0;
    color: #333;
    font-size: 16px;
}

.search-result-item .price {
    font-weight: bold;
    color: #e74c3c;
    font-size: 14px;
}

.add-to-compare {
    background: #28a745;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: background 0.3s;
}

.add-to-compare:hover {
    background: #218838;
}

.compare-table-wrapper {
    overflow-x: auto;
    margin: 30px 0;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.compare-table {
    width: 100%;
    min-width: 800px;
    border-collapse: collapse;
    background: white;
}

.compare-table td {
    border: 1px solid #ddd;
    padding: 15px;
    text-align: center;
    vertical-align: top;
}

.spec-label {
    background: #f8f9fa;
    font-weight: bold;
    text-align: left !important;
    min-width: 150px;
    color: #333;
}

.product-column {
    position: relative;
    min-width: 200px;
}

.product-compare-header {
    position: relative;
    padding: 20px;
}

.product-compare-header img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    margin-bottom: 15px;
    border-radius: 10px;
}

.product-compare-header h4 {
    margin: 10px 0;
    color: #333;
    font-size: 16px;
    line-height: 1.4;
}

.product-compare-header h4 a {
    color: inherit;
    text-decoration: none;
}

.product-compare-header h4 a:hover {
    color: #007cba;
}

.product-compare-header .price {
    font-size: 18px;
    font-weight: bold;
    color: #e74c3c;
    margin: 10px 0;
}

.remove-from-compare {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s;
}

.remove-from-compare:hover {
    background: #c82333;
}

.button-clear {
    background: #dc3545;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    margin: 20px 0;
    transition: background 0.3s;
}

.button-clear:hover {
    background: #c82333;
}

/* Quick Compare Section */
.quick-compare-section {
    background: white;
    padding: 30px;
    border-radius: 15px;
    margin: 40px 0;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.quick-compare-section h3 {
    text-align: center;
    color: #333;
    margin-bottom: 10px;
}

.quick-compare-section p {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
}

.quick-compare-products {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.quick-product-item {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.quick-product-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.1);
}

.quick-product-item .product-image img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 10px;
}

.quick-product-item h4 {
    margin: 10px 0;
    color: #333;
    font-size: 14px;
    line-height: 1.3;
}

.quick-product-item .price {
    font-weight: bold;
    color: #e74c3c;
    margin: 8px 0;
    font-size: 14px;
}

.quick-add-compare {
    background: #007cba;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    width: 100%;
    transition: background 0.3s;
}

.quick-add-compare:hover {
    background: #005a87;
}

.quick-add-compare.added {
    background: #28a745;
    cursor: default;
}

.quick-add-compare.added:hover {
    background: #28a745;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .page-header h1 {
        font-size: 1.8rem;
    }
    
    .compare-search {
        padding: 20px;
    }
    
    .compare-table {
        font-size: 12px;
    }
    
    .product-compare-header img {
        width: 80px;
        height: 80px;
    }
    
    .product-compare-header h4 {
        font-size: 14px;
    }
    
    .search-result-item {
        flex-direction: column;
        text-align: center;
        padding: 10px;
    }
    
    .search-result-item img {
        margin-right: 0;
        margin-bottom: 10px;
    }
    
    .quick-compare-products {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }
}
</style>

<!-- JavaScript -->
<script>
jQuery(document).ready(function($) {
    var compareProducts = JSON.parse(localStorage.getItem('phonestore_compare') || '[]');
    var maxProducts = 4;
    
    // Load existing compare table if any
    updateCompareDisplay();
    
    // Search sản phẩm
    var searchTimeout;
    $('#product-search').on('input', function() {
        var searchTerm = $(this).val();
        
        clearTimeout(searchTimeout);
        
        if (searchTerm.length >= 2) {
            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: phonestore_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'phonestore_ajax_product_search',
                        term: searchTerm,
                        nonce: phonestore_ajax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            showSearchResults(response.data);
                        }
                    },
                    error: function() {
                        $('#search-results').html('<div class="search-result-item"><p>Lỗi khi tìm kiếm. Vui lòng thử lại.</p></div>');
                    }
                });
            }, 300);
        } else {
            $('#search-results').empty();
        }
    });
    
    // Click outside to close search results
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-container').length) {
            $('#search-results').empty();
        }
    });
    
    function showSearchResults(products) {
        var html = '';
        if (products.length > 0) {
            products.forEach(function(product) {
                html += '<div class="search-result-item" data-id="' + product.id + '">';
                html += '<img src="' + (product.image || '') + '" alt="">';
                html += '<div class="product-info">';
                html += '<h4>' + product.title + '</h4>';
                html += '<div class="price">' + product.price + '</div>';
                html += '</div>';
                html += '<button class="add-to-compare">Thêm</button>';
                html += '</div>';
            });
        } else {
            html = '<div class="search-result-item"><p>Không tìm thấy sản phẩm nào.</p></div>';
        }
        $('#search-results').html(html);
    }
    
    // Thêm sản phẩm vào compare từ search
    $(document).on('click', '.add-to-compare', function() {
        var productId = parseInt($(this).closest('.search-result-item').data('id'));
        addToCompare(productId);
    });
    
    // Thêm sản phẩm vào compare từ quick section
    $(document).on('click', '.quick-add-compare', function() {
        var productId = parseInt($(this).data('product-id'));
        var button = $(this);
        
        if (!button.hasClass('added')) {
            addToCompare(productId);
            button.addClass('added').text('✓ Đã thêm');
        }
    });
    
    function addToCompare(productId) {
        if (compareProducts.length >= maxProducts) {
            alert('Chỉ có thể so sánh tối đa ' + maxProducts + ' sản phẩm');
            return;
        }
        
        if (compareProducts.indexOf(productId) === -1 && productId) {
            compareProducts.push(productId);
            localStorage.setItem('phonestore_compare', JSON.stringify(compareProducts));
            updateCompareDisplay();
            $('#search-results').empty();
            $('#product-search').val('');
            
            // Update quick buttons
            $('.quick-add-compare[data-product-id="' + productId + '"]')
                .addClass('added').text('✓ Đã thêm');
            
            alert('Đã thêm sản phẩm vào so sánh!');
        } else if (compareProducts.indexOf(productId) !== -1) {
            alert('Sản phẩm đã có trong danh sách so sánh!');
        }
    }
    
    function updateCompareDisplay() {
        if (compareProducts.length === 0) {
            $('#compare-table-container').hide();
            // Reset quick buttons
            $('.quick-add-compare').removeClass('added').text('+ Thêm so sánh');
            return;
        }
        
        // Show compare table
        $('#compare-table-container').show();
        $('#compare-table-container h3').text('Bảng so sánh (' + compareProducts.length + ' sản phẩm)');
        
        // Load compare data (simplified version - would need AJAX to get full product data)
        loadCompareTableData();
        
        // Update quick buttons state
        compareProducts.forEach(function(id) {
            $('.quick-add-compare[data-product-id="' + id + '"]')
                .addClass('added').text('✓ Đã thêm');
        });
    }
    
    function loadCompareTableData() {
        // Basic table structure - would need AJAX call for full implementation
        var headerHtml = '<td class="spec-label">Sản phẩm</td>';
        for (var i = 0; i < compareProducts.length; i++) {
            headerHtml += '<td class="product-column">Sản phẩm ' + (i + 1) + '</td>';
        }
        $('#product-row').html(headerHtml);
        
        // Add basic specs rows
        var specsRows = [
            'Giá',
            'Thương hiệu', 
            'Màn hình',
            'Vi xử lý',
            'RAM',
            'Bộ nhớ',
            'Camera',
            'Pin'
        ];
        
        var bodyHtml = '';
        specsRows.forEach(function(spec) {
            bodyHtml += '<tr><td class="spec-label">' + spec + '</td>';
            for (var i = 0; i < compareProducts.length; i++) {
                bodyHtml += '<td>Đang tải...</td>';
            }
            bodyHtml += '</tr>';
        });
        
        $('#compare-body').html(bodyHtml);
    }
    
    // Remove from compare
    $(document).on('click', '.remove-from-compare', function() {
        var productId = parseInt($(this).data('product-id'));
        removeFromCompare(productId);
    });
    
    function removeFromCompare(productId) {
        var index = compareProducts.indexOf(productId);
        if (index > -1) {
            compareProducts.splice(index, 1);
            localStorage.setItem('phonestore_compare', JSON.stringify(compareProducts));
            updateCompareDisplay();
            
            // Update quick button
            $('.quick-add-compare[data-product-id="' + productId + '"]')
                .removeClass('added').text('+ Thêm so sánh');
        }
    }
    
    // Clear all compare
    $('#clear-compare').on('click', function() {
        if (confirm('Bạn có chắc muốn xóa tất cả sản phẩm khỏi danh sách so sánh?')) {
            compareProducts = [];
            localStorage.setItem('phonestore_compare', JSON.stringify(compareProducts));
            updateCompareDisplay();
        }
    });
});

// JavaScript cải tiến cho trang so sánh sản phẩm
jQuery(document).ready(function($) {
    var compareProducts = JSON.parse(localStorage.getItem('phonestore_compare') || '[]');
    var maxProducts = 4;
    
    // Load existing compare table if any
    if (compareProducts.length > 0) {
        updateCompareDisplay();
    }
    
    // Search sản phẩm với debounce
    var searchTimeout;
    $('#product-search').on('input', function() {
        var searchTerm = $(this).val().trim();
        
        clearTimeout(searchTimeout);
        
        if (searchTerm.length >= 2) {
            // Hiển thị loading
            $('#search-results').html('<div class="search-loading">🔍 Đang tìm kiếm...</div>').show();
            
            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: phonestore_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'phonestore_search_products_compare',
                        term: searchTerm,
                        nonce: phonestore_ajax.nonce
                    },
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            showSearchResults(response.data);
                        } else {
                            $('#search-results').html('<div class="no-results">❌ Không tìm thấy sản phẩm nào</div>').show();
                        }
                    },
                    error: function() {
                        $('#search-results').html('<div class="search-error">⚠️ Lỗi khi tìm kiếm. Vui lòng thử lại.</div>').show();
                    }
                });
            }, 300);
        } else if (searchTerm.length === 0) {
            $('#search-results').hide();
        }
    });
    
    // Hiển thị kết quả tìm kiếm
    function showSearchResults(products) {
        var resultsHtml = '';
        
        products.forEach(function(product) {
            var isAdded = compareProducts.indexOf(product.id) !== -1;
            var buttonText = isAdded ? '✓ Đã thêm' : '+ Thêm vào so sánh';
            var buttonClass = isAdded ? 'added' : '';
            var buttonDisabled = isAdded || compareProducts.length >= maxProducts ? 'disabled' : '';
            
            resultsHtml += '<div class="search-result-item">';
            resultsHtml += '<img src="' + product.image + '" alt="' + product.title + '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">';
            resultsHtml += '<div class="product-info">';
            resultsHtml += '<h5>' + product.title + '</h5>';
            resultsHtml += '<span class="price">' + product.price + '</span>';
            resultsHtml += '</div>';
            resultsHtml += '<button class="add-to-compare ' + buttonClass + '" data-product-id="' + product.id + '" ' + buttonDisabled + '>' + buttonText + '</button>';
            resultsHtml += '</div>';
        });
        
        $('#search-results').html(resultsHtml).show();
    }
    
    // Thêm sản phẩm vào so sánh từ kết quả tìm kiếm
    $(document).on('click', '.add-to-compare', function() {
        var productId = parseInt($(this).data('product-id'));
        var button = $(this);
        
        if (button.hasClass('added') || button.prop('disabled')) {
            return;
        }
        
        if (compareProducts.length >= maxProducts) {
            alert('Chỉ có thể so sánh tối đa ' + maxProducts + ' sản phẩm!');
            return;
        }
        
        // Thêm vào danh sách
        compareProducts.push(productId);
        localStorage.setItem('phonestore_compare', JSON.stringify(compareProducts));
        
        // Cập nhật giao diện button
        button.addClass('added').text('✓ Đã thêm').prop('disabled', true);
        
        // Cập nhật hiển thị so sánh
        updateCompareDisplay();
        
        // Ẩn kết quả tìm kiếm và clear input
        $('#search-results').hide();
        $('#product-search').val('');
        
        // Scroll xuống bảng so sánh
        if ($('#compare-table-container').is(':visible')) {
            $('html, body').animate({
                scrollTop: $('#compare-table-container').offset().top - 100
            }, 500);
        }
    });
    
    // Quick add từ sản phẩm có sẵn
    $(document).on('click', '.quick-add-compare', function() {
        var productId = parseInt($(this).data('product-id'));
        var button = $(this);
        
        if (button.hasClass('added')) {
            return;
        }
        
        if (compareProducts.length >= maxProducts) {
            alert('Chỉ có thể so sánh tối đa ' + maxProducts + ' sản phẩm!');
            return;
        }
        
        compareProducts.push(productId);
        localStorage.setItem('phonestore_compare', JSON.stringify(compareProducts));
        
        button.addClass('added').text('✓ Đã thêm');
        
        updateCompareDisplay();
    });
    
    // Cập nhật hiển thị bảng so sánh
    function updateCompareDisplay() {
        if (compareProducts.length === 0) {
            $('#compare-table-container').hide();
            $('.quick-add-compare').removeClass('added').text('+ Thêm so sánh');
            return;
        }
        
        // Hiển thị container
        $('#compare-table-container').show();
        $('#compare-table-container h3').text('🔄 Bảng so sánh (' + compareProducts.length + ' sản phẩm)');
        
        // Hiển thị loading trong bảng
        $('#compare-table').html(`
            <tbody>
                <tr>
                    <td colspan="100%" style="text-align: center; padding: 40px;">
                        <div style="font-size: 18px;">⏳ Đang tải dữ liệu so sánh...</div>
                        <div style="margin-top: 10px; color: #6c757d;">Vui lòng đợi trong giây lát</div>
                    </td>
                </tr>
            </tbody>
        `);
        
        // Load dữ liệu qua AJAX
        $.ajax({
            url: phonestore_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'phonestore_load_compare_table',
                product_ids: compareProducts,
                nonce: phonestore_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#compare-table').html(response.data);
                    
                    // Thêm animation cho bảng
                    $('#compare-table').hide().fadeIn(300);
                    
                    // Update quick buttons state
                    compareProducts.forEach(function(id) {
                        $('.quick-add-compare[data-product-id="' + id + '"]')
                            .addClass('added').text('✓ Đã thêm');
                    });
                } else {
                    $('#compare-table').html(`
                        <tbody>
                            <tr>
                                <td colspan="100%" style="text-align: center; padding: 40px; color: #dc3545;">
                                    <div style="font-size: 18px;">❌ Không thể tải dữ liệu so sánh</div>
                                    <div style="margin-top: 10px;">Vui lòng thử lại sau</div>
                                    <button onclick="updateCompareDisplay()" style="margin-top: 15px; padding: 8px 16px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer;">🔄 Thử lại</button>
                                </td>
                            </tr>
                        </tbody>
                    `);
                }
            },
            error: function() {
                $('#compare-table').html(`
                    <tbody>
                        <tr>
                            <td colspan="100%" style="text-align: center; padding: 40px; color: #dc3545;">
                                <div style="font-size: 18px;">⚠️ Lỗi kết nối</div>
                                <div style="margin-top: 10px;">Vui lòng kiểm tra kết nối internet và thử lại</div>
                                <button onclick="location.reload()" style="margin-top: 15px; padding: 8px 16px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">🔄 Tải lại trang</button>
                            </td>
                        </tr>
                    </tbody>
                `);
            }
        });
    }
    
    // Xóa sản phẩm khỏi so sánh
    $(document).on('click', '.remove-from-compare', function() {
        var productId = parseInt($(this).data('product-id'));
        removeFromCompare(productId);
    });
    
    function removeFromCompare(productId) {
        var index = compareProducts.indexOf(productId);
        if (index > -1) {
            compareProducts.splice(index, 1);
            localStorage.setItem('phonestore_compare', JSON.stringify(compareProducts));
            
            // Update quick button
            $('.quick-add-compare[data-product-id="' + productId + '"]')
                .removeClass('added').text('+ Thêm so sánh');
            
            updateCompareDisplay();
            
            // Thông báo
            showNotification('✓ Đã xóa sản phẩm khỏi danh sách so sánh', 'success');
        }
    }
    
    // Xóa tất cả sản phẩm
    $('#clear-compare').on('click', function() {
        if (compareProducts.length === 0) return;
        
        if (confirm('🗑️ Bạn có chắc muốn xóa tất cả sản phẩm khỏi danh sách so sánh?')) {
            compareProducts = [];
            localStorage.setItem('phonestore_compare', JSON.stringify(compareProducts));
            
            $('.quick-add-compare').removeClass('added').text('+ Thêm so sánh');
            updateCompareDisplay();
            
            showNotification('✓ Đã xóa tất cả sản phẩm khỏi danh sách so sánh', 'success');
        }
    });
    
    // Ẩn kết quả tìm kiếm khi click bên ngoài
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-container').length) {
            $('#search-results').hide();
        }
    });
    
    // Focus vào ô tìm kiếm khi click
    $('#product-search').on('focus', function() {
        if ($(this).val().length >= 2) {
            $('#search-results').show();
        }
    });
    
    // Hàm hiển thị thông báo
    function showNotification(message, type = 'info') {
        // Tạo thông báo nếu chưa có
        if (!$('#compare-notification').length) {
            $('body').append('<div id="compare-notification" style="position: fixed; top: 20px; right: 20px; z-index: 9999; display: none;"></div>');
        }
        
        var bgColor = type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8';
        
        $('#compare-notification')
            .html('<div style="background: ' + bgColor + '; color: white; padding: 12px 20px; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">' + message + '</div>')
            .fadeIn(300)
            .delay(3000)
            .fadeOut(300);
    }
    
    // Make updateCompareDisplay global để có thể gọi từ HTML
    window.updateCompareDisplay = updateCompareDisplay;
});

// CSS inline để cải thiện giao diện
var compareStyles = `
<style>
.search-result-item {
    display: flex;
    align-items: center;
    padding: 12px;
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s;
}

.search-result-item:hover {
    background-color: #f8f9fa;
}

.search-result-item:last-child {
    border-bottom: none;
}

.search-result-item img {
    margin-right: 12px;
    flex-shrink: 0;
}

.search-result-item .product-info {
    flex-grow: 1;
    margin-right: 10px;
}

.search-result-item h5 {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 600;
}

.search-result-item .price {
    color: #e74c3c;
    font-weight: bold;
    font-size: 13px;
}

.add-to-compare {
    background: #007cba;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}

.add-to-compare:hover:not(:disabled):not(.added) {
    background: #005a87;
    transform: translateY(-1px);
}

.add-to-compare.added {
    background: #28a745;
    cursor: default;
}

.add-to-compare:disabled {
    background: #6c757d;
    cursor: not-allowed;
    opacity: 0.6;
}

.search-loading, .no-results, .search-error {
    padding: 20px;
    text-align: center;
    color: #6c757d;
    font-style: italic;
}

.search-error {
    color: #dc3545;
}

.compare-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

.compare-table th, .compare-table td {
    border: 1px solid #dee2e6;
    text-align: left;
    vertical-align: top;
}

.compare-table .spec-label {
    background: #f8f9fa;
    font-weight: bold;
    width: 200px;
    position: sticky;
    left: 0;
    z-index: 2;
}

.compare-table .product-column {
    background: #ffffff;
    text-align: center;
    min-width: 200px;
}

.compare-table .product-header {
    padding: 15px;
    border-bottom: 2px solid #dee2e6;
}

.compare-table .product-header img {
    display: block;
    margin: 0 auto 10px;
    border: 2px solid #f8f9fa;
}

.compare-table .product-header h4 a {
    color: #007cba;
    text-decoration: none;
}

.compare-table .product-header h4 a:hover {
    text-decoration: underline;
}

.compare-table .spec-value {
    font-size: 13px;
    line-height: 1.4;
}

.compare-table tr:nth-child(even) {
    background-color: rgba(0,0,0,0.02);
}

.compare-table tr:hover {
    background-color: rgba(0,124,186,0.05);
}

#search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 0 0 6px 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    max-height: 400px;
    overflow-y: auto;
    z-index: 1000;
}

.search-container {
    position: relative;
}

#product-search {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #ddd;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.2s;
}

#product-search:focus {
    outline: none;
    border-color: #007cba;
    box-shadow: 0 0 0 3px rgba(0,124,186,0.1);
}

.button-clear {
    background: #dc3545;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    margin-top: 20px;
    transition: all 0.2s;
}

.button-clear:hover {
    background: #c82333;
    transform: translateY(-1px);
}

.quick-add-compare {
    background: #17a2b8;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
    margin-top: 10px;
}

.quick-add-compare:hover:not(.added) {
    background: #138496;
}

.quick-add-compare.added {
    background: #28a745;
    cursor: default;
}

/* Responsive */
@media (max-width: 768px) {
    .compare-table {
        font-size: 12px;
    }
    
    .compare-table .spec-label {
        width: 120px;
        font-size: 11px;
    }
    
    .compare-table .product-column {
        min-width: 150px;
    }
    
    .compare-table .product-header img {
        width: 60px !important;
        height: 60px !important;
    }
    
    .search-result-item {
        flex-direction: column;
        text-align: center;
        padding: 15px 10px;
    }
    
    .search-result-item img {
        margin-right: 0;
        margin-bottom: 10px;
    }
    
    .search-result-item .product-info {
        margin-right: 0;
        margin-bottom: 10px;
    }
    
    #product-search {
        font-size: 14px;
    }
}

/* Animation cho việc thêm/xóa sản phẩm */
@keyframes slideInFromRight {
    0% {
        opacity: 0;
        transform: translateX(50px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

.compare-table .product-column {
    animation: slideInFromRight 0.3s ease-out;
}

/* Loading animation */
@keyframes pulse {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
    100% {
        opacity: 1;
    }
}

.search-loading {
    animation: pulse 1.5s infinite;
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}
</style>`;

document.head.insertAdjacentHTML('beforeend', compareStyles);
</script>


<?php get_footer(); ?>