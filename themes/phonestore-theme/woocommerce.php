<?php
/*
File: wp-content/themes/phonestore-theme/woocommerce.php
WooCommerce Shop Page Template with Search
*/

get_header();
?>

<div class="container">
    <?php if (is_shop()): ?>
        <!-- Shop Page Header -->
        <div class="shop-page-header">
            <h1>📱 Tất Cả Sản Phẩm</h1>
            <p>Khám phá bộ sưu tập điện thoại chính hãng với giá tốt nhất</p>
        </div>
        
        <!-- Filter Section -->
        <div class="shop-filter-section">
            <div class="filter-container">
                <h3>🔍 Bộ Lọc Sản Phẩm</h3>
                
                <form class="shop-filters-form" method="GET">
                    <div class="filters-row">
                        <div class="filter-group">
                            <label>Thương hiệu:</label>
                            <select name="filter_brand" id="filter_brand">
                                <option value="">Tất cả thương hiệu</option>
                                <option value="iphone" <?php selected(isset($_GET['filter_brand']) ? $_GET['filter_brand'] : '', 'iphone'); ?>>iPhone</option>
                                <option value="samsung" <?php selected(isset($_GET['filter_brand']) ? $_GET['filter_brand'] : '', 'samsung'); ?>>Samsung</option>
                                <option value="xiaomi" <?php selected(isset($_GET['filter_brand']) ? $_GET['filter_brand'] : '', 'xiaomi'); ?>>Xiaomi</option>
                                <option value="oppo" <?php selected(isset($_GET['filter_brand']) ? $_GET['filter_brand'] : '', 'oppo'); ?>>OPPO</option>
                                <option value="vivo" <?php selected(isset($_GET['filter_brand']) ? $_GET['filter_brand'] : '', 'vivo'); ?>>Vivo</option>
                                <option value="huawei" <?php selected(isset($_GET['filter_brand']) ? $_GET['filter_brand'] : '', 'huawei'); ?>>Huawei</option>
                                <option value="realme" <?php selected(isset($_GET['filter_brand']) ? $_GET['filter_brand'] : '', 'realme'); ?>>Realme</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label>Khoảng giá:</label>
                            <select name="filter_price" id="filter_price">
                                <option value="">Tất cả mức giá</option>
                                <option value="0-5" <?php selected(isset($_GET['filter_price']) ? $_GET['filter_price'] : '', '0-5'); ?>>Dưới 5 triệu</option>
                                <option value="5-10" <?php selected(isset($_GET['filter_price']) ? $_GET['filter_price'] : '', '5-10'); ?>>5-10 triệu</option>
                                <option value="10-15" <?php selected(isset($_GET['filter_price']) ? $_GET['filter_price'] : '', '10-15'); ?>>10-15 triệu</option>
                                <option value="15-20" <?php selected(isset($_GET['filter_price']) ? $_GET['filter_price'] : '', '15-20'); ?>>15-20 triệu</option>
                                <option value="20-30" <?php selected(isset($_GET['filter_price']) ? $_GET['filter_price'] : '', '20-30'); ?>>20-30 triệu</option>
                                <option value="30-100" <?php selected(isset($_GET['filter_price']) ? $_GET['filter_price'] : '', '30-100'); ?>>Trên 30 triệu</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label>RAM:</label>
                            <select name="filter_ram" id="filter_ram">
                                <option value="">Tất cả RAM</option>
                                <option value="3gb" <?php selected(isset($_GET['filter_ram']) ? $_GET['filter_ram'] : '', '3gb'); ?>>3GB</option>
                                <option value="4gb" <?php selected(isset($_GET['filter_ram']) ? $_GET['filter_ram'] : '', '4gb'); ?>>4GB</option>
                                <option value="6gb" <?php selected(isset($_GET['filter_ram']) ? $_GET['filter_ram'] : '', '6gb'); ?>>6GB</option>
                                <option value="8gb" <?php selected(isset($_GET['filter_ram']) ? $_GET['filter_ram'] : '', '8gb'); ?>>8GB</option>
                                <option value="12gb" <?php selected(isset($_GET['filter_ram']) ? $_GET['filter_ram'] : '', '12gb'); ?>>12GB</option>
                                <option value="16gb" <?php selected(isset($_GET['filter_ram']) ? $_GET['filter_ram'] : '', '16gb'); ?>>16GB+</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label>Bộ nhớ:</label>
                            <select name="filter_storage" id="filter_storage">
                                <option value="">Tất cả bộ nhớ</option>
                                <option value="64gb" <?php selected(isset($_GET['filter_storage']) ? $_GET['filter_storage'] : '', '64gb'); ?>>64GB</option>
                                <option value="128gb" <?php selected(isset($_GET['filter_storage']) ? $_GET['filter_storage'] : '', '128gb'); ?>>128GB</option>
                                <option value="256gb" <?php selected(isset($_GET['filter_storage']) ? $_GET['filter_storage'] : '', '256gb'); ?>>256GB</option>
                                <option value="512gb" <?php selected(isset($_GET['filter_storage']) ? $_GET['filter_storage'] : '', '512gb'); ?>>512GB</option>
                                <option value="1tb" <?php selected(isset($_GET['filter_storage']) ? $_GET['filter_storage'] : '', '1tb'); ?>>1TB</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label>Sắp xếp:</label>
                            <select name="orderby" id="orderby">
                                <option value="">Mặc định</option>
                                <option value="price" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'price'); ?>>Giá tăng dần</option>
                                <option value="price-desc" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'price-desc'); ?>>Giá giảm dần</option>
                                <option value="title" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'title'); ?>>Tên A-Z</option>
                                <option value="title-desc" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'title-desc'); ?>>Tên Z-A</option>
                                <option value="date" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'date'); ?>>Mới nhất</option>
                            </select>
                        </div>
                        
                        <div class="filter-actions">
                            <button type="submit" class="apply-btn">🔍 Áp dụng</button>
                            <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="clear-btn">🗑️ Xóa bộ lọc</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Search Section -->
        <div class="shop-search-section">
            <div class="search-container">
                <form class="shop-search-form" method="GET" action="<?php echo get_permalink(wc_get_page_id('shop')); ?>">
                    <div class="search-input-wrapper">
                        <input type="text" name="s" id="shop-product-search" 
                               placeholder="Tìm kiếm sản phẩm (VD: iPhone 15, Samsung Galaxy...)" 
                               value="<?php echo get_search_query(); ?>" autocomplete="off">
                        <button type="submit" id="shop-search-btn">🔍 Tìm kiếm</button>
                    </div>
                    
                    <!-- Preserve filter parameters -->
                    <?php if (isset($_GET['filter_brand']) && !empty($_GET['filter_brand'])): ?>
                        <input type="hidden" name="filter_brand" value="<?php echo esc_attr($_GET['filter_brand']); ?>">
                    <?php endif; ?>
                    <?php if (isset($_GET['filter_price']) && !empty($_GET['filter_price'])): ?>
                        <input type="hidden" name="filter_price" value="<?php echo esc_attr($_GET['filter_price']); ?>">
                    <?php endif; ?>
                    <?php if (isset($_GET['filter_ram']) && !empty($_GET['filter_ram'])): ?>
                        <input type="hidden" name="filter_ram" value="<?php echo esc_attr($_GET['filter_ram']); ?>">
                    <?php endif; ?>
                    <?php if (isset($_GET['filter_storage']) && !empty($_GET['filter_storage'])): ?>
                        <input type="hidden" name="filter_storage" value="<?php echo esc_attr($_GET['filter_storage']); ?>">
                    <?php endif; ?>
                    <?php if (isset($_GET['orderby']) && !empty($_GET['orderby'])): ?>
                        <input type="hidden" name="orderby" value="<?php echo esc_attr($_GET['orderby']); ?>">
                    <?php endif; ?>
                </form>
                
                <div class="search-suggestions" id="shop-search-suggestions" style="display: none;">
                    <!-- Search suggestions will appear here -->
                </div>
            </div>
        </div>
        
        <!-- Search Results Info -->
        <?php if (isset($_GET['s']) && !empty($_GET['s'])): ?>
            <div class="search-results-info">
                <h3>🔍 Kết quả tìm kiếm cho: "<strong><?php echo esc_html(get_search_query()); ?></strong>"</h3>
            </div>
        <?php endif; ?>
        
        <!-- Filter Results Info -->
        <?php 
        $active_filters = array();
        if (isset($_GET['filter_brand']) && !empty($_GET['filter_brand'])) {
            $brands = array('iphone' => 'iPhone', 'samsung' => 'Samsung', 'xiaomi' => 'Xiaomi', 'oppo' => 'OPPO', 'vivo' => 'Vivo', 'huawei' => 'Huawei', 'realme' => 'Realme');
            $active_filters[] = 'Thương hiệu: ' . $brands[$_GET['filter_brand']];
        }
        if (isset($_GET['filter_price']) && !empty($_GET['filter_price'])) {
            $prices = array('0-5' => 'Dưới 5 triệu', '5-10' => '5-10 triệu', '10-15' => '10-15 triệu', '15-20' => '15-20 triệu', '20-30' => '20-30 triệu', '30-100' => 'Trên 30 triệu');
            $active_filters[] = 'Giá: ' . $prices[$_GET['filter_price']];
        }
        if (isset($_GET['filter_ram']) && !empty($_GET['filter_ram'])) {
            $active_filters[] = 'RAM: ' . strtoupper($_GET['filter_ram']);
        }
        if (isset($_GET['filter_storage']) && !empty($_GET['filter_storage'])) {
            $active_filters[] = 'Bộ nhớ: ' . strtoupper($_GET['filter_storage']);
        }
        
        if (!empty($active_filters)): ?>
            <div class="active-filters-info">
                <h4>📌 Bộ lọc đang áp dụng:</h4>
                <div class="active-filters-list">
                    <?php foreach ($active_filters as $filter): ?>
                        <span class="filter-tag"><?php echo $filter; ?></span>
                    <?php endforeach; ?>
                    <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="clear-all-filters">✖️ Xóa tất cả</a>
                </div>
            </div>
        <?php endif; ?>
        
    <?php endif; ?>
    
    <!-- WooCommerce Content -->
    <div class="woocommerce-content">
        <?php woocommerce_content(); ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Search suggestions
    let searchTimeout;
    $('#shop-product-search').on('input', function() {
        const searchTerm = $(this).val().trim();
        
        clearTimeout(searchTimeout);
        
        if (searchTerm.length >= 2) {
            searchTimeout = setTimeout(function() {
                showSearchSuggestions(searchTerm);
            }, 300);
        } else {
            $('#shop-search-suggestions').hide();
        }
    });
    
    // Search suggestions data
    function showSearchSuggestions(term) {
        const suggestions = [
            'iPhone 15 Pro Max',
            'iPhone 15 Pro', 
            'iPhone 15',
            'iPhone 14 Pro Max',
            'iPhone 14 Pro',
            'iPhone 14',
            'Samsung Galaxy S24 Ultra',
            'Samsung Galaxy S24 Plus',
            'Samsung Galaxy S24',
            'Samsung Galaxy S23',
            'Xiaomi 14 Ultra',
            'Xiaomi 14',
            'Xiaomi 13T Pro',
            'Xiaomi Redmi Note 13',
            'OPPO Find X7 Ultra',
            'OPPO Find X6 Pro',
            'OPPO Reno 11',
            'Vivo X100 Pro',
            'Vivo V30',
            'Huawei P60 Pro',
            'Realme GT 5 Pro'
        ].filter(item => item.toLowerCase().includes(term.toLowerCase()));
        
        if (suggestions.length > 0) {
            const suggestionsHtml = suggestions.slice(0, 6).map(suggestion => 
                `<div class="suggestion-item" data-suggestion="${suggestion}">${suggestion}</div>`
            ).join('');
            
            $('#shop-search-suggestions').html(suggestionsHtml).show();
        } else {
            $('#shop-search-suggestions').hide();
        }
    }
    
    // Click suggestion
    $(document).on('click', '.suggestion-item', function() {
        const suggestion = $(this).data('suggestion');
        $('#shop-product-search').val(suggestion);
        $('#shop-search-suggestions').hide();
        $('.shop-search-form').submit();
    });
    
    // Hide suggestions when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-container').length) {
            $('#shop-search-suggestions').hide();
        }
    });
    
    // Filter form enhancements
    $('.shop-filters-form select').on('change', function() {
        $(this).closest('form').submit();
    });
    
    // Search form submit enhancement
    $('.shop-search-form').on('submit', function(e) {
        const searchValue = $('#shop-product-search').val().trim();
        if (searchValue === '') {
            e.preventDefault();
            $('#shop-product-search').focus();
            return false;
        }
    });
    
    // Auto-focus search when page loads with search query
    if ($('#shop-product-search').val() !== '') {
        $('html, body').animate({
            scrollTop: $('.shop-search-section').offset().top - 100
        }, 500);
    }
});
</script>

<style>
/* === SHOP PAGE STYLES === */
.shop-page-header {
    text-align: center;
    padding: 40px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    margin: 20px 0 30px 0;
}

.shop-page-header h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
    font-weight: 800;
}

.shop-page-header p {
    font-size: 1.2rem;
    opacity: 0.9;
}

/* Filter Section */
.shop-filter-section {
    background: #f8fafc;
    padding: 25px;
    border-radius: 15px;
    margin: 20px 0;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.filter-container h3 {
    color: #2d3748;
    margin-bottom: 20px;
    text-align: center;
    font-size: 1.3rem;
}

.filters-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    color: #4a5568;
    font-weight: 600;
    margin-bottom: 5px;
    font-size: 14px;
}

.filter-group select {
    padding: 10px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    font-size: 14px;
    transition: border-color 0.3s;
}

.filter-group select:focus {
    outline: none;
    border-color: #38a169;
    box-shadow: 0 0 0 3px rgba(56, 161, 105, 0.1);
}

.filter-actions {
    display: flex;
    gap: 10px;
}

.apply-btn, .clear-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.apply-btn {
    background: #38a169;
    color: white;
}

.apply-btn:hover {
    background: #2f855a;
    transform: translateY(-2px);
}

.clear-btn {
    background: #e2e8f0;
    color: #4a5568;
}

.clear-btn:hover {
    background: #cbd5e0;
    transform: translateY(-2px);
}

/* === SEARCH SECTION === */
.shop-search-section {
    margin: 30px 0;
    padding: 25px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.search-container {
    max-width: 800px;
    margin: 0 auto;
    position: relative;
}

.search-input-wrapper {
    display: flex;
    gap: 15px;
    align-items: center;
    background: #f8fafc;
    padding: 8px;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    transition: all 0.3s;
}

.search-input-wrapper:focus-within {
    border-color: #38a169;
    box-shadow: 0 0 0 4px rgba(56, 161, 105, 0.1);
}

#shop-product-search {
    flex: 1;
    padding: 15px 20px;
    border: none;
    background: transparent;
    font-size: 16px;
    outline: none;
}

#shop-product-search::placeholder {
    color: #a0aec0;
}

#shop-search-btn {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    color: white;
    border: none;
    padding: 15px 25px;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 16px;
}

#shop-search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(56, 161, 105, 0.3);
}

/* Search Suggestions */
.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-top: 5px;
    max-height: 300px;
    overflow-y: auto;
    z-index: 100;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.suggestion-item {
    padding: 12px 20px;
    cursor: pointer;
    transition: background 0.2s;
    border-bottom: 1px solid #f7fafc;
    font-size: 14px;
}

.suggestion-item:hover {
    background: #f8fafc;
    color: #38a169;
}

.suggestion-item:last-child {
    border-bottom: none;
}

/* Search Results Info */
.search-results-info {
    background: #e6fffa;
    border: 1px solid #81e6d9;
    border-radius: 8px;
    padding: 15px 20px;
    margin: 20px 0;
}

.search-results-info h3 {
    color: #234e52;
    margin: 0;
    font-size: 16px;
}

/* Active Filters Info */
.active-filters-info {
    background: #fef5e7;
    border: 1px solid #f6e05e;
    border-radius: 8px;
    padding: 15px 20px;
    margin: 20px 0;
}

.active-filters-info h4 {
    color: #744210;
    margin: 0 0 10px 0;
    font-size: 14px;
}

.active-filters-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.filter-tag {
    background: #ed8936;
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.clear-all-filters {
    background: #e53e3e;
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.3s;
}

.clear-all-filters:hover {
    background: #c53030;
}

/* WooCommerce Content Styling */
.woocommerce-content {
    margin-top: 30px;
}

/* Override WooCommerce default styles */
.woocommerce .products {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)) !important;
    gap: 25px !important;
}

.woocommerce .products li.product {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    float: none !important;
    width: auto !important;
    margin: 0 !important;
}

.woocommerce .products li.product:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.woocommerce .products li.product img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s;
}

.woocommerce .products li.product:hover img {
    transform: scale(1.05);
}

.woocommerce .products li.product .woocommerce-loop-product__title {
    color: #2d3748;
    font-size: 16px;
    font-weight: 700;
    margin: 15px 0 10px 0;
    line-height: 1.4;
}

.woocommerce .products li.product .price {
    color: #e53e3e;
    font-size: 18px;
    font-weight: 800;
    margin: 10px 0;
}

.woocommerce .products li.product .button {
    background: #38a169;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
    width: 100%;
    margin-top: 10px;
}

.woocommerce .products li.product .button:hover {
    background: #2f855a;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .shop-page-header h1 {
        font-size: 2rem;
    }
    
    .filters-row {
        grid-template-columns: 1fr;
    }
    
    .filter-actions {
        grid-column: 1;
        flex-direction: column;
    }
    
    .search-input-wrapper {
        flex-direction: column;
        gap: 10px;
    }
    
    #shop-search-btn {
        width: 100%;
    }
    
    .active-filters-list {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .woocommerce .products {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important;
        gap: 20px !important;
    }
}

@media (max-width: 480px) {
    .shop-filter-section,
    .shop-search-section {
        padding: 15px;
    }
    
    .woocommerce .products {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php get_footer(); ?>