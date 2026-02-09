/* Performance optimization script for SiteLedger */
(function() {
    'use strict';
    
    // Add loading indicator for CSS
    document.addEventListener('DOMContentLoaded', function() {
        // Remove any loading states once CSS is fully loaded
        document.body.style.visibility = 'visible';
    });
    
    // Lazy load images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    observer.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Preload critical pages on hover
    const linkObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const link = entry.target;
                if (link.hostname === window.location.hostname) {
                    // Preload on hover
                    link.addEventListener('mouseenter', function() {
                        const prefetchLink = document.createElement('link');
                        prefetchLink.rel = 'prefetch';
                        prefetchLink.href = this.href;
                        document.head.appendChild(prefetchLink);
                    }, { once: true });
                }
            }
        });
    });
    
    document.querySelectorAll('a[href]').forEach(link => {
        linkObserver.observe(link);
    });
    
})();