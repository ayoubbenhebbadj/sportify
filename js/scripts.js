document.addEventListener('DOMContentLoaded', function() {
    // --- Navbar Scroll Effect ---
    const navbar = document.querySelector('nav');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.style.backgroundColor = 'white';
            navbar.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
        } else {
            navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
            navbar.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
        }
    });

    // --- Smooth Scrolling for Navigation Links ---
    const navLinks = document.querySelectorAll('.nav_links a');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            document.querySelector(targetId).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // --- "Get Started" Button Smooth Scroll ---
    const getStartedBtn = document.querySelector('.writing-section a');
    if (getStartedBtn) {
        getStartedBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            document.querySelector(targetId).scrollIntoView({
                behavior: 'smooth'
            });
        });
    }

    // --- Animate Stadium Details on "View More" Click ---
    const stadiumContainers = document.querySelectorAll('.stadium');
    stadiumContainers.forEach(container => {
        const viewMoreBtn = container.querySelector('.view-more-btn');
        const stadiumDetails = container.querySelector('.stadium-details');

        if (viewMoreBtn && stadiumDetails) {
            viewMoreBtn.addEventListener('click', () => {
                stadiumDetails.classList.toggle('active');
                viewMoreBtn.textContent = stadiumDetails.classList.contains('active') ? 'View Less' : 'View More';
            });
        }
    });

    // --- Animate Stadiums on Scroll ---
    const stadiums = document.querySelectorAll('.stadium');
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.2 // Trigger when 20% of the element is visible
    };

    const stadiumObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.transform = 'translateY(0)';
                entry.target.style.opacity = '1';
                observer.unobserve(entry.target); // Only animate once
            } else {
                entry.target.style.transform = 'translateY(20px)';
                entry.target.style.opacity = '0';
            }
        });
    });

    stadiums.forEach(stadium => {
        stadium.style.transform = 'translateY(20px)'; // Initial off-screen position
        stadium.style.opacity = '0';
        stadiumObserver.observe(stadium);
    });

    // --- Animate Reviews on Scroll ---
    const reviews = document.querySelectorAll('.review');
    const reviewObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.transform = 'translateY(0)';
                entry.target.style.opacity = '1';
                observer.unobserve(entry.target);
            } else {
                entry.target.style.transform = 'translateY(20px)';
                entry.target.opacity = '0';
            }
        });
    }, observerOptions); // Use the same options as stadiums

    reviews.forEach(review => {
        review.style.transform = 'translateY(20px)';
        review.style.opacity = '0';
        reviewObserver.observe(review);
    });

    // --- Highlight Active Navigation Link on Scroll ---
    const sections = document.querySelectorAll('section, .reversed, .rev, footer');
    const navLinksHighlight = document.querySelectorAll('.nav_links a');

    function updateActiveNavLink() {
        let currentSectionId = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            if (window.scrollY >= sectionTop - (window.innerHeight / 3) && window.scrollY < sectionTop + sectionHeight - (window.innerHeight / 3)) {
                currentSectionId = section.id;
            }
        });

        navLinksHighlight.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + currentSectionId && currentSectionId !== '') {
                link.classList.add('active');
            } else if (currentSectionId === '' && link.getAttribute('href') === '#') {
                link.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', updateActiveNavLink);
    updateActiveNavLink(); // Call on initial load
});