/**
 * Mobile Enhancements JavaScript
 * Improves touch interactions and responsive behavior
 */

document.addEventListener('DOMContentLoaded', function() {

    // ========== Detect Touch Device ==========
    const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    if (isTouchDevice) {
        document.body.classList.add('touch-device');
    }

    // ========== Viewport Height Fix for Mobile ==========
    function setVH() {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }
    setVH();
    window.addEventListener('resize', setVH);
    window.addEventListener('orientationchange', setVH);

    // ========== Smooth Scroll for Internal Links ==========
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // ========== Table Horizontal Scroll Indicator ==========
    function initTableScrollIndicators() {
        const tableContainers = document.querySelectorAll('.table-container');

        tableContainers.forEach(container => {
            const table = container.querySelector('table');
            if (!table) return;

            // Check if table overflows
            function checkOverflow() {
                if (container.scrollWidth > container.clientWidth) {
                    container.classList.add('has-scroll');

                    // Add scroll position classes
                    if (container.scrollLeft === 0) {
                        container.classList.add('scroll-start');
                        container.classList.remove('scroll-middle', 'scroll-end');
                    } else if (container.scrollLeft + container.clientWidth >= container.scrollWidth - 5) {
                        container.classList.add('scroll-end');
                        container.classList.remove('scroll-start', 'scroll-middle');
                    } else {
                        container.classList.add('scroll-middle');
                        container.classList.remove('scroll-start', 'scroll-end');
                    }
                } else {
                    container.classList.remove('has-scroll', 'scroll-start', 'scroll-middle', 'scroll-end');
                }
            }

            checkOverflow();
            container.addEventListener('scroll', checkOverflow);
            window.addEventListener('resize', checkOverflow);
        });
    }
    initTableScrollIndicators();

    // ========== Swipe Gestures for Modals ==========
    function initSwipeToClose() {
        const modals = document.querySelectorAll('.calendar-modal, .modal');

        modals.forEach(modal => {
            let startY = 0;
            let currentY = 0;
            const modalContent = modal.querySelector('.calendar-modal-content, .modal-content');

            if (!modalContent) return;

            modalContent.addEventListener('touchstart', function(e) {
                startY = e.touches[0].clientY;
            }, { passive: true });

            modalContent.addEventListener('touchmove', function(e) {
                currentY = e.touches[0].clientY;
                const diff = currentY - startY;

                // Only allow swipe down when at top of scroll
                if (modalContent.scrollTop === 0 && diff > 0) {
                    modalContent.style.transform = `translateY(${Math.min(diff, 200)}px)`;
                    modalContent.style.opacity = 1 - (diff / 400);
                }
            }, { passive: true });

            modalContent.addEventListener('touchend', function(e) {
                const diff = currentY - startY;

                if (diff > 100) {
                    // Close modal
                    modal.classList.remove('active');
                }

                modalContent.style.transform = '';
                modalContent.style.opacity = '';
                startY = 0;
                currentY = 0;
            });
        });
    }
    initSwipeToClose();

    // ========== Pull to Refresh Indication ==========
    let pullStartY = 0;
    let isPulling = false;

    document.addEventListener('touchstart', function(e) {
        if (window.scrollY === 0) {
            pullStartY = e.touches[0].clientY;
            isPulling = true;
        }
    }, { passive: true });

    document.addEventListener('touchmove', function(e) {
        if (!isPulling) return;

        const pullDistance = e.touches[0].clientY - pullStartY;
        if (pullDistance > 60 && window.scrollY === 0) {
            document.body.classList.add('pull-to-refresh-active');
        }
    }, { passive: true });

    document.addEventListener('touchend', function() {
        if (document.body.classList.contains('pull-to-refresh-active')) {
            document.body.classList.remove('pull-to-refresh-active');
            // Could trigger refresh here if needed
        }
        isPulling = false;
        pullStartY = 0;
    });

    // ========== Double Tap Prevention ==========
    let lastTap = 0;
    document.addEventListener('touchend', function(e) {
        const currentTime = new Date().getTime();
        const tapLength = currentTime - lastTap;

        if (tapLength < 300 && tapLength > 0) {
            e.preventDefault();
        }
        lastTap = currentTime;
    });

    // ========== Card Touch Feedback ==========
    const cards = document.querySelectorAll('.stat-card, .recent-item, .clickable-row');

    cards.forEach(card => {
        card.addEventListener('touchstart', function() {
            this.classList.add('touch-active');
        }, { passive: true });

        card.addEventListener('touchend', function() {
            this.classList.remove('touch-active');
        });

        card.addEventListener('touchcancel', function() {
            this.classList.remove('touch-active');
        });
    });

    // ========== Keyboard Visibility Detection ==========
    const originalHeight = window.innerHeight;

    window.addEventListener('resize', function() {
        if (window.innerHeight < originalHeight * 0.75) {
            document.body.classList.add('keyboard-open');
        } else {
            document.body.classList.remove('keyboard-open');
        }
    });

    // ========== Lazy Load Images ==========
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.add('loaded');
                    }
                    observer.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // ========== Form Input Zoom Prevention (iOS) ==========
    const formInputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="tel"], textarea, select');

    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            if (window.innerWidth <= 768) {
                document.body.classList.add('input-focused');
            }
        });

        input.addEventListener('blur', function() {
            document.body.classList.remove('input-focused');
        });
    });

    // ========== Responsive Debug Mode (Dev Only) ==========
    if (localStorage.getItem('debug-responsive') === 'true') {
        const debugEl = document.createElement('div');
        debugEl.id = 'responsive-debug';
        debugEl.style.cssText = 'position:fixed;bottom:10px;left:10px;background:rgba(0,0,0,0.8);color:#fff;padding:8px 12px;font-size:12px;border-radius:4px;z-index:9999;';
        document.body.appendChild(debugEl);

        function updateDebugInfo() {
            debugEl.textContent = `${window.innerWidth}×${window.innerHeight}`;
        }
        updateDebugInfo();
        window.addEventListener('resize', updateDebugInfo);
    }

    console.log('📱 Mobile enhancements loaded');
});
