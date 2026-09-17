// ==========================================
// Scroll Progress Indicator & Back-to-Top Button
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    // Select the scroll progress container element from the DOM
    const scrollProgress = document.getElementById('progress');
    // Exit early if the progress element does not exist on the current page
    if (!scrollProgress) return;

    // Calculate scroll percentage and update the circular indicator background & visibility
    const calcScrollValue = () => {
        // Get the current vertical scroll position (with cross-browser fallback)
        const pos = document.documentElement.scrollTop || document.body.scrollTop;
        // Calculate the total scrollable height: entire document height minus the visible viewport height
        const calcHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        // Calculate percentage scrolled, rounded to the nearest whole integer (0 - 100)
        const scrollValue = Math.round((pos * 100) / calcHeight);

        // Display the button when scrolled down more than 100px; otherwise keep it hidden
        if (pos > 100) {
            scrollProgress.style.display = 'grid';
        } else {
            scrollProgress.style.display = 'none';
        }

        // Dynamically apply a conic gradient: filled with blue up to the current progress, dark background for remainder
        scrollProgress.style.background = `conic-gradient(#3b82f6 ${scrollValue}%, #1e293b ${scrollValue}%)`;
    };

    // Smoothly scroll back to the top when the button is clicked
    scrollProgress.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Recompute scroll position on page scroll and initial asset load
    window.addEventListener('scroll', calcScrollValue);
    window.addEventListener('load', calcScrollValue);
});

// ==========================================
// Nav
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    const navLinksContainer = document.querySelector('.nav-links');
    const indicator = document.querySelector('.nav-indicator');

    // Select standalone links and all dropdown wrappers
    const standardLinks = document.querySelectorAll('.nav-links > a');
    const dropdownWrappers = document.querySelectorAll('.nav-item-dropdown');
    const activeItem = document.querySelector('.nav-links > a.active, .dropdown-trigger.active');

    // Recalculates indicator positioning relative to .nav-links
    function moveIndicatorTo(element) {
        if (!element || !indicator || !navLinksContainer) return;

        const containerRect = navLinksContainer.getBoundingClientRect();
        const elementRect = element.getBoundingClientRect();

        const leftOffset = elementRect.left - containerRect.left;
        const elementWidth = elementRect.width;

        indicator.style.left = `${leftOffset}px`;
        indicator.style.width = `${elementWidth}px`;
        indicator.style.opacity = '1';
    }

    // Initialize indicator on the active route
    if (activeItem) {
        moveIndicatorTo(activeItem);
    }

    // Standard top-level navigation links hover
    standardLinks.forEach(link => {
        link.addEventListener('mouseenter', () => {
            moveIndicatorTo(link);
        });
    });

    // Lock indicator under the corresponding trigger when hovering any dropdown menu
    dropdownWrappers.forEach(wrapper => {
        const trigger = wrapper.querySelector('.dropdown-trigger');
        if (trigger) {
            wrapper.addEventListener('mouseenter', () => {
                moveIndicatorTo(trigger);
            });
        }
    });

    // Reset indicator back to the active element when the mouse leaves navigation
    navLinksContainer.addEventListener('mouseleave', () => {
        if (activeItem) {
            moveIndicatorTo(activeItem);
        } else {
            indicator.style.opacity = '0';
        }
    });

    // Mobile drawer toggle handler
    const hamburgerBtn = document.getElementById('hamburger-btn');
    if (hamburgerBtn && navLinksContainer) {
        hamburgerBtn.addEventListener('click', () => {
            navLinksContainer.classList.toggle('active');
            hamburgerBtn.classList.toggle('toggle');
        });
    }
});
// ==========================================================================
// Community Post Category Filter
// ==========================================================================
document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.filter-tab');
    const cards = document.querySelectorAll('.post-card');
    const emptyState = document.getElementById('emptyState');

    if (!tabs.length || !cards.length) return;

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Update active state on tabs
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const selectedCategory = tab.getAttribute('data-category');
            let visibleCount = 0;

            // Filter post cards based on selected category
            cards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                if (selectedCategory === 'all' || cardCategory === selectedCategory) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Toggle empty state message if no cards match
            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });
    });
});