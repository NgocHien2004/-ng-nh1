<?php
/*
Template Name: Liên Hệ
*/

get_header();
?>

<div class="container">
    <div class="contact-page-header">
        <h1>📞 Liên Hệ Với Chúng Tôi</h1>
        <p>Chúng tôi luôn sẵn sàng hỗ trợ bạn. Hãy liên hệ với chúng tôi qua các hình thức dưới đây</p>
    </div>
    
    <!-- Contact Info Cards -->
    <div class="contact-info-section">
        <h2>🏪 Thông Tin Cửa Hàng</h2>
        <div class="contact-cards-grid">
            <div class="contact-card">
                <div class="contact-icon">📍</div>
                <h3>Địa Chỉ</h3>
                <p>Purple House, Ninh Kiều<br>Cần Thơ, Việt Nam</p>
                <a href="https://maps.google.com/?q=Purple+House+Ninh+Kieu+Can+Tho" target="_blank" class="map-link">
                    🗺️ Xem trên bản đồ
                </a>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">📱</div>
                <h3>Số Điện Thoại</h3>
                <p><a href="tel:+84123456789">📞 0123.456.789</a></p>
                <p><a href="tel:+84987654321">📞 098.765.4321</a></p>
                <small>Hotline hỗ trợ 24/7</small>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">✉️</div>
                <h3>Email</h3>
                <p><a href="mailto:info@phonestore.com">info@phonestore.com</a></p>
                <p><a href="mailto:support@phonestore.com">support@phonestore.com</a></p>
                <small>Phản hồi trong 24h</small>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">🕐</div>
                <h3>Giờ Mở Cửa</h3>
                <p><strong>Thứ 2 - Thứ 6:</strong><br>8:00 - 21:00</p>
                <p><strong>Thứ 7 - Chủ Nhật:</strong><br>8:00 - 22:00</p>
            </div>
        </div>
    </div>
    
    <!-- Contact Form Section -->
    <div class="contact-form-section">
        <h2>💬 Gửi Tin Nhắn Cho Chúng Tôi</h2>
        
        <!-- Success/Error Messages -->
        <div id="contact-messages" style="display: none;"></div>
        
        <form id="contact-form" class="contact-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="contact-name">👤 Họ và Tên *</label>
                    <input type="text" id="contact-name" name="name" required placeholder="Nhập họ và tên của bạn">
                </div>
                
                <div class="form-group">
                    <label for="contact-phone">📱 Số Điện Thoại *</label>
                    <input type="tel" id="contact-phone" name="phone" required placeholder="Nhập số điện thoại">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="contact-email">✉️ Email *</label>
                    <input type="email" id="contact-email" name="email" required placeholder="Nhập địa chỉ email">
                </div>
                
                <div class="form-group">
                    <label for="contact-subject">📋 Chủ Đề</label>
                    <select id="contact-subject" name="subject">
                        <option value="">-- Chọn chủ đề --</option>
                        <option value="product-inquiry">Hỏi về sản phẩm</option>
                        <option value="warranty">Bảo hành</option>
                        <option value="return-exchange">Đổi trả</option>
                        <option value="technical-support">Hỗ trợ kỹ thuật</option>
                        <option value="complaint">Khiếu nại</option>
                        <option value="partnership">Hợp tác kinh doanh</option>
                        <option value="other">Khác</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="contact-message">💭 Tin Nhắn *</label>
                <textarea id="contact-message" name="message" rows="6" required placeholder="Nhập tin nhắn của bạn..."></textarea>
            </div>
            
            <div class="form-group checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" id="contact-privacy" name="privacy" required>
                    <span class="checkmark"></span>
                    Tôi đồng ý với chính sách bảo mật và cho phép cửa hàng liên hệ lại với tôi
                </label>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="submit-btn">
                    <span class="btn-text">🚀 Gửi Tin Nhắn</span>
                    <span class="btn-loader" style="display: none;">⏳ Đang gửi...</span>
                </button>
                <button type="reset" class="reset-btn">🔄 Làm Mới</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Contact Page Styles */
.contact-page-header {
    text-align: center;
    padding: 40px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    margin: 20px 0 40px 0;
}

.contact-page-header h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.contact-page-header p {
    font-size: 1.2rem;
    opacity: 0.9;
}

.contact-info-section {
    margin: 40px 0;
}

.contact-info-section h2 {
   text-align: center;
   font-size: 2rem;
   margin-bottom: 30px;
   color: #333;
}

.contact-cards-grid {
   display: grid;
   grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
   gap: 25px;
   margin-bottom: 40px;
}

.contact-card {
   background: white;
   padding: 30px;
   border-radius: 15px;
   text-align: center;
   box-shadow: 0 5px 15px rgba(0,0,0,0.1);
   transition: transform 0.3s, box-shadow 0.3s;
}

.contact-card:hover {
   transform: translateY(-5px);
   box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.contact-icon {
   font-size: 48px;
   margin-bottom: 15px;
}

.contact-card h3 {
   color: #333;
   margin-bottom: 15px;
   font-size: 20px;
}

.contact-card p {
   color: #666;
   line-height: 1.6;
   margin-bottom: 10px;
}

.contact-card a {
   color: #38a169;
   text-decoration: none;
}

.contact-card a:hover {
   text-decoration: underline;
}

.map-link {
   display: inline-block;
   margin-top: 10px;
   padding: 8px 16px;
   background: #38a169;
   color: white !important;
   border-radius: 6px;
   font-size: 14px;
   font-weight: 600;
   transition: background 0.3s;
}

.map-link:hover {
   background: #2f855a;
   text-decoration: none !important;
}

.contact-form-section {
   background: #f8f9fa;
   padding: 40px;
   border-radius: 15px;
   margin: 40px 0;
}

.contact-form-section h2 {
   text-align: center;
   color: #333;
   margin-bottom: 30px;
   font-size: 2rem;
}

.contact-form {
   max-width: 800px;
   margin: 0 auto;
}

.form-row {
   display: grid;
   grid-template-columns: 1fr 1fr;
   gap: 20px;
   margin-bottom: 20px;
}

.form-group {
   margin-bottom: 20px;
}

.form-group label {
   display: block;
   margin-bottom: 8px;
   color: #333;
   font-weight: 600;
}

.form-group input,
.form-group select,
.form-group textarea {
   width: 100%;
   padding: 15px;
   border: 2px solid #e2e8f0;
   border-radius: 10px;
   font-size: 16px;
   transition: border-color 0.3s;
   font-family: inherit;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
   outline: none;
   border-color: #38a169;
}

.form-group textarea {
   resize: vertical;
   line-height: 1.6;
}

.checkbox-group {
   display: flex;
   align-items: flex-start;
   gap: 10px;
}

.checkbox-label {
   display: flex;
   align-items: flex-start;
   gap: 10px;
   cursor: pointer;
   line-height: 1.5;
}

.checkbox-label input[type="checkbox"] {
   width: auto;
   margin: 0;
}

.form-actions {
   display: flex;
   gap: 15px;
   justify-content: center;
   margin-top: 30px;
}

.submit-btn {
   background: #38a169;
   color: white;
   padding: 15px 30px;
   border: none;
   border-radius: 10px;
   font-size: 16px;
   font-weight: 600;
   cursor: pointer;
   transition: background 0.3s;
}

.submit-btn:hover {
   background: #2f855a;
}

.submit-btn:disabled {
   background: #a0aec0;
   cursor: not-allowed;
}

.reset-btn {
   background: #6c757d;
   color: white;
   padding: 15px 30px;
   border: none;
   border-radius: 10px;
   font-size: 16px;
   font-weight: 600;
   cursor: pointer;
   transition: background 0.3s;
}

.reset-btn:hover {
   background: #5a6268;
}

/* Messages */
#contact-messages {
   margin-bottom: 30px;
   padding: 20px;
   border-radius: 10px;
}

.success-message {
   background: #d4edda;
   color: #155724;
   border: 1px solid #c3e6cb;
   padding: 20px;
   border-radius: 10px;
}

.error-message {
   background: #f8d7da;
   color: #721c24;
   border: 1px solid #f5c6cb;
   padding: 20px;
   border-radius: 10px;
}

.success-message h4,
.error-message h4 {
   margin-bottom: 10px;
   font-size: 18px;
}

/* Responsive */
@media (max-width: 768px) {
   .contact-page-header h1 {
       font-size: 2rem;
   }
   
   .contact-cards-grid {
       grid-template-columns: 1fr;
       gap: 20px;
   }
   
   .form-row {
       grid-template-columns: 1fr;
       gap: 0;
   }
   
   .contact-form-section {
       padding: 20px;
   }
   
   .form-actions {
       flex-direction: column;
       align-items: center;
   }
   
   .submit-btn,
   .reset-btn {
       width: 100%;
       max-width: 300px;
   }
}
</style>

<script>
jQuery(document).ready(function($) {
   // Contact Form Submission
   $('#contact-form').on('submit', function(e) {
       e.preventDefault();
       
       const submitBtn = $('.submit-btn');
       const btnText = $('.btn-text');
       const btnLoader = $('.btn-loader');
       const messagesDiv = $('#contact-messages');
       
       // Show loading
       submitBtn.prop('disabled', true);
       btnText.hide();
       btnLoader.show();
       
       // Get form data
       const formData = {
           action: 'phonestore_contact_form',
           name: $('#contact-name').val(),
           phone: $('#contact-phone').val(),
           email: $('#contact-email').val(),
           subject: $('#contact-subject').val(),
           message: $('#contact-message').val(),
           privacy: $('#contact-privacy').is(':checked'),
           nonce: phonestore_ajax.nonce
       };
       
       // Submit via AJAX
       $.ajax({
           url: phonestore_ajax.ajax_url,
           type: 'POST',
           data: formData,
           success: function(response) {
               if (response.success) {
                   messagesDiv.html(`
                       <div class="success-message">
                           <h4>✅ Gửi tin nhắn thành công!</h4>
                           <p>${response.data.message}</p>
                       </div>
                   `).show();
                   
                   // Reset form
                   $('#contact-form')[0].reset();
                   
                   // Scroll to message
                   $('html, body').animate({
                       scrollTop: messagesDiv.offset().top - 100
                   }, 500);
                   
               } else {
                   messagesDiv.html(`
                       <div class="error-message">
                           <h4>❌ Có lỗi xảy ra!</h4>
                           <p>${response.data.message}</p>
                       </div>
                   `).show();
               }
           },
           error: function() {
               messagesDiv.html(`
                   <div class="error-message">
                       <h4>❌ Lỗi kết nối!</h4>
                       <p>Vui lòng thử lại sau hoặc liên hệ trực tiếp qua số điện thoại.</p>
                   </div>
               `).show();
           },
           complete: function() {
               // Hide loading
               submitBtn.prop('disabled', false);
               btnText.show();
               btnLoader.hide();
           }
       });
   });
   
   // Phone number formatting
   $('#contact-phone').on('input', function() {
       let value = $(this).val().replace(/\D/g, '');
       if (value.length > 0) {
           if (value.length <= 3) {
               value = value;
           } else if (value.length <= 6) {
               value = value.slice(0, 3) + '.' + value.slice(3);
           } else if (value.length <= 9) {
               value = value.slice(0, 3) + '.' + value.slice(3, 6) + '.' + value.slice(6);
           } else {
               value = value.slice(0, 3) + '.' + value.slice(3, 6) + '.' + value.slice(6, 9);
           }
       }
       $(this).val(value);
   });
   
   // Auto-hide messages after 5 seconds
   setTimeout(function() {
       $('#contact-messages').fadeOut();
   }, 5000);
});
</script>

<?php get_footer(); ?>