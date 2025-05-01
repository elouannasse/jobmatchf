// Dashboard interaction script
document.addEventListener('DOMContentLoaded', function() {
    // Menu toggle for mobile
    const menuToggle = document.querySelector('.menu-toggle');
    const dashboardNav = document.querySelector('.dashboard-nav');
    
    if (menuToggle && dashboardNav) {
        menuToggle.addEventListener('click', function() {
            dashboardNav.classList.toggle('show');
        });
    }
    
    // Add table responsiveness
    const tables = document.querySelectorAll('table');
    tables.forEach(table => {
        table.classList.add('table-responsive');
        
        // Add data attributes for responsive display
        const headerTexts = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
        
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            cells.forEach((cell, index) => {
                if (headerTexts[index]) {
                    cell.setAttribute('data-label', headerTexts[index]);
                }
            });
        });
    });
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    if (typeof bootstrap !== 'undefined') {
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Activate current page link
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.dashboard-nav-link, .home-list-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href) && href !== '#') {
            link.classList.add('active');
        }
    });
    
    // Add animation to stat cards
    const statCards = document.querySelectorAll('.dashboard-stat-card, .stats-card');
    statCards.forEach((card, index) => {
        card.style.animationDelay = (index * 0.1) + 's';
        card.classList.add('animate__animated', 'animate__fadeInUp');
    });
});
