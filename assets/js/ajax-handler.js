<?php
/**
 * AJAX Handler JavaScript
 * Manages form submissions via AJAX
 */

// Contact Form Handler
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'contact_form');
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        try {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Gönderiliyor...';
            
            const response = await fetch('api/ajax-handler.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            const messageDiv = document.getElementById('formMessage');
            messageDiv.style.display = 'block';
            
            if (data.success) {
                messageDiv.className = 'alert alert-success';
                messageDiv.innerHTML = data.message;
                contactForm.reset();
            } else {
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = data.message;
            }
        } catch (error) {
            const messageDiv = document.getElementById('formMessage');
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-danger';
            messageDiv.innerHTML = 'Hata oluştu. Lütfen tekrar deneyin.';
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
}

// Newsletter Subscribe Handler
const newsletterForms = document.querySelectorAll('.newsletter-form');
if (newsletterForms.length > 0) {
    newsletterForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'subscribe');
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            try {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>';
                
                const response = await fetch('api/ajax-handler.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert(data.message);
                    this.reset();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Hata oluştu. Lütfen tekrar deneyin.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    });
}

// Search Handler
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    const searchResults = document.getElementById('searchResults');
    
    searchInput.addEventListener('input', async function(e) {
        const query = e.target.value.trim();
        
        if (query.length < 2) {
            searchResults.innerHTML = '';
            return;
        }
        
        try {
            const response = await fetch(`api/ajax-handler.php?action=search&q=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            if (data.success && data.data.length > 0) {
                let html = '<div class="list-group">';
                data.data.forEach(item => {
                    const url = item.type === 'page' 
                        ? `pages/${item.slug}.php`
                        : `pages/service/${item.slug}.php`;
                    html += `<a href="${url}" class="list-group-item list-group-item-action">${item.title}</a>`;
                });
                html += '</div>';
                searchResults.innerHTML = html;
            } else {
                searchResults.innerHTML = '<p class="text-muted">Sonuç bulunamadı</p>';
            }
        } catch (error) {
            searchResults.innerHTML = '<p class="text-danger">Hata oluştu</p>';
        }
    });
}
