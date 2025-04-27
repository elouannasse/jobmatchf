/**
 * Modern Job Offers JavaScript
 * Enhances the job offers listing page with interactive features
 */

document.addEventListener('DOMContentLoaded', function() {
    // Status badge click animation
    const statusBadges = document.querySelectorAll('.badge-active, .badge-inactive');
    statusBadges.forEach(badge => {
        badge.addEventListener('click', function() {
            this.classList.add('clicked');
            
            // Remove animation class after animation completes
            setTimeout(() => {
                this.classList.remove('clicked');
            }, 300);
        });
    });

    // Add visual feedback to action buttons
    const actionButtons = document.querySelectorAll('.action-btn');
    actionButtons.forEach(button => {
        // Add ripple effect on click
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            
            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${e.clientX - rect.left - size / 2}px`;
            ripple.style.top = `${e.clientY - rect.top - size / 2}px`;
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Enhanced search with debounce
    const searchInput = document.getElementById('searchInput');
    let debounceTimer;
    
    if (searchInput) {
        // Add clear button to search
        const searchContainer = searchInput.closest('.search-container');
        if (searchContainer) {
            const clearButton = document.createElement('button');
            clearButton.className = 'search-clear';
            clearButton.innerHTML = '<i class="fas fa-times"></i>';
            clearButton.style.display = 'none';
            searchContainer.appendChild(clearButton);
            
            // Toggle clear button visibility
            searchInput.addEventListener('input', function() {
                clearButton.style.display = this.value.length > 0 ? 'block' : 'none';
            });
            
            // Clear search on button click
            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                searchInput.focus();
                this.style.display = 'none';
            });
        }
        
        // Debounced search
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            
            debounceTimer = setTimeout(() => {
                performSearch(this.value);
            }, 300);
        });
    }
    
    function performSearch(query) {
        const trimmedQuery = query.trim().toLowerCase();
        const table = document.querySelector('.offers-table');
        
        if (table) {
            const rows = table.querySelectorAll('tbody tr');
            let hasResults = false;
            
            rows.forEach(function(row) {
                let found = false;
                row.querySelectorAll('td').forEach(function(cell) {
                    if (cell.textContent.toLowerCase().indexOf(trimmedQuery) > -1) {
                        found = true;
                    }
                });
                
                if (found || trimmedQuery === '') {
                    row.style.display = '';
                    // Add fade-in animation for better UX
                    row.style.animation = 'fadeIn 0.3s ease-in-out';
                    hasResults = true;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Show or hide "no results" message
            const emptySearchResults = document.querySelector('.empty-search-results');
            if (emptySearchResults) {
                emptySearchResults.style.display = hasResults || trimmedQuery === '' ? 'none' : 'block';
                
                if (!hasResults && trimmedQuery !== '') {
                    emptySearchResults.style.animation = 'fadeIn 0.3s ease-in-out';
                }
            }
        }
    }

    // Toast notifications
    const toast = document.querySelector('.toast');
    if (toast) {
        // Show the toast
        toast.style.display = 'block';
        
        // Add close button to toast
        const closeBtn = document.createElement('button');
        closeBtn.className = 'toast-close';
        closeBtn.innerHTML = '<i class="fas fa-times"></i>';
        toast.appendChild(closeBtn);
        
        // Close on button click
        closeBtn.addEventListener('click', function() {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 300);
        });
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 300);
        }, 5000);
    }

    // Delete confirmation enhancement
    const deleteButtons = document.querySelectorAll('.action-btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-bs-target');
            const modal = document.querySelector(modalId);
            
            if (modal) {
                // Highlight delete button
                const deleteConfirmBtn = modal.querySelector('.btn-delete-confirm');
                if (deleteConfirmBtn) {
                    // Add pulse animation
                    deleteConfirmBtn.classList.add('pulse-animation');
                    setTimeout(() => {
                        deleteConfirmBtn.classList.remove('pulse-animation');
                    }, 2000);
                }
            }
        });
    });

    // Add visual feedback to all interactive elements
    document.querySelectorAll('button, .btn, a[href]').forEach(element => {
        if (!element.classList.contains('action-btn') && !element.classList.contains('toast-close')) {
            element.addEventListener('mousedown', function() {
                this.style.transform = 'scale(0.98)';
            });
            
            element.addEventListener('mouseup', function() {
                this.style.transform = '';
            });
            
            element.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        }
    });

    // Add table row highlight on hover
    document.querySelectorAll('.offers-table tbody tr').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(78, 115, 223, 0.05)';
            this.style.cursor = 'pointer';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
            this.style.cursor = '';
        });
    });
});

// Add custom styles for JavaScript-added elements
document.head.insertAdjacentHTML('beforeend', `
<style>
    .clicked {
        animation: pulse 0.3s ease-out;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.5);
        transform: scale(0);
        animation: ripple 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple {
        to { transform: scale(2); opacity: 0; }
    }
    
    .search-clear {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #858796;
        cursor: pointer;
        font-size: 0.9rem;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    .search-clear:hover {
        background-color: rgba(0, 0, 0, 0.05);
        color: #5a5c69;
    }
    
    .toast-close {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        color: #858796;
        cursor: pointer;
        font-size: 0.9rem;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    .toast-close:hover {
        background-color: rgba(0, 0, 0, 0.05);
        color: #5a5c69;
    }
    
    .pulse-animation {
        animation: pulse-danger 2s infinite;
    }
    
    @keyframes pulse-danger {
        0% { box-shadow: 0 0 0 0 rgba(231, 74, 59, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(231, 74, 59, 0); }
        100% { box-shadow: 0 0 0 0 rgba(231, 74, 59, 0); }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
`);
