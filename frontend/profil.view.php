<?php
$h = fn($s) => htmlspecialchars((string)($s ?? ''), ENT_QUOTES, 'UTF-8');
$icons    = ['💻','🚀','🎯','⚡','🔧','🌐','📱','🧩'];
$nomCourt = strtoupper(explode(' ', $nom)[0]);

$photoPosition = $photoPosition ?? 'right';
$sectionsOrder = $sectionsOrder ?? ['nom_titre','competences','projets','contact','reseaux'];
?>
<!DOCTYPE html>
<html lang="fr" class="<?= $h($themeClass) ?>">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= $h($nom) ?> — Portfolio</title>
    <meta name="description" content="<?= $h(substr($bio, 0, 155)) ?>"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="<?= APP_URL ?>/frontend/css/profil.css"/>
</head>
<body>

<!-- NAVBAR -->
<nav class="port-nav" id="navbar">
    <a class="nav-logo" href="#"><?= $h($nomCourt) ?><span class="nav-logo-dot">.</span></a>
    <ul class="nav-menu">
        <li><a href="#home"  class="nav-link active">Accueil</a></li>
        <li><a href="#about" class="nav-link">À propos</a></li>
        <?php if ($competences): ?><li><a href="#skills"   class="nav-link">Compétences</a></li><?php endif; ?>
        <?php if ($projets):     ?><li><a href="#projects" class="nav-link">Projets</a></li><?php endif; ?>
        <li><a href="#contact" class="nav-link">Contact</a></li>
    </ul>
</nav>

<!-- HERO -->
<section id="home" class="hero-section">
    <div class="container-fluid px-4 px-lg-5">
        <div class="row align-items-center min-vh-100 g-0">

            <?php if ($photoPosition === 'left'): ?>
            <div class="col-lg-6 hero-photo-col">
                <div class="hero-photo-wrap">
                    <div class="hero-photo-bg"></div>
                    <?php if ($photoUrl): ?>
                        <img src="<?= $h($photoUrl) ?>" alt="<?= $h($nom) ?>" class="hero-photo"/>
                    <?php else: ?>
                        <div class="hero-avatar-letter"><?= $h($initiale) ?></div>
                    <?php endif; ?>
                    <div class="floating-badge badge-available">
                        <span class="available-dot"></span><span>Disponible</span>
                    </div>
                    <?php if ($profil['localisation']): ?>
                        <div class="floating-badge badge-location">
                            <i class="bi bi-geo-alt-fill accent-icon"></i><?= $h($profil['localisation']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Texte -->
            <div class="col-lg-6 hero-text-col">
                <p class="hero-hello">Salut !</p>
                <h1 class="hero-heading">
                    Je suis <span class="accent"><?= $h($titre) ?></span>
                    <?php if ($ville): ?><br/>basé à <span class="accent"><?= $h($ville) ?></span><?php endif; ?>
                </h1>
                <?php if ($bio): ?><p class="hero-bio"><?= $h($bio) ?></p><?php endif; ?>
                <div class="hero-btns">
                    <?php if ($profil['email_public']): ?>
                        <a href="mailto:<?= $h($profil['email_public']) ?>" class="btn-hire">
                            Me contacter <i class="bi bi-arrow-right-circle"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ($projets): ?>
                        <a href="#projects" class="btn-outline-port">
                            Mon travail <i class="bi bi-grid-3x3-gap"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <?php if (count($projets) > 0 || count($competences) > 0): ?>
                <div class="hero-stats">
                    <?php if (count($projets) > 0): ?>
                        <div class="hero-stat">
                            <span class="hero-stat-num"><?= count($projets) ?>+</span>
                            <span class="hero-stat-label">Projets réalisés</span>
                        </div>
                    <?php endif; ?>
                    <?php if (count($competences) > 0): ?>
                        <div class="hero-stat">
                            <span class="hero-stat-num"><?= count($competences) ?>+</span>
                            <span class="hero-stat-label">Compétences</span>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <?php if ($photoPosition === 'right'): ?>
            <div class="col-lg-6 hero-photo-col">
                <div class="hero-photo-wrap">
                    <div class="hero-photo-bg"></div>
                    <?php if ($photoUrl): ?>
                        <img src="<?= $h($photoUrl) ?>" alt="<?= $h($nom) ?>" class="hero-photo"/>
                    <?php else: ?>
                        <div class="hero-avatar-letter"><?= $h($initiale) ?></div>
                    <?php endif; ?>
                    <div class="floating-badge badge-available">
                        <span class="available-dot"></span><span>Disponible</span>
                    </div>
                    <?php if ($profil['localisation']): ?>
                        <div class="floating-badge badge-location">
                            <i class="bi bi-geo-alt-fill accent-icon"></i><?= $h($profil['localisation']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<hr class="section-sep"/>

<?php
// Éviter les doublons
$rendered = [];

foreach ($sectionsOrder as $sec):
    // Normaliser les anciens noms
    if (in_array($sec, ['bio','about'])) $sec = 'nom_titre';
    if ($sec === 'skills')   $sec = 'competences';
    if ($sec === 'projects') $sec = 'projets';

    // Ne pas afficher deux fois la même section
    if (in_array($sec, $rendered)) continue;
    $rendered[] = $sec;

    switch ($sec):

        case 'nom_titre':
?>
<!-- ABOUT -->
<section class="port-section" id="about">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 reveal">
                <p class="section-tag">About Me</p>
                <h2 class="section-title">Qui suis-<span class="accent">je</span> ?</h2>
                <p class="section-text"><?= $bio ? nl2br($h($bio)) : 'Aucune biographie renseignée.' ?></p>
                <?php if ($profil['localisation']): ?>
                    <p class="info-location"><i class="bi bi-geo-alt-fill accent-icon"></i> <?= $h($profil['localisation']) ?></p>
                <?php endif; ?>
            </div>
            <div class="col-lg-6 offset-lg-1 reveal reveal-delay-1">
                <?php
                $infos = [
                    ['icon'=>'person-fill',    'label'=>'Nom',       'val'=>$nom,                    'href'=>false],
                    ['icon'=>'briefcase-fill', 'label'=>'Métier',    'val'=>$titre,                  'href'=>false],
                    ['icon'=>'envelope-fill',  'label'=>'Email',     'val'=>$profil['email_public'], 'href'=>'mailto:'],
                    ['icon'=>'telephone-fill', 'label'=>'Téléphone', 'val'=>$profil['telephone'],    'href'=>'tel:'],
                    ['icon'=>'github',         'label'=>'GitHub',    'val'=>$profil['github'],       'href'=>''],
                    ['icon'=>'globe2',         'label'=>'Site web',  'val'=>$profil['site_web'],     'href'=>''],
                ];
                ?>
                <div class="row g-3">
                    <?php foreach ($infos as $info): ?>
                        <?php if (empty($info['val'])) continue; ?>
                        <div class="col-sm-6">
                            <div class="info-card">
                                <div class="info-card-label"><i class="bi bi-<?= $h($info['icon']) ?>"></i> <?= $h($info['label']) ?></div>
                                <div class="info-card-value">
                                    <?php if ($info['href'] !== false): ?>
                                        <a href="<?= $h($info['href'].$info['val']) ?>" target="_blank"><?= $h($info['val']) ?></a>
                                    <?php else: ?>
                                        <?= $h($info['val']) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="social-row mt-4">
                    <?php if ($profil['github']): ?><a href="<?= $h($profil['github']) ?>" target="_blank" class="social-pill"><i class="bi bi-github"></i> GitHub</a><?php endif; ?>
                    <?php if ($profil['linkedin']): ?><a href="<?= $h($profil['linkedin']) ?>" target="_blank" class="social-pill"><i class="bi bi-linkedin"></i> LinkedIn</a><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<hr class="section-sep"/>
<?php
        break;

        case 'competences':
            if ($competences):
?>
<!-- SKILLS -->
<section class="port-section" id="skills">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <p class="section-tag justify-content-center">Skills</p>
            <h2 class="section-title">Mes <span class="accent">compétences</span></h2>
        </div>
        <div class="skills-grid reveal reveal-delay-1">
            <?php foreach ($competences as $comp): ?>
                <span class="skill-chip"><?= $h($comp) ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<hr class="section-sep"/>
<?php
            endif;
        break;

        case 'projets':
            if ($projets):
?>
<!-- PROJETS -->
<section class="port-section" id="projects">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <p class="section-tag justify-content-center">Portfolio</p>
            <h2 class="section-title">Mes <span class="accent">projets</span></h2>
        </div>
        <div class="row g-4">
            <?php foreach ($projets as $i => $p): ?>
                <div class="col-md-6 col-lg-4 reveal reveal-delay-<?= ($i % 3) + 1 ?>">
                    <div class="project-card">
                        <div class="project-thumb">
                            <span class="project-icon"><?= $icons[$i % count($icons)] ?></span>
                            <span class="project-num"><?= str_pad($i+1, 2, '0', STR_PAD_LEFT) ?></span>
                        </div>
                        <div class="project-body">
                            <h3 class="project-title"><?= $h($p['titre']) ?></h3>
                            <?php if ($p['description']): ?><p class="project-desc"><?= $h($p['description']) ?></p><?php endif; ?>
                            <?php if ($p['url']): ?><a href="<?= $h($p['url']) ?>" target="_blank" class="project-link">Voir le projet <i class="bi bi-arrow-right"></i></a><?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<hr class="section-sep"/>
<?php
            endif;
        break;

        case 'contact':
?>
<!-- CONTACT -->
<section class="port-section" id="contact">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-card reveal">
                    <p class="section-tag justify-content-center">Contacts</p>
                    <h2 class="section-title">Travaillons <span class="accent">ensemble !</span></h2>
                    <p class="contact-sub">Vous avez un projet en tête ? Contactez-moi et discutons-en !</p>
                    <div class="contact-btns">
                        <?php if ($profil['email_public']): ?>
                            <a href="mailto:<?= $h($profil['email_public']) ?>" class="btn-hire">
                                <i class="bi bi-envelope-fill"></i> <?= $h($profil['email_public']) ?>
                            </a>
                        <?php endif; ?>
                        <?php if ($profil['telephone']): ?>
                            <a href="tel:<?= $h($profil['telephone']) ?>" class="btn-outline-port">
                                <i class="bi bi-telephone-fill"></i> <?= $h($profil['telephone']) ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<hr class="section-sep"/>
<?php
        break;

        case 'reseaux':
            if ($profil['github'] || $profil['linkedin']):
?>
<!-- RÉSEAUX SOCIAUX -->
<section class="port-section" id="reseaux">
    <div class="container text-center">
        <p class="section-tag justify-content-center">Réseaux</p>
        <h2 class="section-title mb-4">Me <span class="accent">suivre</span></h2>
        <div class="social-row justify-content-center">
            <?php if ($profil['github']): ?><a href="<?= $h($profil['github']) ?>" target="_blank" class="social-pill"><i class="bi bi-github"></i> GitHub</a><?php endif; ?>
            <?php if ($profil['linkedin']): ?><a href="<?= $h($profil['linkedin']) ?>" target="_blank" class="social-pill"><i class="bi bi-linkedin"></i> LinkedIn</a><?php endif; ?>
        </div>
    </div>
</section>
<hr class="section-sep"/>
<?php
            endif;
        break;

    endswitch;
endforeach;
?>

<!-- FOOTER -->
<footer class="port-footer">
    <div class="container">
        <p class="mb-1">© <?= date('Y') ?> — <strong><?= $h($nom) ?></strong></p>
        <p class="footer-credit">Créé avec <a href="<?= APP_URL ?>/frontend/index.html">ProfilSite</a></p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= APP_URL ?>/frontend/js/profil.js"></script>
</body>
</html>
