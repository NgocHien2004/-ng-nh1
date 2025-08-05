jQuery(document).ready(function ($) {
  // Auto-complete cho search
  let searchTimeout;
  $('.phone-search-form input[name="s"]').on("input", function () {
    var searchTerm = $(this).val();
    clearTimeout(searchTimeout);

    if (searchTerm.length >= 2) {
      searchTimeout = setTimeout(function () {
        $.ajax({
          url: phonestore_ajax.ajax_url,
          type: "POST",
          data: {
            action: "phonestore_ajax_product_search",
            term: searchTerm,
            nonce: phonestore_ajax.nonce,
          },
          success: function (response) {
            if (response.success) {
              showSearchSuggestions(response.data);
            }
          },
          error: function () {
            console.log("Search error");
          },
        });
      }, 300);
    } else {
      hideSearchSuggestions();
    }
  });

  function showSearchSuggestions(suggestions) {
    // Remove existing suggestions
    $(".search-suggestions").remove();

    if (suggestions.length === 0) {
      return;
    }

    var suggestionsHtml = '<div class="search-suggestions">';
    suggestions.forEach(function (item) {
      suggestionsHtml += '<div class="suggestion-item" data-url="' + item.url + '">';
      suggestionsHtml += '<img src="' + (item.image || "") + '" width="40" height="40">';
      suggestionsHtml += '<div class="suggestion-info">';
      suggestionsHtml += '<span class="suggestion-title">' + item.title + "</span>";
      suggestionsHtml += '<span class="suggestion-price">' + item.price + "</span>";
      suggestionsHtml += "</div>";
      suggestionsHtml += "</div>";
    });
    suggestionsHtml += "</div>";

    $(".phone-search-container").append(suggestionsHtml);
  }

  function hideSearchSuggestions() {
    $(".search-suggestions").remove();
  }

  // Click vào suggestion
  $(document).on("click", ".suggestion-item", function () {
    window.location.href = $(this).data("url");
  });

  // Hide suggestions when clicking outside
  $(document).on("click", function (e) {
    if (!$(e.target).closest(".phone-search-container").length) {
      hideSearchSuggestions();
    }
  });

  // AJAX filter cho sản phẩm
  $(".phone-filters select").on("change", function () {
    var formData = $(".phone-search-form").serialize();

    $.ajax({
      url: phonestore_ajax.ajax_url,
      type: "POST",
      data: {
        action: "phonestore_filter_products",
        filters: formData,
        nonce: phonestore_ajax.nonce,
      },
      beforeSend: function () {
        $(".products").html('<div class="loading" style="text-align: center; padding: 40px;">⏳ Đang tải...</div>');
      },
      success: function (response) {
        if (response.success) {
          $(".products").html(response.data);
        } else {
          $(".products").html('<p style="text-align: center; padding: 40px;">Không tìm thấy sản phẩm phù hợp.</p>');
        }
      },
      error: function () {
        $(".products").html('<p style="text-align: center; padding: 40px;">Lỗi khi tải sản phẩm. Vui lòng thử lại.</p>');
      },
    });
  });

  // Compare functionality
  let compareProducts = JSON.parse(localStorage.getItem("phonestore_compare") || "[]");

  // Add to compare
  $(document).on("click", ".compare-btn", function (e) {
    e.preventDefault();
    var productId = parseInt($(this).data("product-id"));

    if (compareProducts.length >= 4) {
      alert("Chỉ có thể so sánh tối đa 4 sản phẩm");
      return;
    }

    if (compareProducts.indexOf(productId) === -1) {
      compareProducts.push(productId);
      localStorage.setItem("phonestore_compare", JSON.stringify(compareProducts));

      $(this).text("✓ Đã thêm").addClass("added");
      alert("Đã thêm sản phẩm vào so sánh!");

      updateCompareCount();
    } else {
      alert("Sản phẩm đã có trong danh sách so sánh!");
    }
  });

  function updateCompareCount() {
    var count = compareProducts.length;
    if (count > 0) {
      if (!$(".compare-notification").length) {
        $("body").append('<div class="compare-notification">So sánh (' + count + ")</div>");
      } else {
        $(".compare-notification").text("So sánh (" + count + ")");
      }
    } else {
      $(".compare-notification").remove();
    }
  }

  // Initialize compare count
  updateCompareCount();

  // Smooth scrolling for anchor links
  $('a[href^="#"]').on("click", function (e) {
    e.preventDefault();
    var target = $($(this).attr("href"));
    if (target.length) {
      $("html, body").animate(
        {
          scrollTop: target.offset().top - 100,
        },
        800
      );
    }
  });
});

// Add CSS for suggestions
jQuery(document).ready(function ($) {
  if (!$("#phonestore-search-styles").length) {
    $("head").append(`
            <style id="phonestore-search-styles">
            .search-suggestions {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                border: 1px solid #ddd;
                border-radius: 10px;
                max-height: 300px;
                overflow-y: auto;
                z-index: 1000;
                box-shadow: 0 8px 16px rgba(0,0,0,0.1);
                margin-top: 5px;
            }
            
            .suggestion-item {
                display: flex;
                align-items: center;
                padding: 12px 15px;
                border-bottom: 1px solid #eee;
                cursor: pointer;
                transition: background 0.3s;
            }
            
            .suggestion-item:hover {
                background: #f8f9fa;
            }
            
            .suggestion-item:last-child {
                border-bottom: none;
            }
            
            .suggestion-item img {
                margin-right: 12px;
                border-radius: 6px;
                object-fit: cover;
            }
            
            .suggestion-info {
                display: flex;
                flex-direction: column;
                flex: 1;
            }
            
            .suggestion-title {
                font-weight: 600;
                color: #333;
                margin-bottom: 4px;
            }
            
            .suggestion-price {
                font-size: 14px;
                color: #e74c3c;
                font-weight: bold;
            }
            
            .compare-notification {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: #007cba;
                color: white;
                padding: 12px 20px;
                border-radius: 25px;
                font-weight: 600;
                z-index: 1000;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                transition: all 0.3s;
            }
            
            .compare-notification:hover {
                background: #005a87;
                transform: translateY(-2px);
            }
            
            .loading {
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                color: #666;
            }
            
            .compare-btn.added {
                background: #28a745 !important;
                cursor: default;
            }
            
            .compare-btn.added:hover {
                background: #28a745 !important;
            }
            </style>
        `);
  }
});
