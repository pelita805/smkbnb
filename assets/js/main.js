/**
 * Main JavaScript - SMK Bangun Nusa Bangsa
 */
document.addEventListener('DOMContentLoaded', () => {
    // Mobile Public Navbar Toggle
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('open');
        });
    }

    // Mobile Portal Sidebar Toggle
    const portalMenuBtn = document.getElementById('portalMenuBtn');
    const portalSidebar = document.getElementById('portalSidebar');

    if (portalMenuBtn && portalSidebar) {
        portalMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            portalSidebar.classList.toggle('open');
        });

        document.addEventListener('click', (e) => {
            if (portalSidebar.classList.contains('open') && !portalSidebar.contains(e.target)) {
                portalSidebar.classList.remove('open');
            }
        });
    }
});
