<?php
/*
Template Name: Sản Phẩm
*/

get_header();
?>

<div class="container">
    <div class="products-page-header">
        <h1>📱 Tất Cả Sản Phẩm</h1>
        <p>Khám phá bộ sưu tập điện thoại chính hãng với giá tốt nhất</p>
    </div>
    
    <!-- Filter Section -->
    <div class="products-filter-section">
        <div class="filter-container">
            <h3>🔍 Bộ Lọc Sản Phẩm</h3>
            
            <div class="filters-row">
                <div class="filter-group">
                    <label>Thương hiệu:</label>
                    <select id="brand-filter">
                        <option value="">Tất cả thương hiệu</option>
                        <option value="iphone">iPhone</option>
                        <option value="samsung">Samsung</option>
                        <option value="xiaomi">Xiaomi</option>
                        <option value="oppo">OPPO</option>
                        <option value="vivo">Vivo</option>
                        <option value="huawei">Huawei</option>
                        <option value="realme">Realme</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Khoảng giá:</label>
                    <select id="price-filter">
                        <option value="">Tất cả mức giá</option>
                        <option value="0-5">Dưới 5 triệu</option>
                        <option value="5-10">5-10 triệu</option>
                        <option value="10-15">10-15 triệu</option>
                        <option value="15-20">15-20 triệu</option>
                        <option value="20-30">20-30 triệu</option>
                        <option value="30-100">Trên 30 triệu</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>RAM:</label>
                    <select id="ram-filter">
                        <option value="">Tất cả RAM</option>
                        <option value="3gb">3GB</option>
                        <option value="4gb">4GB</option>
                        <option value="6gb">6GB</option>
                        <option value="8gb">8GB</option>
                        <option value="12gb">12GB</option>
                        <option value="16gb">16GB+</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Bộ nhớ:</label>
                    <select id="storage-filter">
                        <option value="">Tất cả bộ nhớ</option>
                        <option value="64gb">64GB</option>
                        <option value="128gb">128GB</option>
                        <option value="256gb">256GB</option>
                        <option value="512gb">512GB</option>
                        <option value="1tb">1TB</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Sắp xếp:</label>
                    <select id="sort-filter">
                        <option value="">Mặc định</option>
                        <option value="price-asc">Giá tăng dần</option>
                        <option value="price-desc">Giá giảm dần</option>
                        <option value="name-asc">Tên A-Z</option>
                        <option value="name-desc">Tên Z-A</option>
                        <option value="newest">Mới nhất</option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button id="apply-filters" class="apply-btn">🔍 Áp dụng</button>
                    <button id="clear-filters" class="clear-btn">🗑️ Xóa bộ lọc</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search Section - ĐÂY LÀ PHẦN SEARCH -->
    <div class="products-search-section">
        <div class="search-container">
            <div class="search-input-wrapper">
                <input type="text" id="product-search" placeholder="Tìm kiếm sản phẩm (VD: iPhone 15, Samsung Galaxy...)" autocomplete="off">
                <button id="search-btn" type="button">🔍 Tìm kiếm</button>
            </div>
            <div class="search-suggestions" id="search-suggestions" style="display: none;">
                <!-- Gợi ý sẽ hiển thị ở đây -->
            </div>
        </div>
    </div>
    
    <!-- Products Grid -->
    <div class="products-section">
        <div class="products-header">
            <h2>📦 Danh Sách Sản Phẩm</h2>
            <div class="products-count">
                <span id="products-count">Đang tải...</span>
            </div>
        </div>
        
        <div id="products-loading" class="loading-spinner" style="display: none;">
            <div class="spinner">⏳</div>
            <p>Đang tải sản phẩm...</p>
        </div>
        
        <div id="products-grid" class="products-grid">
            <!-- Sản phẩm sẽ được load bằng JavaScript -->
        </div>
        
        <div id="no-products" class="no-products" style="display: none;">
            <div class="no-products-icon">📱</div>
            <h3>Không tìm thấy sản phẩm</h3>
            <p>Vui lòng thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
            <button id="show-all-products" class="show-all-btn">📱 Hiển thị tất cả sản phẩm</button>
        </div>
        
        <!-- Load More Button -->
        <div class="load-more-section">
            <button id="load-more" class="load-more-btn" style="display: none;">
                📦 Xem thêm sản phẩm
            </button>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    let currentPage = 1;
    let isLoading = false;
    let hasMoreProducts = true;
    
    // Load initial products
    loadProducts();
    
    // URL parameter handling
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('brand')) {
        $('#brand-filter').val(urlParams.get('brand'));
        loadProducts(true);
    }
    
    // Search functionality
    let searchTimeout;
    $('#product-search').on('input', function() {
        const searchTerm = $(this).val().trim();
        
        clearTimeout(searchTimeout);
        
        if (searchTerm.length >= 2) {
            searchTimeout = setTimeout(function() {
                showSearchSuggestions(searchTerm);
            }, 300);
        } else {
            $('#search-suggestions').hide();
        }
    });
    
    // Search suggestions
    function showSearchSuggestions(term) {
        const suggestions = [
            'iPhone 15 Pro Max',
            'iPhone 15 Pro', 
            'iPhone 14',
            'Samsung Galaxy S24 Ultra',
            'Samsung Galaxy S24',
            'Xiaomi 14 Ultra',
            'Xiaomi 13T Pro',
            'OPPO Find X7 Ultra',
            'Vivo X100 Pro'
        ].filter(item => item.toLowerCase().includes(term.toLowerCase()));
        
        if (suggestions.length > 0) {
            const suggestionsHtml = suggestions.slice(0, 5).map(suggestion => 
                `<div class="suggestion-item" data-suggestion="${suggestion}">${suggestion}</div>`
            ).join('');
            
            $('#search-suggestions').html(suggestionsHtml).show();
        } else {
            $('#search-suggestions').hide();
        }
    }
    
    // Click suggestion
    $(document).on('click', '.suggestion-item', function() {
        const suggestion = $(this).data('suggestion');
        $('#product-search').val(suggestion);
        $('#search-suggestions').hide();
        performSearch();
    });
    
    // Hide suggestions when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-container').length) {
            $('#search-suggestions').hide();
        }
    });
    
    // Search button click
    $('#search-btn').on('click', function() {
        performSearch();
    });
    
    // Search on Enter key
    $('#product-search').on('keypress', function(e) {
        if (e.which === 13) {
            performSearch();
        }
    });
    
    function performSearch() {
        currentPage = 1;
        hasMoreProducts = true;
        loadProducts(true);
        $('#search-suggestions').hide();
    }
    
    // Apply filters
    $('#apply-filters').on('click', function() {
        currentPage = 1;
        hasMoreProducts = true;
        loadProducts(true);
    });
    
    // Clear filters
    $('#clear-filters').on('click', function() {
        $('.filter-container select').val('');
        $('#product-search').val('');
        currentPage = 1;
        hasMoreProducts = true;
        loadProducts(true);
        
        // Update URL
        if (history.pushState) {
            history.pushState(null, null, window.location.pathname);
        }
    });
    
    // Show all products
    $('#show-all-products').on('click', function() {
        $('#clear-filters').click();
    });
    
    // Load more products
    $('#load-more').on('click', function() {
        currentPage++;
        loadProducts(false);
    });
    
    // Load products function
    function loadProducts(clearExisting = false) {
        if (isLoading) return;
        
        isLoading = true;
        $('#products-loading').show();
        
        if (clearExisting) {
            $('#products-grid').empty();
            $('#no-products').hide();
            $('#load-more').hide();
        }
        
        const filters = {
            brand: $('#brand-filter').val(),
            price: $('#price-filter').val(),
            ram: $('#ram-filter').val(),
            storage: $('#storage-filter').val(),
            sort: $('#sort-filter').val(),
            search: $('#product-search').val(),
            page: currentPage
        };
        
        // Simulate loading products (fallback when no AJAX endpoint)
        setTimeout(function() {
            loadMockProducts(filters, clearExisting);
        }, 500);
    }
    
    // Mock products for demo
    function loadMockProducts(filters, clearExisting) {
        const mockProducts = [
            {
                id: 1,
                name: 'iPhone 15 Pro Max',
                price: '₫29,990,000',
                image: 'https://via.placeholder.com/300x300/007cba/ffffff?text=iPhone+15+Pro',
                brand: 'iphone',
                ram: '8gb',
                storage: '256gb',
                specs: ['💾 8GB RAM', '💿 256GB', '📷 48MP'],
                url: '#'
            },
            {
                id: 2,
                name: 'Samsung Galaxy S24 Ultra',
                price: '₫32,990,000',
                image: 'https://via.placeholder.com/300x300/1f4788/ffffff?text=Galaxy+S24',
                brand: 'samsung',
                ram: '12gb',
                storage: '512gb',
                specs: ['💾 12GB RAM', '💿 512GB', '📷 200MP'],
                url: '#'
            },
            {
                id: 3,
                name: 'Xiaomi 14 Ultra',
                price: '₫24,990,000',
                image: 'https://via.placeholder.com/300x300/ff6900/ffffff?text=Xiaomi+14',
                brand: 'xiaomi',
                ram: '12gb',
                storage: '512gb',
                specs: ['💾 12GB RAM', '💿 512GB', '📷 50MP'],
                url: '#'
            },
            {
                id: 4,
                name: 'OPPO Find X7 Ultra',
                price: '₫27,990,000',
                image: 'https://via.placeholder.com/300x300/1ba784/ffffff?text=OPPO+Find',
                brand: 'oppo',
                ram: '16gb',
                storage: '512gb',
                specs: ['💾 16GB RAM', '💿 512GB', '📷 50MP'],
                url: '#'
            },
            {
                id: 5,
                name: 'Vivo X100 Pro',
                price: '₫19,990,000',
                image: 'https://via.placeholder.com/300x300/4c6ef5/ffffff?text=Vivo+X100',
                brand: 'vivo',
                ram: '12gb',
                storage: '256gb',
                specs: ['💾 12GB RAM', '💿 256GB', '📷 50MP'],
                url: '#'
            },
            {
                id: 6,
                name: 'iPhone 14 Pro',
                price: '₫24,990,000',
                image: 'https://via.placeholder.com/300x300/007cba/ffffff?text=iPhone+14',
                brand: 'iphone',
                ram: '6gb',
                storage: '128gb',
                specs: ['💾 6GB RAM', '💿 128GB', '📷 48MP'],
                url: '#'
            },
            {
                id: 7,
                name: 'Samsung Galaxy S23',
                price: '₫18,990,000',
                image: 'https://via.placeholder.com/300x300/1f4788/ffffff?text=Galaxy+S23',
                brand: 'samsung',
                ram: '8gb',
                storage: '256gb',
                specs: ['💾 8GB RAM', '💿 256GB', '📷 50MP'],
                url: '#'
            },
            {
                id: 8,
                name: 'Xiaomi 13T Pro',
                price: '₫16,990,000',
                image: 'https://via.placeholder.com/300x300/ff6900/ffffff?text=Xiaomi+13T',
                brand: 'xiaomi',
                ram: '12gb',
                storage: '256gb',
                specs: ['💾 12GB RAM', '💿 256GB', '📷 50MP'],
                url: '#'
            }
        ];
        
        // Apply filters
        let filteredProducts = mockProducts.filter(product => {
            if (filters.brand && product.brand !== filters.brand) return false;
            if (filters.ram && product.ram !== filters.ram) return false;
            if (filters.storage && product.storage !== filters.storage) return false;
            if (filters.search && !product.name.toLowerCase().includes(filters.search.toLowerCase())) return false;
            return true;
        });
        
        // Apply sorting
        if (filters.sort) {
            switch (filters.sort) {
                case 'price-asc':
                    filteredProducts.sort((a, b) => parsePrice(a.price) - parsePrice(b.price));
                    break;
                case 'price-desc':
                    filteredProducts.sort((a, b) => parsePrice(b.price) - parsePrice(a.price));
                    break;
                case 'name-asc':
                    filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case 'name-desc':
                    filteredProducts.sort((a, b) => b.name.localeCompare(a.name));
                    break;
            }
        }
        
        displayProducts(filteredProducts, clearExisting);
        updateProductsCount(filteredProducts.length);
        
        if (filteredProducts.length === 0) {
            $('#no-products').show();
        } else {
            $('#load-more').show();
        }
        
        isLoading = false;
        $('#products-loading').hide();
    }
    
    // Parse price for sorting
    function parsePrice(priceStr) {
        return parseInt(priceStr.replace(/[^\d]/g, ''));
    }
    
    // Display products
    function displayProducts(products, clearExisting) {
        if (clearExisting) {
            $('#products-grid').empty();
        }
        
        if (products.length === 0 && clearExisting) {
            $('#no-products').show();
            return;
        }
        
        $('#no-products').hide();
        
        products.forEach(function(product) {
            const productHtml = `
                <div class="product-card" data-product-id="${product.id}">
                    <div class="product-image">
                        <img src="${product.image}" alt="${product.name}" loading="lazy">
                        <div class="product-overlay">
                            <button class="quick-view-btn" data-product-id="${product.id}">👁️ Xem nhanh</button>
                            <button class="compare-btn" data-product-id="${product.id}">⚖️ So sánh</button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">${product.name}</h3>
                        <div class="product-price">${product.price}</div>
                        <div class="product-specs">${product.specs.map(spec => `<span>${spec}</span>`).join('')}</div>
                        <div class="product-rating">
                            <div class="stars">⭐⭐⭐⭐⭐</div>
                            <span class="review-count">(${Math.floor(Math.random() * 100) + 10} đánh giá)</span>
                        </div>
                    </div>
                    <div class="product-actions">
                        <button class="add-to-cart-btn" data-product-id="${product.id}">
                            🛒 Thêm vào giỏ
                        </button>
                        <button class="buy-now-btn" data-product-id="${product.id}">
                            ⚡ Mua ngay
                        </button>
                    </div>
                </div>
            `;
            
            $('#products-grid').append(productHtml);
        });
    }
    
    // Update products count
    function updateProductsCount(total) {
        $('#products-count').text(`${total} sản phẩm`);
    }
});
</script>

<style>
/* === PRODUCTS PAGE STYLES === */
.products-page-header {
    text-align: center;
    padding: 40px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    margin: 20px 0 30px 0;
}

.products-page-header h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.products-page-header p {
    font-size: 1.2rem;
    opacity: 0.9;
}

/* Filter Section */
.products-filter-section {
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
}

/* === SEARCH SECTION === */
.products-search-section {
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
    transition: border-color 0.3s;
}

.search-input-wrapper:focus-within {
    border-color: #38a169;
    box-shadow: 0 0 0 3px rgba(56, 161, 105, 0.1);
}

#product-search {
    flex: 1;
    padding: 15px 20px;
    border: none;
    background: transparent;
    font-size: 16px;
    outline: none;
}

#product-search::placeholder {
    color: #a0aec0;
}

#search-btn {
    background: #38a169;
    color: white;
    border: none;
    padding: 15px 25px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 16px;
}

#search-btn:hover {
    background: #2f855a;
    transform: translateY(-1px);
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
}

.suggestion-item:hover {
    background: #f8fafc;
}

.suggestion-item:last-child {
    border-bottom: none;
}

/* Products Section */
.products-section {
    margin: 30px 0;
}

.products-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding: 0 10px;
}

.products-header h2 {
    color: #2d3748;
    font-size: 1.8rem;
}

.products-count {
    color: #718096;
    font-weight: 600;
    background: #f8fafc;
    padding: 8px 16px;
    border-radius: 20px;
}

/* Loading Spinner */
.loading-spinner {
    text-align: center;
    padding: 60px 0;
}

.spinner {
    font-size: 48px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    margin: 20px 0;
}

.product-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.product-image {
    position: relative;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    opacity: 0;
    transition: opacity 0.3s;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.quick-view-btn, .compare-btn {
    background: rgba(255,255,255,0.9);
    color: #2d3748;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.quick-view-btn:hover, .compare-btn:hover {
    background: white;
    transform: translateY(-2px);
}

.product-info {
    padding: 20px;
}

.product-name {
    color: #2d3748;
    font-size: 16px;
    font-weight: 700;
    margin: 0 0 10px 0;
    line-height: 1.4;
}

.product-price {
    color: #e53e3e;
    font-size: 20px;
    font-weight: 800;
    margin: 10px 0;
}

.product-specs {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin: 12px 0;
}

.product-specs span {
    background: #f7fafc;
    color: #4a5568;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 12px 0;
}

.stars {
    color: #fbbf24;
    font-size: 14px;
}

.review-count {
    color: #718096;
    font-size: 12px;
}

.product-actions {
    padding: 15px 20px;
    display: flex;
    gap: 10px;
}

.add-to-cart-btn, .buy-now-btn {
    flex: 1;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
}

.add-to-cart-btn {
    background: #38a169;
    color: white;
}

.add-to-cart-btn:hover {
    background: #2f855a;
    transform: translateY(-2px);
}

.buy-now-btn {
    background: #e53e3e;
    color: white;
}

.buy-now-btn:hover {
    background: #c53030;
    transform: translateY(-2px);
}

/* No Products */
.no-products {
    text-align: center;
    padding: 80px 20px;
    color: #718096;
}

.no-products-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.no-products h3 {
    font-size: 24px;
    margin-bottom: 10px;
    color: #4a5568;
}

.show-all-btn {
    background: #4299e1;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
   margin-top: 20px;
   transition: all 0.3s;
}

.show-all-btn:hover {
   background: #3182ce;
   transform: translateY(-2px);
}

/* Load More */
.load-more-section {
   text-align: center;
   margin: 40px 0;
}

.load-more-btn {
   background: #4299e1;
   color: white;
   border: none;
   padding: 15px 30px;
   border-radius: 10px;
   font-size: 16px;
   font-weight: 600;
   cursor: pointer;
   transition: all 0.3s;
}

.load-more-btn:hover {
   background: #3182ce;
   transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
   .products-page-header h1 {
       font-size: 2rem;
   }
   
   .filters-row {
       grid-template-columns: 1fr;
   }
   
   .filter-actions {
       grid-column: 1;
   }
   
   .search-input-wrapper {
       flex-direction: column;
       gap: 10px;
   }
   
   #search-btn {
       width: 100%;
   }
   
   .products-header {
       flex-direction: column;
       align-items: flex-start;
       gap: 10px;
   }
   
   .products-grid {
       grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
       gap: 20px;
   }
}

@media (max-width: 480px) {
   .products-grid {
       grid-template-columns: 1fr;
   }
   
   .product-actions {
       flex-direction: column;
   }
   
   .products-filter-section,
   .products-search-section {
       padding: 15px;
   }
}
</style>

<?php get_footer(); ?>

<?php
// Lấy danh sách sản phẩm
$products = wc_get_products(['status' => 'publish']);

foreach ($products as $product) :
    $product_url = get_permalink($product->get_id());
?>
    <div class="product-card">
        <a href="<?php echo $product_url; ?>">
            <?php echo $product->get_image('medium'); ?>
            <h3><?php echo $product->get_name(); ?></h3>
            <div class="price"><?php echo $product->get_price_html(); ?></div>
        </a>
        
        <!-- Nút xem chi tiết -->
        <a href="<?php echo $product_url; ?>" class="btn-detail">
            👁️ Xem chi tiết
        </a>
    </div>
<?php endforeach; ?>