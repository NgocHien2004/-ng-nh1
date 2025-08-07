<?php
/*
Template Name: So Sánh Sản Phẩm
*/

get_header();
?>

<div class="container">
    <div class="compare-page-header">
        <h1>⚖️ So Sánh Sản Phẩm</h1>
        <p>So sánh thông số kỹ thuật chi tiết giữa các điện thoại để chọn được sản phẩm phù hợp nhất</p>
    </div>
    
    <!-- Search & Add Products Section -->
    <div class="compare-search-section">
        <div class="search-container">
            <h3>🔍 Thêm sản phẩm để so sánh (tối đa 4 sản phẩm)</h3>
            <div class="product-search-wrapper">
                <input type="text" id="product-search" placeholder="Tìm kiếm sản phẩm để thêm vào so sánh..." autocomplete="off">
                <div id="search-results" class="search-dropdown"></div>
            </div>
        </div>
        
        <!-- Selected Products for Compare -->
        <div id="selected-products" class="selected-products">
            <h4>📱 Sản phẩm đã chọn:</h4>
            <div id="selected-list" class="selected-list">
                <div class="empty-state">
                    <span>Chưa có sản phẩm nào được chọn</span>
                </div>
            </div>
            <button id="start-compare" class="start-compare-btn" disabled>
                🚀 Bắt đầu so sánh
            </button>
        </div>
    </div>
    
    <!-- Compare Table Section -->
    <div id="compare-table-section" class="compare-table-section" style="display: none;">
        <div class="compare-header">
            <h3>📊 Bảng so sánh chi tiết</h3>
            <button id="clear-all" class="clear-all-btn">🗑️ Xóa tất cả</button>
        </div>
        
        <div class="compare-table-wrapper">
            <table id="compare-table" class="compare-table">
                <!-- Content will be loaded via AJAX -->
            </table>
        </div>
    </div>
    
    <!-- Quick Compare với sản phẩm phổ biến -->
    <div class="quick-compare-section">
        <h3>⚡ So sánh nhanh</h3>
        <p>Chọn 2-3 sản phẩm phổ biến để so sánh ngay:</p>
        
        <div class="quick-compare-grid">
            <?php
            // Lấy sản phẩm phổ biến
            if (class_exists('WooCommerce')) {
                $popular_products = wc_get_products([
                    'limit' => 6,
                    'orderby' => 'popularity',
                    'status' => 'publish'
                ]);
            } else {
                $popular_products = get_posts([
                    'post_type' => 'product',
                    'posts_per_page' => 6,
                    'post_status' => 'publish'
                ]);
            }
            
            if (!empty($popular_products)) {
                foreach ($popular_products as $product):
                    if (is_object($product) && method_exists($product, 'get_id')) {
                        $product_id = $product->get_id();
                        $product_name = $product->get_name();
                        $product_price = $product->get_price_html();
                        $product_image = $product->get_image('thumbnail');
                    } else {
                        $product_id = $product->ID;
                        $product_name = $product->post_title;
                        $wc_product = wc_get_product($product_id);
                        $product_price = $wc_product ? $wc_product->get_price_html() : 'Liên hệ';
                        $product_image = get_the_post_thumbnail($product_id, 'thumbnail');
                        if (!$product_image) {
                            $product_image = '<img src="' . wc_placeholder_img_src() . '" alt="No Image">';
                        }
                    }
                ?>
                    <div class="quick-product-card">
                        <div class="product-image">
                            <?php echo $product_image; ?>
                        </div>
                        <div class="product-info">
                            <h4><?php echo esc_html($product_name); ?></h4>
                            <div class="price"><?php echo $product_price; ?></div>
                            
                            <?php
                            // Hiển thị specs nếu có
                            if (function_exists('get_field')) {
                                $ram = get_field('ram', $product_id);
                                $storage = get_field('storage', $product_id);
                                $brand = get_field('brand', $product_id);
                                
                                if ($ram || $storage || $brand) {
                                    echo '<div class="quick-specs">';
                                    if ($brand) echo '<span>📱 ' . ucfirst($brand) . '</span>';
                                    if ($ram) echo '<span>💾 ' . strtoupper($ram) . '</span>';
                                    if ($storage) echo '<span>💿 ' . strtoupper($storage) . '</span>';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                        <button class="quick-add-btn" data-product-id="<?php echo $product_id; ?>">
                            ➕ Thêm so sánh
                        </button>
                    </div>
                <?php endforeach; 
            } else {
                echo '<p>Chưa có sản phẩm nào để so sánh. Vui lòng thêm sản phẩm vào WooCommerce.</p>';
            }
            ?>
        </div>
    </div>
    
    <!-- How to use section -->
    <div class="how-to-use-section">
        <h3>❓ Cách sử dụng</h3>
        <div class="steps-grid">
            <div class="step">
                <div class="step-number">1</div>
                <h4>Tìm kiếm sản phẩm</h4>
                <p>Gõ tên điện thoại vào ô tìm kiếm hoặc chọn từ danh sách sản phẩm phổ biến</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h4>Thêm vào so sánh</h4>
                <p>Click "Thêm so sánh" để thêm sản phẩm vào danh sách so sánh (tối đa 4 sản phẩm)</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h4>Xem kết quả</h4>
                <p>Bảng so sánh sẽ hiển thị chi tiết thông số kỹ thuật để bạn dễ dàng đưa ra quyết định</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Compare Page Styles */
.compare-page-header {
    text-align: center;
    padding: 40px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    margin: 20px 0 40px 0;
}

.compare-page-header h1 {
    font-size: 2.2rem;
    margin-bottom: 10px;
}

.compare-page-header p {
    font-size: 1.1rem;
    opacity: 0.9;
}

.compare-search-section {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 15px;
    margin: 30px 0;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.search-container h3 {
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

.product-search-wrapper {
    position: relative;
    max-width: 600px;
    margin: 0 auto 30px auto;
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

.search-dropdown {
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
    display: none;
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

.search-result-item .product-details {
    flex: 1;
}

.search-result-item h5 {
    margin: 0 0 5px 0;
    color: #333;
    font-size: 16px;
}

.search-result-item .price {
    font-weight: bold;
    color: #e74c3c;
    font-size: 14px;
}

.add-to-compare-btn {
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

.add-to-compare-btn:hover {
    background: #218838;
}

.add-to-compare-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
}

.selected-products {
    text-align: center;
}

.selected-products h4 {
    color: #333;
    margin-bottom: 20px;
}

.selected-list {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.empty-state {
    color: #666;
    font-style: italic;
}

.selected-product {
    background: #007cba;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.remove-product {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    padding: 0;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.start-compare-btn {
    background: #28a745;
    color: white;
    padding: 15px 30px;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
}

.start-compare-btn:hover:not(:disabled) {
    background: #218838;
}

.start-compare-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
}

.compare-table-section {
    margin: 40px 0;
}

.compare-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.compare-header h3 {
    color: #333;
    font-size: 1.8rem;
}

.clear-all-btn {
    background: #dc3545;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: background 0.3s;
}

.clear-all-btn:hover {
    background: #c82333;
}

.compare-table-wrapper {
    overflow-x: auto;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.compare-table {
    width: 100%;
    min-width: 800px;
    border-collapse: collapse;
    background: white;
}

.compare-table td,
.compare-table th {
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

.product-header {
    position: relative;
    padding: 20px;
}

.product-header img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    margin-bottom: 15px;
    border-radius: 10px;
}

.product-header h4 {
    margin: 10px 0;
    color: #333;
    font-size: 16px;
    line-height: 1.4;
}

.product-header h4 a {
    color: inherit;
    text-decoration: none;
}

.product-header h4 a:hover {
    color: #007cba;
}

.product-header .price {
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

.quick-compare-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.quick-product-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.quick-product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.1);
}

.quick-product-card .product-image img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 10px;
}

.quick-product-card h4 {
    margin: 10px 0;
    color: #333;
    font-size: 14px;
    line-height: 1.3;
}

.quick-product-card .price {
    font-weight: bold;
    color: #e74c3c;
    margin: 8px 0;
    font-size: 14px;
}

.quick-specs {
    display: flex;
    justify-content: center;
    gap: 5px;
    margin: 10px 0;
    flex-wrap: wrap;
}

.quick-specs span {
    background: #e2e8f0;
    color: #4a5568;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 10px;
}

.quick-add-btn {
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

.quick-add-btn:hover {
    background: #005a87;
}

.quick-add-btn.added {
    background: #28a745;
    cursor: default;
}

.how-to-use-section {
    margin: 50px 0;
}

.how-to-use-section h3 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
    font-size: 2rem;
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.step {
    text-align: center;
    padding: 30px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.step-number {
    width: 60px;
    height: 60px;
    background: #38a169;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    margin: 0 auto 20px auto;
}

.step h4 {
    color: #333;
    margin-bottom: 15px;
    font-size: 18px;
}

.step p {
    color: #666;
    line-height: 1.6;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .compare-page-header h1 {
        font-size: 1.8rem;
    }
    
    .compare-search-section {
        padding: 20px;
    }
    
    .compare-table {
        font-size: 12px;
    }
    
    .product-header img {
        width: 80px;
        height: 80px;
    }
    
    .product-header h4 {
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
    
    .quick-compare-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }
    
    .steps-grid {
       grid-template-columns: 1fr;
       gap: 20px;
   }
   
   .compare-header {
       flex-direction: column;
       gap: 15px;
       text-align: center;
   }
   
   .selected-list {
       flex-direction: column;
       align-items: center;
   }
}
</style>

<script>
jQuery(document).ready(function($) {
   let selectedProducts = [];
   const maxProducts = 4;
   
   // Product search functionality
   let searchTimeout;
   $('#product-search').on('input', function() {
       const searchTerm = $(this).val().trim();
       
       clearTimeout(searchTimeout);
       
       if (searchTerm.length < 2) {
           $('#search-results').hide().empty();
           return;
       }
       
       searchTimeout = setTimeout(function() {
           searchProducts(searchTerm);
       }, 300);
   });
   
   // Search products via AJAX
   function searchProducts(term) {
       $.ajax({
           url: phonestore_ajax.ajax_url,
           type: 'POST',
           data: {
               action: 'phonestore_search_products_compare',
               term: term,
               nonce: phonestore_ajax.nonce
           },
           success: function(response) {
               if (response.success && response.data.length > 0) {
                   displaySearchResults(response.data);
               } else {
                   $('#search-results').html('<div class="search-result-item"><p>Không tìm thấy sản phẩm nào.</p></div>').show();
               }
           },
           error: function() {
               $('#search-results').html('<div class="search-result-item"><p>Lỗi khi tìm kiếm. Vui lòng thử lại.</p></div>').show();
           }
       });
   }
   
   // Display search results
   function displaySearchResults(products) {
       const resultsHtml = products.map(product => `
           <div class="search-result-item" data-product-id="${product.id}">
               <img src="${product.image}" alt="${product.title}">
               <div class="product-details">
                   <h5>${product.title}</h5>
                   <div class="price">${product.price}</div>
               </div>
               <button class="add-to-compare-btn" ${selectedProducts.includes(product.id) ? 'disabled' : ''}>
                   ${selectedProducts.includes(product.id) ? '✓ Đã thêm' : '+ Thêm'}
               </button>
           </div>
       `).join('');
       
       $('#search-results').html(resultsHtml).show();
   }
   
   // Add product to compare
   $(document).on('click', '.add-to-compare-btn, .quick-add-btn', function() {
       const productId = parseInt($(this).data('product-id') || $(this).closest('.search-result-item').data('product-id'));
       
       if (selectedProducts.length >= maxProducts) {
           alert('Tối đa chỉ có thể so sánh 4 sản phẩm cùng lúc!');
           return;
       }
       
       if (!selectedProducts.includes(productId)) {
           addProductToCompare(productId);
       }
   });
   
   // Add product to compare list
   function addProductToCompare(productId) {
       $.ajax({
           url: phonestore_ajax.ajax_url,
           type: 'POST',
           data: {
               action: 'phonestore_get_product_compare',
               product_id: productId,
               nonce: phonestore_ajax.nonce
           },
           success: function(response) {
               if (response.success) {
                   selectedProducts.push(productId);
                   updateSelectedProductsList();
                   
                   // Update UI
                   $(`.add-to-compare-btn[data-product-id="${productId}"], .quick-add-btn[data-product-id="${productId}"]`)
                       .prop('disabled', true)
                       .text('✓ Đã thêm')
                       .addClass('added');
                   
                   // Update search results if visible
                   $(`.search-result-item[data-product-id="${productId}"] .add-to-compare-btn`)
                       .prop('disabled', true)
                       .text('✓ Đã thêm');
                       
                   // Hide search results
                   $('#search-results').hide();
                   $('#product-search').val('');
               } else {
                   alert('Không thể thêm sản phẩm vào so sánh. Vui lòng thử lại.');
               }
           },
           error: function() {
               alert('Lỗi kết nối. Vui lòng thử lại.');
           }
       });
   }
   
   // Update selected products list
   function updateSelectedProductsList() {
       if (selectedProducts.length === 0) {
           $('#selected-list').html('<div class="empty-state"><span>Chưa có sản phẩm nào được chọn</span></div>');
           $('#start-compare').prop('disabled', true);
           $('#compare-table-section').hide();
           return;
       }
       
       const selectedHtml = selectedProducts.map(productId => `
           <div class="selected-product" data-product-id="${productId}">
               <span>Sản phẩm #${productId}</span>
               <button class="remove-product" data-product-id="${productId}">×</button>
           </div>
       `).join('');
       
       $('#selected-list').html(selectedHtml);
       $('#start-compare').prop('disabled', selectedProducts.length < 2);
       
       // Auto-update compare table if visible
       if (selectedProducts.length >= 2 && $('#compare-table-section').is(':visible')) {
           updateCompareTable();
       }
   }
   
   // Remove product from compare
   $(document).on('click', '.remove-product', function() {
       const productId = parseInt($(this).data('product-id'));
       selectedProducts = selectedProducts.filter(id => id !== productId);
       updateSelectedProductsList();
       
       // Re-enable add buttons
       $(`.add-to-compare-btn[data-product-id="${productId}"], .quick-add-btn[data-product-id="${productId}"]`)
           .prop('disabled', false)
           .text('+ Thêm')
           .removeClass('added');
   });
   
   // Start compare
   $('#start-compare').on('click', function() {
       if (selectedProducts.length >= 2) {
           updateCompareTable();
           $('#compare-table-section').show();
           $('html, body').animate({
               scrollTop: $('#compare-table-section').offset().top - 100
           }, 800);
       }
   });
}
   // Update compare table
   function updateCompareTable() {
    if (selectedProducts.length < 2) {
        $('#compare-table-section').hide();
        return;
    }
    
    // Show loading
    $('#compare-table').html('<tbody><tr><td colspan="100%" style="text-align: center; padding: 40px;">⏳ Đang tải dữ liệu so sánh...</td></tr></tbody>');
    
    // Load compare data via AJAX
    $.ajax({
        url: phonestore_ajax.ajax_url,
        type: 'POST',
        data: {
            action: 'phonestore_load_compare_table',
            product_ids: selectedProducts,
            nonce: phonestore_ajax.nonce
        },
        success: function(response) {
            console.log('AJAX Response:', response); // Debug log
            if (response.success) {
                $('#compare-table').html(response.data);
                $('#compare-table-section').show();
            } else {
                $('#compare-table').html('<tbody><tr><td colspan="100%" style="text-align: center; padding: 40px;">❌ Không thể tải dữ liệu so sánh. ' + (response.data || '') + '</td></tr></tbody>');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr, status, error); // Debug log
            $('#compare-table').html('<tbody><tr><td colspan="100%" style="text-align: center; padding: 40px;">❌ Lỗi kết nối: ' + error + '</td></tr></tbody>');
        }
    });
}
   
   // Remove from compare table
   $(document).on('click', '.remove-from-compare', function() {
       const productId = parseInt($(this).data('product-id'));
       selectedProducts = selectedProducts.filter(id => id !== productId);
       updateSelectedProductsList();
       
       // Re-enable add buttons
       $(`.add-to-compare-btn[data-product-id="${productId}"], .quick-add-btn[data-product-id="${productId}"]`)
           .prop('disabled', false)
           .text('+ Thêm')
           .removeClass('added');
   });
   
   // Clear all products
   $('#clear-all').on('click', function() {
       if (confirm('Bạn có chắc muốn xóa tất cả sản phẩm khỏi danh sách so sánh?')) {
           selectedProducts = [];
           updateSelectedProductsList();
           $('#compare-table-section').hide();
           
           // Re-enable all add buttons
           $('.add-to-compare-btn, .quick-add-btn')
               .prop('disabled', false)
               .text('+ Thêm')
               .removeClass('added');
       }
   });
   
   // Hide search results when clicking outside
   $(document).on('click', function(e) {
       if (!$(e.target).closest('.product-search-wrapper').length) {
           $('#search-results').hide();
       }
   });
   
   // Clear search when clicking search input
   $('#product-search').on('focus', function() {
       $(this).select();
   });
});
</script>

<?php get_footer(); ?>