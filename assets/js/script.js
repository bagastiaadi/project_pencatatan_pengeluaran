document.addEventListener('DOMContentLoaded', function() {
    console.log('Expense Tracker - Script loaded!');
    
    // Initialize functions
    initNumberFormat();
    initDateInputs();
    initFormValidation();
});


//Auto format input angka
function initNumberFormat() {
    const amountInputs = document.querySelectorAll('input[name="amount"]');
    
    amountInputs.forEach(input => {
        // Format saat user mengetik
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/[^\d]/g, '');
            
            if (value) {
                // Format dengan titik pemisah ribuan
                this.value = formatNumber(value);
            }
        });
        
        // Hilangkan format saat submit (kembalikan ke angka murni)
        const form = input.closest('form');
        if (form) {
            form.addEventListener('submit', function() {
                input.value = input.value.replace(/\./g, '');
            });
        }
    });
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(angka);
}


// Set max date dan default
function initDateInputs() {
    const dateInputs = document.querySelectorAll('input[type="date"]');
    const today = new Date().toISOString().split('T')[0];
    
    dateInputs.forEach(input => {
        // Set max date = hari ini (tidak bisa pilih tanggal masa depan)
        if (input.name === 'expense_date') {
            input.max = today;
        }
        
        // Set default value jika kosong
        if (!input.value && input.name === 'expense_date') {
            input.value = today;
        }
    });
}

//Validasi tambahan
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Validasi amount tidak boleh 0
            const amountInput = form.querySelector('input[name="amount"]');
            if (amountInput) {
                const amount = parseInt(amountInput.value.replace(/\./g, ''));
                if (amount <= 0) {
                    e.preventDefault();
                    alert('Jumlah pengeluaran harus lebih dari 0!');
                    amountInput.focus();
                    return false;
                }
            }
            
            // Validasi password match
            const newPassword = form.querySelector('input[name="new_password"]');
            const confirmPassword = form.querySelector('input[name="confirm_password"]');
            
            if (newPassword && confirmPassword) {
                if (newPassword.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert('Password baru dan konfirmasi tidak cocok!');
                    confirmPassword.focus();
                    return false;
                }
            }
            
            // Validasi password register
            const password = form.querySelector('input[name="password"]');
            const confirmPasswordReg = form.querySelector('input[name="confirm_password"]');
            
            if (password && confirmPasswordReg && form.action.includes('register')) {
                if (password.value !== confirmPasswordReg.value) {
                    e.preventDefault();
                    alert('Password dan konfirmasi tidak cocok!');
                    confirmPasswordReg.focus();
                    return false;
                }
            }
        });
    });
}


//Custom confirm dialog
function confirmDelete(message) {
    message = message || 'Yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
    return confirm(message);
}


//Show/hide password
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = event.target;
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = '🙈';
    } else {
        input.type = 'password';
        icon.textContent = '👁️';
    }
}


//Alert otomatis hilang
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        // Auto hide setelah 5 detik
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
        
        // Close button (jika ada)
        const closeBtn = document.createElement('span');
        closeBtn.innerHTML = '&times;';
        closeBtn.style.cssText = 'float: right; cursor: pointer; font-size: 1.5rem; line-height: 1;';
        closeBtn.onclick = function() {
            alert.style.transition = 'opacity 0.3s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        };
        
        alert.insertBefore(closeBtn, alert.firstChild);
    });
});


//Scroll halus ke element
function smoothScrollTo(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
    }
}



//Tampilkan loading saat submit
function showLoading(button) {
    const originalText = button.textContent;
    button.disabled = true;
    button.textContent = 'Loading...';
    
    // Restore setelah 3 detik (fallback)
    setTimeout(() => {
        button.disabled = false;
        button.textContent = originalText;
    }, 3000);
}

// Add loading to all submit buttons
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                showLoading(submitBtn);
            }
        });
    });
});



//Copy text ke clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Berhasil disalin ke clipboard!');
    }).catch(err => {
        console.error('Gagal menyalin:', err);
    });
}


//Animasi angka naik
function animateValue(element, start, end, duration) {
    const range = end - start;
    const increment = range / (duration / 16); // 60fps
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= end) {
            current = end;
            clearInterval(timer);
        }
        element.textContent = Math.floor(current).toLocaleString('id-ID');
    }, 16);
}

// Animate statistics on dashboard
document.addEventListener('DOMContentLoaded', function() {
    const statValues = document.querySelectorAll('.stat-value');
    
    statValues.forEach(stat => {
        const text = stat.textContent.replace(/[^\d]/g, '');
        const value = parseInt(text);
        
        if (value && !isNaN(value)) {
            stat.textContent = '0';
            setTimeout(() => {
                animateValue(stat, 0, value, 1000);
            }, 300);
        }
    });
});