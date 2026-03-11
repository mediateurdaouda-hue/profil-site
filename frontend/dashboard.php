<?php
/*
 * Vue : tableau de bord utilisateur
 * Inclus par backend/dashboard.php — toutes les variables sont déjà définies
 */
if (!defined('APP_URL')) {
    require_once __DIR__ . '/../backend/config.php';
}
if (!isset($user))          $user          = [];
if (!isset($projets))       $projets       = [];
if (!isset($themes))        $themes        = [];
if (!isset($succes))        $succes        = '';
if (!isset($erreur))        $erreur        = '';
if (!isset($bienvenue))     $bienvenue     = false;
if (!isset($sectionActive)) $sectionActive = 'profil';

$username  = $user['username'] ?? ($_SESSION['username'] ?? 'Utilisateur');
$photo     = $user['photo']    ?? '';
$photoUrl  = $photo ? APP_URL . '/uploads/' . $photo : '';
$initiale  = strtoupper(substr($username, 0, 1));
$minisite  = APP_URL . '/profil.php?u=' . urlencode($username);

// Fonction locale pour éviter l'erreur si e() n'est pas définie
$h = fn($s) => htmlspecialchars((string)($s ?? ''), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Tableau de bord — ProfilSite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="<?= APP_URL ?>/frontend/css/style.css"/>
</head>
<body>

<!-- Bouton menu mobile -->
<button class="btn btn-primary btn-sm d-lg-none position-fixed m-3 z-3"
        style="top:0;left:0;"
        onclick="openSidebar()">
    <i class="bi bi-list fs-5"></i>
</button>

<!-- Overlay (ferme la sidebar sur mobile) -->
<div id="sidebarOverlay"
     class="d-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50"
     style="z-index:299;"
     onclick="closeSidebar()">
</div>

<!-- ============================================================
     BARRE LATÉRALE
     ============================================================ -->
<aside class="sidebar" id="sidebar">

    <!-- Logo -->
    <div class="sidebar-brand">
        <a href="<?= APP_URL ?>/frontend/index.html" class="text-decoration-none">
            <span class="fw-bold font-display fs-5">
                Profil<span class="text-primary">Site</span>
            </span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-grow-1">
        <ul class="list-unstyled mb-0">
            <li>
                <a href="#"
                   class="sidebar-link <?= $sectionActive === 'profil' ? 'active' : '' ?>"
                   onclick="showSection('profil', this); return false;">
                    <i class="bi bi-person-circle"></i> Mon Profil
                </a>
            </li>
            <li>
                <a href="#"
                   class="sidebar-link <?= $sectionActive === 'projets' ? 'active' : '' ?>"
                   onclick="showSection('projets', this); return false;">
                    <i class="bi bi-folder2-open"></i> Mes Projets
                    <span class="badge bg-primary bg-opacity-10 text-primary ms-auto">
                        <?= count($projets) ?>
                    </span>
                </a>
            </li>
            <li class="mt-2 pt-2 border-top">
                <a href="<?= $h($minisite) ?>" target="_blank" class="sidebar-link">
                    <i class="bi bi-box-arrow-up-right"></i> Voir mon site
                </a>
            </li>
        </ul>
    </nav>

    <!-- Utilisateur connecté -->
    <div class="border-top pt-3 mt-3">
        <div class="d-flex align-items-center gap-2 mb-3 overflow-hidden">
            <div class="sidebar-avatar">
                <?php if ($photoUrl): ?>
                    <img src="<?= $h($photoUrl) ?>" alt="Photo de profil"/>
                <?php else: ?>
                    <?= $h($initiale) ?>
                <?php endif; ?>
            </div>
            <div class="overflow-hidden">
                <div class="fw-semibold small text-truncate">
                    <?= $h($user['nom_complet'] ?? $username) ?>
                </div>
                <div class="text-muted" style="font-size:.74rem;">@<?= $h($username) ?></div>
            </div>
        </div>
        <a href="<?= APP_URL ?>/backend/auth/logout.php"
           class="btn btn-outline-danger btn-sm w-100">
            <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
        </a>
    </div>

</aside>

<!-- ============================================================
     CONTENU PRINCIPAL DU DASHBOARD
     ============================================================ -->
<main class="dashboard-main">

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-start mb-4 pt-2">
        <div>
            <h1 class="h3 fw-bold font-display mb-1">Tableau de bord</h1>
            <p class="text-muted small mb-0">Gérez votre profil et votre mini-site</p>
        </div>
        <a href="<?= $h($minisite) ?>" target="_blank"
           class="btn btn-primary btn-sm d-none d-sm-inline-flex align-items-center gap-2">
            <i class="bi bi-eye"></i> Voir mon site
        </a>
    </div>

    <!-- Alerte bienvenue (première connexion) -->
    <?php if ($bienvenue): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <div>
                <strong>Bienvenue, <?= $h($username) ?> !</strong>
                Votre mini-site est accessible à :
                <a href="<?= $h($minisite) ?>" target="_blank" class="fw-semibold"><?= $h($minisite) ?></a>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Alertes succès / erreur -->
    <?php if ($succes): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 py-2 small mb-4" role="alert">
            <i class="bi bi-check-circle-fill flex-shrink-0"></i> <?= $h($succes) ?>
            <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($erreur): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 py-2 small mb-4" role="alert">
            <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i> <?= $h($erreur) ?>
        </div>
    <?php endif; ?>

    <!-- Bandeau URL du mini-site -->
    <div class="panel d-flex align-items-center gap-3 flex-wrap">
        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
             style="width:44px;height:44px;">
            <i class="bi bi-link-45deg text-primary fs-5"></i>
        </div>
        <div class="flex-grow-1 overflow-hidden">
            <div class="text-muted small mb-1">Votre mini-site personnel :</div>
            <a href="<?= $h($minisite) ?>" target="_blank"
               class="fw-semibold text-primary text-decoration-none text-truncate d-block">
                <?= $h($minisite) ?>
            </a>
        </div>
        <button id="btnCopy" class="btn btn-outline-primary btn-sm flex-shrink-0"
                onclick="copyUrl('<?= $h($minisite) ?>')">
            <i class="bi bi-clipboard me-1"></i>Copier
        </button>
    </div>

    <!-- ============================================================
         SECTION PROFIL
         ============================================================ -->
    <div id="section-profil"
         class="dash-section <?= $sectionActive === 'projets' ? 'd-none' : '' ?>">
        <div class="panel">

            <h5 class="fw-bold font-display mb-4 pb-3 border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-person-circle text-primary"></i>
                Informations personnelles
            </h5>

            <form method="POST"
                  action="<?= APP_URL ?>/backend/dashboard.php"
                  enctype="multipart/form-data">
                <input type="hidden" name="action" value="profil"/>

                <!-- Photo de profil -->
                <div class="mb-4">
                    <label class="form-label small fw-medium">Photo de profil</label>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-lg">
                            <?php if ($photoUrl): ?>
                                <img src="<?= $h($photoUrl) ?>" alt="Photo" id="photoPreview"/>
                            <?php else: ?>
                                <span id="photoInitiale"><?= $h($initiale) ?></span>
                                <img src="" id="photoPreview" style="display:none;" alt=""/>
                            <?php endif; ?>
                        </div>
                        <div>
                            <input type="file" name="photo" class="form-control form-control-sm"
                                   accept="image/*" onchange="previewPhoto(this)"/>
                            <div class="form-text">JPG, PNG, GIF, WEBP — 2 Mo max</div>
                        </div>
                    </div>
                </div>

                <!-- Champs du profil -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-medium">Nom complet</label>
                        <input type="text" name="nom_complet" class="form-control"
                               placeholder="Jean Dupont"
                               value="<?= $h($user['nom_complet'] ?? '') ?>"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-medium">Titre professionnel</label>
                        <input type="text" name="titre" class="form-control"
                               placeholder="Développeur Web Full Stack"
                               value="<?= $h($user['titre'] ?? '') ?>"/>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-medium">Biographie</label>
                        <textarea name="bio" class="form-control" rows="3"
                                  placeholder="Parlez de vous, de votre parcours..."><?= $h($user['bio'] ?? '') ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-medium">
                            Compétences
                            <span class="fw-normal text-muted">(séparées par des virgules)</span>
                        </label>
                        <input type="text" name="competences" class="form-control"
                               placeholder="PHP, JavaScript, MySQL, HTML, CSS"
                               value="<?= $h($user['competences'] ?? '') ?>"/>
                    </div>
                    <div class="col-md-6">