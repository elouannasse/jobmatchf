// Job Offers Page Interactive Features
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        // Add clear button to search input
        const searchContainer = searchInput.parentElement;
        const clearButton = document.createElement('button');
        clearButton.innerHTML = '<i class="fas fa-times"></i>';
        clearButton.className = 'search-clear-btn';
        clearButton.style.display = 'none';
        searchContainer.appendChild(clearButton);
        
        // Show/hide clear button based on input content
        searchInput.addEventListener('input', function() {
            clearButton.style.display = this.value.length > 0 ? 'block' : 'none';
            
            // Perform search
            performSearch(this.value);
        });
        
        // Clear search when button is clicked
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            this.style.display = 'none';
            performSearch('');
            searchInput.focus();
        });
    }
    
    // Search function
    function performSearch(searchValue) {
        searchValue = searchValue.toLowerCase();
        const table = document.querySelector('table');
        
        if (table) {
            const rows = table.querySelectorAll('tbody tr');
            let hasResults = false;
            
            rows.forEach(function(row) {
                let found = false;
                row.querySelectorAll('td').forEach(function(cell) {
                    if (cell.textContent.toLowerCase().indexOf(searchValue) > -1) {
                        found = true;
                    }
                });
                
                if (found) {
                    row.style.display = '';
                    hasResults = true;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Show or hide the "no results" message
            const emptyStateContainer = document.querySelector('.empty-search-results');
            if (emptyStateContainer) {
                emptyStateContainer.style.display = hasResults ? 'none' : 'block';
            }
        }
    }
    
    // Add smooth transitions to status badges
    const statusBadges = document.querySelectorAll('.status-badge');
    statusBadges.forEach(badge => {
        badge.addEventListener('click', function() {
            this.classList.add('status-toggle-animation');
            
            // Remove animation class after transition finishes
            setTimeout(() => {
                this.classList.remove('status-toggle-animation');
            }, 500);
        });
    });
    
    // Add interactive hover effects to action buttons
    const actionButtons = document.querySelectorAll('.action-btn');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.classList.add('action-btn-hover');
        });
        
        button.addEventListener('mouseleave', function() {
            this.classList.remove('action-btn-hover');
        });
    });
    
    // Highlight table rows on hover
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.classList.add('row-highlight');
        });
        
        row.addEventListener('mouseleave', function() {
            this.classList.remove('row-highlight');
        });
    });
    
    // Add success message timeout
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(() => {
            successAlert.classList.add('fade-out');
            setTimeout(() => {
                successAlert.style.display = 'none';
            }, 500);
        }, 5000);
    }
    
    // Make delete confirmation more interactive
    const deleteButtons = document.querySelectorAll('.action-btn.delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-bs-target');
            const modalElement = document.querySelector(modalId);
            
            if (modalElement) {
                const confirmButton = modalElement.querySelector('.btn-delete');
                
                // Add shake animation to confirm button
                confirmButton.addEventListener('mouseenter', function() {
                    this.classList.add('confirm-delete-hover');
                });
                
                confirmButton.addEventListener('mouseleave', function() {
                    this.classList.remove('confirm-delete-hover');
                });
            }
        });
    });
});

// Add CSS animation classes
document.head.insertAdjacentHTML('beforeend', `
<style>
    .status-toggle-animation {
        animation: pulse 0.5s ease-in-out;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    
    .action-btn-hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    }
    
    .row-highlight {
        background-color: rgba(78, 115, 223, 0.05) !important;
    }
    
    .fade-out {
        opacity: 0;
        transition: opacity 0.5s ease-out;
    }
    
    .confirm-delete-hover {
        animation: shake 0.5s ease-in-out infinite;
    }
    
    @keyframes shake {
        0% { transform: translateX(0); }
        25% { transform: translateX(-2px); }
        50% { transform: translateX(0); }
        75% { transform: translateX(2px); }
        100% { transform: translateX(0); }
    }
    
    .search-clear-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #858796;
        cursor: pointer;
        font-size: 0.8rem;
        padding: 0;
        display: none;
    }
    
    .search-clear-btn:hover {
        color: #4e73df;
    }
</style>
`);
