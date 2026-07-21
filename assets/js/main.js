// original code by fawn barisic - contains all js scripts for the shamazon site

// mobile hamburger menu
let menuInitialized = false;

function initMobileMenu() {
    console.log('initMobileMenu called');
    
    if (menuInitialized) {
        console.log('Menu already initialized, skipping');
        return;
    }
    
    let hamburger = document.getElementById('hamburgerBtn');
    const navMenu = document.querySelector('.main-nav ul');
    
    if (!hamburger) {
        console.error('Hamburger button not found!');
        return;
    }
    if (!navMenu) {
        console.error('Nav menu UL not found!');
        return;
    }
    
    console.log('Hamburger found, attaching click listener');
    
    // clone and replace to remove ALL existing event listeners
    const newHamburger = hamburger.cloneNode(true);
    hamburger.parentNode.replaceChild(newHamburger, hamburger);
    
    // get the reference again
    hamburger = document.getElementById('hamburgerBtn');
    
    // single clean event listener
    hamburger.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        console.log('Hamburger clicked!');
        
        // toggle the show class
        navMenu.classList.toggle('show');
        
        // log the current state for debugging
        console.log('show class present?', navMenu.classList.contains('show'));
        
        // toggle icon
        if (navMenu.classList.contains('show')) {
            this.innerHTML = '✕';
            console.log('Menu opened, icon changed to ✕');
        } else {
            this.innerHTML = '☰';
            console.log('Menu closed, icon changed to ☰');
        }
    });
    
    menuInitialized = true;
    console.log('Menu initialization complete!');
}

// form validation
function initFormValidation() {
    // contact form validation
    const contactForm = document.querySelector('.contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            let isValid = true;
            const name = document.getElementById('name');
            const email = document.getElementById('email');
            const subject = document.getElementById('subject');
            const message = document.getElementById('message');
            
            // reset error states
            clearErrors(this);
            
            // validate name
            if (name && name.value.trim() === '') {
                showError(name, 'Please enter your name.');
                isValid = false;
            }
            
            // validate email
            if (email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email.value.trim())) {
                    showError(email, 'Please enter a valid email address.');
                    isValid = false;
                }
            }
            
            // validate subject
            if (subject && subject.value.trim() === '') {
                showError(subject, 'Please enter a subject.');
                isValid = false;
            }
            
            // validate message
            if (message && message.value.trim() === '') {
                showError(message, 'Please enter your message.');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // profile form validation
    const profileForm = document.querySelector('.profile-form');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            let isValid = true;
            const fullName = document.getElementById('full_name');
            const email = document.getElementById('email');
            
            clearErrors(this);
            
            if (fullName && fullName.value.trim() === '') {
                showError(fullName, 'Please enter your full name.');
                isValid = false;
            }
            
            if (email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email.value.trim())) {
                    showError(email, 'Please enter a valid email address.');
                    isValid = false;
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // password change validation
    const passwordForm = document.querySelector('form[name="change_password"]');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            let isValid = true;
            const current = document.getElementById('current_password');
            const newPass = document.getElementById('new_password');
            const confirm = document.getElementById('confirm_password');
            
            clearErrors(this);
            
            if (current && current.value.trim() === '') {
                showError(current, 'Please enter your current password.');
                isValid = false;
            }
            
            if (newPass && newPass.value.trim() === '') {
                showError(newPass, 'Please enter a new password.');
                isValid = false;
            } else if (newPass && newPass.value.length < 6) {
                showError(newPass, 'Password must be at least 6 characters.');
                isValid = false;
            }
            
            if (confirm && confirm.value.trim() === '') {
                showError(confirm, 'Please confirm your new password.');
                isValid = false;
            }
            
            if (newPass && confirm && newPass.value !== confirm.value) {
                showError(confirm, 'Passwords do not match.');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
}

// show error message
function showError(input, message) {
    input.classList.add('error');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.style.color = '#dc3545';
    errorDiv.style.fontSize = '0.85rem';
    errorDiv.style.marginTop = '3px';
    errorDiv.textContent = message;
    input.parentElement.appendChild(errorDiv);
}

// clear all errors from a form
function clearErrors(form) {
    // remove error class from inputs
    form.querySelectorAll('.error').forEach(function(el) {
        el.classList.remove('error');
    });
    
    // remove error messages
    form.querySelectorAll('.field-error').forEach(function(el) {
        el.remove();
    });
}

// cart qty buttons
function initCartQuantityButtons() {
    // add +/- buttons to cart quantity inputs
    document.querySelectorAll('.qty-input').forEach(function(input) {
        // create wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'qty-wrapper';
        wrapper.style.display = 'flex';
        wrapper.style.alignItems = 'center';
        wrapper.style.gap = '5px';
        
        // create decrement button
        const minusBtn = document.createElement('button');
        minusBtn.type = 'button';
        minusBtn.textContent = '−';
        minusBtn.className = 'qty-btn qty-minus';
        minusBtn.style.padding = '4px 10px';
        minusBtn.style.border = '1px solid #ced4da';
        minusBtn.style.borderRadius = '4px';
        minusBtn.style.background = '#f8f9fa';
        minusBtn.style.cursor = 'pointer';
        
        // create increment button
        const plusBtn = document.createElement('button');
        plusBtn.type = 'button';
        plusBtn.textContent = '+';
        plusBtn.className = 'qty-btn qty-plus';
        plusBtn.style.padding = '4px 10px';
        plusBtn.style.border = '1px solid #ced4da';
        plusBtn.style.borderRadius = '4px';
        plusBtn.style.background = '#f8f9fa';
        plusBtn.style.cursor = 'pointer';
        
        // wrap input
        input.parentElement.insertBefore(wrapper, input);
        wrapper.appendChild(minusBtn);
        wrapper.appendChild(input);
        wrapper.appendChild(plusBtn);
        
        // update input width
        input.style.width = '50px';
        input.style.textAlign = 'center';
        input.style.margin = '0';
        
        // event handlers
        minusBtn.addEventListener('click', function() {
            let val = parseInt(input.value) || 1;
            if (val > 1) {
                input.value = val - 1;
                // Trigger change event for form submission
                input.dispatchEvent(new Event('change'));
            }
        });
        
        plusBtn.addEventListener('click', function() {
            let val = parseInt(input.value) || 0;
            let max = parseInt(input.getAttribute('max')) || 99;
            if (val < max) {
                input.value = val + 1;
                input.dispatchEvent(new Event('change'));
            }
        });
        
        // prevent negative numbers
        input.addEventListener('change', function() {
            let val = parseInt(this.value) || 0;
            if (val < 1) this.value = 1;
            let max = parseInt(this.getAttribute('max')) || 99;
            if (val > max) this.value = max;
        });
    });
}

// theme preview
function initThemePreview() {
    const themeOptions = document.querySelectorAll('.theme-option input[type="radio"]');
    
    themeOptions.forEach(function(radio) {
        radio.addEventListener('change', function() {
            // remove selected class from all options
            document.querySelectorAll('.theme-option').forEach(function(opt) {
                opt.classList.remove('selected');
            });
            
            // add selected class to the parent of the checked radio
            if (this.checked) {
                this.closest('.theme-option').classList.add('selected');
            }
        });
    });
}

// product search enhancement
function initProductSearch() {
    const searchInput = document.querySelector('.search-input');
    const searchForm = document.querySelector('.search-form');
    
    if (searchInput && searchForm) {
        // show loading state on submit
        searchForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.textContent = 'Searching...';
                submitBtn.disabled = true;
                setTimeout(function() {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Search';
                }, 2000);
            }
        });
    }
}

// order status badges
function initOrderStatusBadges() {
    const statusBadges = document.querySelectorAll('.badge');
    statusBadges.forEach(function(badge) {
        const text = badge.textContent.toLowerCase().trim();
        const colors = {
            'pending': '#ffc107',
            'processing': '#17a2b8',
            'shipped': '#007bff',
            'delivered': '#28a745',
            'cancelled': '#dc3545'
        };
        if (colors[text]) {
            badge.style.backgroundColor = colors[text];
            badge.style.color = '#fff';
            badge.style.padding = '4px 10px';
            badge.style.borderRadius = '4px';
            badge.style.display = 'inline-block';
            badge.style.fontSize = '0.85rem';
        }
    });
}

// keyboard shortcuts for accessibility
document.addEventListener('keydown', function(e) {
    // escape key closes mobile menu
    if (e.key === 'Escape') {
        const navMenu = document.querySelector('.main-nav ul');
        const hamburger = document.getElementById('hamburgerBtn');
        if (navMenu && navMenu.classList.contains('show')) {
            navMenu.classList.remove('show');
            if (hamburger) {
                hamburger.classList.remove('active');
                hamburger.innerHTML = '☰';
            }
        }
    }
    
    // alt + s focuses search
    if (e.altKey && e.key === 's') {
        e.preventDefault();
        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            searchInput.focus();
        }
    }
});

// FAQ Toggle
function toggleFAQ(button) {
    const item = button.closest('.faq-item');
    if (!item) return;
    
    const answer = item.querySelector('.faq-answer');
    const icon = button.querySelector('.faq-icon');
    const isOpen = item.classList.contains('open');
    
    // close all others
    document.querySelectorAll('.faq-item').forEach(function(el) {
        if (el !== item) {
            el.classList.remove('open');
            const a = el.querySelector('.faq-answer');
            if (a) a.style.display = 'none';
            const i = el.querySelector('.faq-icon');
            if (i) i.textContent = '▼';
        }
    });
    
    // toggle current
    if (isOpen) {
        item.classList.remove('open');
        if (answer) answer.style.display = 'none';
        if (icon) icon.textContent = '▼';
    } else {
        item.classList.add('open');
        if (answer) answer.style.display = 'block';
        if (icon) icon.textContent = '▲';
    }
}

// add css animations dynamically
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    /* Loading spinner for buttons */
    .loading .btn-text {
        visibility: hidden;
    }
    .loading::after {
        content: '';
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #fff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 0.6s linear infinite;
        position: absolute;
        margin-left: 8px;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    /* Product card hover effects */
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }
`;
document.head.appendChild(style);

// DOM ready initialization
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM ready - initializing all features');
    initMobileMenu();
    initFormValidation();
    initCartQuantityButtons();
    initThemePreview();
    initProductSearch();
    initOrderStatusBadges();
});

// window load event to double check mobile menu working
window.addEventListener('load', function() {
    console.log('Window fully loaded - double-checking initialization');
    if (!menuInitialized) {
        console.log('Menu not initialized, trying again...');
        initMobileMenu();
    }
});

console.log('Shamazon JavaScript initialized successfully!');