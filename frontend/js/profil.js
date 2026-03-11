/* ============================================================
   profil.js — Scripts du mini-site portfolio
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

    /* ==============================
       SCROLL REVEAL
       ============================== */
    const revealObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal').forEach(function (el) {
        revealObserver.observe(el);
    });

    /* ==============================
       NAVBAR — Ombre au scroll
       ============================== */
    var navbar = document.getElementById('navbar');

    window.addEventListener('scroll', function () {
        if (window.scrollY > 40) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }, { passive: true });

    /* ==============================
       NAVBAR — Lien actif au scroll
       ============================== */
    var sections = document.querySelectorAll('section[id]');
    var navLinks = document.querySelectorAll('.nav-menu .nav-link');

    window.addEventListener('scroll', function () {
        var current = '';

        sections.forEach(function (section) {
            if (window.scrollY >= section.offsetTop - 120) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(function (link) {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    }, { passive: true });

});
