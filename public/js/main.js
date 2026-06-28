// ============================================
// GLOBAL FUNCTIONS
// ============================================

// Format Rupiah
function formatRupiah(angka) {
    return 'Rp ' + Number(angka).toLocaleString('id-ID');
}

// Format Date
function formatDate(date) {
    const d = new Date(date);
    return d.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Get current date for filter
function getToday() {
    return new Date().toISOString().split('T')[0];
}

// ============================================
// SIDEBAR
// ============================================
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}

// Close sidebar on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    }
});

// ============================================
// ALERT AUTO DISMISS
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach((alert, index) => {
        setTimeout(() => {
            alert.classList.add('animate__fadeOutUp');
            setTimeout(() => alert.remove(), 500);
        }, 4000 + (index * 1000));
    });
});

// ============================================
// DELETE CONFIRMATION
// ============================================
function confirmDelete(message) {
    return confirm(message || 'Apakah Anda yakin?');
}

// ============================================
// FORM VALIDATION
// ============================================
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const inputs = form.querySelectorAll('[required]');
    let valid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            valid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return valid;
}

// ============================================
// EXPORT FUNCTIONS
// ============================================
function exportData(type) {
    // Simulate export
    showNotification('Fitur export akan segera tersedia', 'info');
}

// ============================================
// NOTIFICATION
// ============================================
function showNotification(message, type = 'info') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} animate__animated animate__fadeInDown`;
    alert.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
        <span>${message}</span>
        <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
    `;
    
    const container = document.querySelector('.content-area');
    if (container) {
        container.insertBefore(alert, container.firstChild);
        
        setTimeout(() => {
            alert.classList.add('animate__fadeOutUp');
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    }
}

// ============================================
// LOADING INDICATOR
// ============================================
function showLoading(btnId) {
    const btn = document.getElementById(btnId);
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    }
}

function hideLoading(btnId, originalText) {
    const btn = document.getElementById(btnId);
    if (btn) {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

// ============================================
// TABLE FUNCTIONS
// ============================================
function sortTable(tableId, colIndex) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const isAsc = table.dataset.sortAsc === 'true';
    
    rows.sort((a, b) => {
        const aText = a.querySelectorAll('td')[colIndex]?.textContent || '';
        const bText = b.querySelectorAll('td')[colIndex]?.textContent || '';
        return isAsc ? aText.localeCompare(bText) : bText.localeCompare(aText);
    });
    
    table.dataset.sortAsc = !isAsc;
    rows.forEach(row => tbody.appendChild(row));
}

// ============================================
// KEYBOARD SHORTCUTS
// ============================================
document.addEventListener('keydown', function(e) {
    // Ctrl + N = New
    if (e.ctrlKey && e.key === 'n') {
        e.preventDefault();
        const addBtn = document.querySelector('.btn-primary:not(.btn-sm)');
        if (addBtn) addBtn.click();
    }
    
    // Ctrl + S = Save
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        const submitBtn = document.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.click();
    }
});

// ============================================
// PRINT INVOICE (Preview)
// ============================================
function printInvoice(invoiceId) {
    alert('Fitur print invoice akan segera tersedia');
}

// ============================================
// INIT
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Set default date filter
    const dateInput = document.getElementById('filterDate');
    if (dateInput) {
        dateInput.value = getToday();
    }
    
    // Add active class to current nav
    const currentPath = window.location.pathname;
    document.querySelectorAll('.sidebar-nav a').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
    
    // Enter key for search
    document.querySelectorAll('.search-input-wrapper input').forEach(input => {
        input.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                this.dispatchEvent(new Event('change'));
            }
        });
    });
});

// ============================================
// CONSOLE WELCOME
// ============================================
console.log('%c Admin POS System v1.0 ', 
    'background: #6366f1; color: #fff; font-size: 20px; font-weight: bold; padding: 10px 20px; border-radius: 5px;'
);
console.log('%c Made with ❤️ using CodeIgniter 4 ', 
    'color: #6366f1; font-size: 14px;'
);