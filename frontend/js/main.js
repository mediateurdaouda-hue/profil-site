// ============================================================
//  PROFILSITE — JAVASCRIPT PRINCIPAL
//  Fonctions partagées entre toutes les pages
// ============================================================

document.addEventListener('DOMContentLoaded', function () {

    // ----------------------------------------------------------
    // 1. NAVBAR — ombre au scroll
    // ----------------------------------------------------------
    const navbar = document.getElementById('navbar');
    if (navbar) {
        const toggleShadow = () =>
            navbar.classList.toggle('scrolled', window.scrollY > 40);
        window.addEventListener('scroll', toggleShadow, { passive: true });
    }

    // ----------------------------------------------------------
    // 2. DÉFILEMENT FLUIDE des ancres internes (#section)
    // ----------------------------------------------------------
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                const offsetTop = target.getBoundingClientRect().top
                                + window.pageYOffset - 80;
                window.scrollTo({ top: offsetTop, behavior: 'smooth' });
            }
        });
    });

    // ----------------------------------------------------------
    // 3. ANIMATION fade-up au scroll (Intersection Observer)
    // ----------------------------------------------------------
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target); // N'animer qu'une seule fois
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.feature-card, .step-card, .template-card')
            .forEach(el => {
                el.classList.add('fade-up');
                observer.observe(el);
            });

    // ----------------------------------------------------------
    // 4. SÉLECTION des cartes template (page d'accueil)
    // ----------------------------------------------------------
    document.querySelectorAll('.template-card').forEach(card => {
        card.addEventListener('click', function () {
            document.querySelectorAll('.template-card')
                    .forEach(c => c.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // ----------------------------------------------------------
    // 5. FORMULAIRE CONTACT — simulation d'envoi
    // ----------------------------------------------------------
    const formContact = document.getElementById('formContact');
    if (formContact) {
        formContact.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = this.querySelector('[type="submit"]');
            const orig = btn.innerHTML;
            btn.disabled  = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Envoi...';
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Message envoyé !';
                btn.classList.replace('btn-primary', 'btn-success');
                this.reset();
            }, 1400);
        });
    }

    // ----------------------------------------------------------
    // 6. SÉLECTEUR DE THÈME dans le dashboard
    // ----------------------------------------------------------
    document.querySelectorAll('.theme-label input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.theme-box')
                    .forEach(b => b.classList.remove('selected'));
            this.closest('.theme-label')
                .querySelector('.theme-box')
                .classList.add('selected');
        });
    });

});

// ============================================================
//  FONCTIONS GLOBALES (appelées depuis les attributs HTML)
// ============================================================

/**
 * Afficher / masquer le mot de passe
 * @param {string} inputId  - id du champ <input>
 * @param {string} iconId   - id de l'icône Bootstrap
 */
function togglePwd(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (!input || !icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}

/**
 * Copier l'URL du mini-site dans le presse-papiers
 * @param {string} url
 */
function copyUrl(url) {
    navigator.clipboard.writeText(url).then(() => {
        const btn = document.getElementById('btnCopy');
        if (!btn) return;
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2 me-1"></i>Copié !';
        btn.classList.replace('btn-outline-primary', 'btn-success');
        setTimeout(() => {
            btn.innerHTML = orig;
            btn.classList.replace('btn-success', 'btn-outline-primary');
        }, 2200);
    });
}

/**
 * Prévisualiser la photo avant upload
 * @param {HTMLInputElement} input
 */
function previewPhoto(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = ev => {
        const img  = document.getElementById('photoPreview');
        const init = document.getElementById('photoInitiale');
        if (img)  { img.src = ev.target.result; img.style.display = 'block'; }
        if (init) { init.style.display = 'none'; }
    };
    reader.readAsDataURL(input.files[0]);
}

/**
 * Afficher une section du dashboard et masquer les autres
 * @param {string} sectionId  - 'profil' ou 'projets'
 * @param {HTMLElement} link  - élément du lien sidebar cliqué
 */
function showSection(sectionId, link) {
    document.querySelectorAll('.dash-section')
            .forEach(s => s.classList.add('d-none'));

    const target = document.getElementById('section-' + sectionId);
    if (target) target.classList.remove('d-none');

    document.querySelectorAll('.sidebar-link')
            .forEach(l => l.classList.remove('active'));
    if (link) link.classList.add('active');

    // Fermer la sidebar sur mobile
    closeSidebar();
}

/** Ouvrir la sidebar mobile */
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebarOverlay').classList.remove('d-none');
}

/** Fermer la sidebar mobile */
function closeSidebar() {
    document.getElementById('sidebar')?.classList.remove('open');
    document.getElementById('sidebarOverlay')?.classList.add('d-none');
}
window.addEventListener("scroll", function(){
    let navbar = document.getElementById("navbar");

    if(window.scrollY > 50){
        navbar.style.background = "white";
        navbar.style.boxShadow = "0 10px 25px rgba(0,0,0,0.08)";
    }else{
        navbar.style.background = "rgba(255,255,255,0.75)";
        navbar.style.boxShadow = "0 5px 20px rgba(0,0,0,0.05)";
    }
    // Masquer/afficher navbar au scroll
let lastScroll = 0;
window.addEventListener('scroll', function() {
    const currentScroll = window.scrollY;
    const navbar = document.getElementById('navbar');
    if (!navbar) return;

    if (currentScroll > lastScroll && currentScroll > 100) {
        // Scroll vers le bas — cacher
        navbar.style.transform = 'translateY(-100%)';
        navbar.style.transition = 'transform 0.3s ease';
    } else {
        // Scroll vers le haut — afficher
        navbar.style.transform = 'translateY(0)';
    }
    lastScroll = currentScroll;
});
});
