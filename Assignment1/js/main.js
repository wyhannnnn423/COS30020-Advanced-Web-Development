// ==========================================================================
// Scroll Progress Indicator & Back-to-Top Button
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    // Select the scroll progress container
    const scrollProgress = document.getElementById('progress');
    if (!scrollProgress) return;

    // Calculate scroll percentage and update the circular indicator
    const calcScrollValue = () => {
        const pos = document.documentElement.scrollTop || document.body.scrollTop;
        const calcHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrollValue = Math.round((pos * 100) / calcHeight);

        // Toggle visibility based on scroll position (show after 100px)
        if (pos > 100) {
            scrollProgress.style.display = 'grid';
        } else {
            scrollProgress.style.display = 'none';
        }

        // Apply a conic gradient to visually represent scroll progress
        scrollProgress.style.background = `conic-gradient(#3b82f6 ${scrollValue}%, #1e293b ${scrollValue}%)`;
    };

    // Scroll smoothly to the top when clicked
    scrollProgress.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Attach scroll and load event listeners
    window.addEventListener('scroll', calcScrollValue);
    window.addEventListener('load', calcScrollValue);
});

// ==========================================================================
// Navigation & Active Indicator
// ==========================================================================
document.addEventListener('DOMContentLoaded', () => {
    const navLinksContainer = document.querySelector('.nav-links');
    const indicator = document.querySelector('.nav-indicator');
    const standardLinks = document.querySelectorAll('.nav-links > a');
    const dropdownWrappers = document.querySelectorAll('.nav-item-dropdown');
    const activeItem = document.querySelector('.nav-links > a.active, .dropdown-trigger.active');

    // Dynamically move the indicator line under the target element
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

    // Set initial indicator position
    if (activeItem) {
        moveIndicatorTo(activeItem);
    }

    // Update indicator position on hover for standard links
    standardLinks.forEach(link => {
        link.addEventListener('mouseenter', () => {
            moveIndicatorTo(link);
        });
    });

    // Update indicator position on hover for dropdown menus
    dropdownWrappers.forEach(wrapper => {
        const trigger = wrapper.querySelector('.dropdown-trigger');
        if (trigger) {
            wrapper.addEventListener('mouseenter', () => {
                moveIndicatorTo(trigger);
            });
        }
    });

    // Revert indicator to the active page link when mouse leaves the nav area
    navLinksContainer.addEventListener('mouseleave', () => {
        if (activeItem) {
            moveIndicatorTo(activeItem);
        } else {
            indicator.style.opacity = '0';
        }
    });

    // Mobile hamburger menu toggle
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
            // Highlight the clicked tab
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const selectedCategory = tab.getAttribute('data-category');
            let visibleCount = 0;

            // Filter cards matching the selected category
            cards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                if (selectedCategory === 'all' || cardCategory === selectedCategory) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Display empty state message if no cards match the filter
            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });
    });
});

// ==========================================================================
// Form Validation Helpers
// ==========================================================================

// Helper to retrieve and trim input values by ID
function valById(id) {
    var el = document.getElementById(id);
    return el ? el.value.trim() : "";
}

// Helper to toggle inline error messages
function setInlineError(id, message) {
    var errorSpan = document.getElementById(id);
    if (errorSpan) {
        errorSpan.textContent = message;
        errorSpan.style.display = (message === "") ? "none" : "block";
    }
}

// Initialize form validation on page load
window.onload = function() {
    var form = document.getElementById("registerForm");
    if (form) {
        form.onsubmit = validateRegisterForm;
    }
};

// ==========================================================================
// Registration Form Validation Logic
// ==========================================================================
function validateRegisterForm() {
    var errors = [];
    var errorSpans = document.querySelectorAll(".auth-form .error-message");

    // Reset all previous inline errors
    errorSpans.forEach(function(span) {
        span.style.display = "none";
        span.textContent = "";
    });

    // 1. First Name Validation (Required + Letters/Spaces only)
    var fname = valById("first_name");
    if (!fname) {
        errors.push("First name is required.");
        setInlineError("first_name_error", "First name is required.");
    } else if (!/^[A-Za-z ]+$/.test(fname)) {
        var msg = "First name may only contain letters and spaces.";
        errors.push(msg);
        setInlineError("first_name_error", msg);
    }

    // 2. Last Name Validation (Required + Letters/Spaces only)
    var lname = valById("last_name");
    if (!lname) {
        errors.push("Last name is required.");
        setInlineError("last_name_error", "Last name is required.");
    } else if (!/^[A-Za-z ]+$/.test(lname)) {
        var msg = "Last name may only contain letters and spaces.";
        errors.push(msg);
        setInlineError("last_name_error", msg);
    }

    // 3. Date of Birth Validation
    var dob = valById("dob");
    if (!dob) {
        errors.push("Date of birth is required.");
        setInlineError("dob_error", "Date of birth is required.");
    }

    // 4. Gender Validation
    var gender = valById("gender");
    if (gender === "" || gender === "-- Select Gender --") {
        errors.push("Please select a gender.");
        setInlineError("gender_error", "Please select a gender.");
    }

    // 5. Email Validation (Required + Standard Regex)
    var email = valById("email");
    if (!email) {
        errors.push("Email is required.");
        setInlineError("email_error", "Email is required.");
    } else if (!/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/.test(email)) {
        var msg = "Invalid email format.";
        errors.push(msg);
        setInlineError("email_error", msg);
    }

    // 6. Hometown Validation
    var hometown = valById("hometown");
    if (!hometown) {
        errors.push("Hometown is required.");
        setInlineError("hometown_error", "Hometown is required.");
    }

    // 7. Password Validation (Required + Min 8 chars, 1 number, 1 symbol)
    var password = valById("password");
    if (!password) {
        errors.push("Password is required.");
        setInlineError("password_error", "Password is required.");
    } else {
        if (password.length < 8) {
            var msg = "Password must be at least 8 characters long.";
            errors.push(msg);
            setInlineError("password_error", msg);
        } else if (!/[0-9]/.test(password)) {
            var msg = "Password must contain at least one number.";
            errors.push(msg);
            setInlineError("password_error", msg);
        } else if (!/[^A-Za-z0-9]/.test(password)) {
            var msg = "Password must contain at least one symbol.";
            errors.push(msg);
            setInlineError("password_error", msg);
        }
    }

    // 8. Confirm Password Validation (Match check)
    var confirm_password = valById("confirm_password");
    if (!confirm_password) {
        errors.push("Confirm password is required.");
        setInlineError("confirm_password_error", "Confirm password is required.");
    } else if (password && confirm_password !== password) {
        var msg = "Passwords do not match.";
        errors.push(msg);
        setInlineError("confirm_password_error", msg);
    }

    // Final Validation Check: Display summary alert if errors exist
    if (errors.length) {
        alert("Please fix the following:\n\n" + errors.join("\n"));
        return false; // Prevent form submission
    } else {
        return true; // Allow form submission
    }
}

// ==========================================================================
// Registration Form See Password       
// ==========================================================================
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input.type === 'password') {
                input.type = 'text';
                btn.textContent = '🔒︎';
            } else {
                input.type = 'password';
                btn.textContent = '👁';
            }
        });
    });
});