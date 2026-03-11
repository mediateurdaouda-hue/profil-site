<?php
/*
 * Vue : tableau de bord utilisateur
 * Inclus par backend/dashboard.php
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

$username = $user['username'] ?? ($_SESSION['username'] ?? 'Utilisateur');
$photo    = $user['photo']    ?? '';
$photoUrl = $photo ? APP_URL . '/uploads/' . $photo : '';
$initiale = strtoupper(substr($username, 0, 1));
$minisite = APP_URL . '/profil.php?u=' . urlencode($username);
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

<button class="btn btn-primary btn-sm d-lg-none position-fixed m-3 z-3"
        style="top:0;left:0;" onclick="openSidebar()">
    <i class="bi bi-list fs-5"></i>
</button>

<div id="sidebarOverlay"
     class="d-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50"
     style="z-index:299;" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="<?= APP_URL ?>/frontend/index.html" class="text-decoration-none">
            <span class="fw-bold font-display fs-5">Profil<span class="text-primary">Site</span></span>
        </a>
    </div>
    <nav class="flex-grow-1">
        <ul class="list-unstyled mb-0">
            <li>
                <a href="#" class="sidebar-link <?= $sectionActive === 'profil' ? 'active' : '' ?>"
                   onclick="showSection('profil', this); return false;">
                    <i class="bi bi-person-circle"></i> Mon Profil
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link <?= $sectionActive === 'projets' ? 'active' : '' ?>"
                   onclick="showSection('projets', this); return false;">
                    <i class="bi bi-folder2-open"></i> Mes Projets
                    <span class="badge bg-primary bg-opacity-10 text-primary ms-auto"><?= count($projets) ?></span>
                </a>
            </li>
            <li class="mt-2 pt-2 border-top">
                <a href="<?= $h($minisite) ?>" target="_blank" class="sidebar-link">
                    <i class="bi bi-box-arrow-up-right"></i> Voir mon site
                </a>
            </li>
        </ul>
    </nav>
    <div class="border-top pt-3 mt-3">
        <div class="d-flex align-items-center gap-2 mb-3 overflow-hidden">
            <div class="sidebar-avatar">
                <?php if ($photoUrl): ?>
                    <img src="<?= $h($photoUrl) ?>" alt="Photo"/>
                <?php else: ?>
                    <?= $h($initiale) ?>
                <?php endif; ?>
            </div>
            <div class="overflow-hidden">
                <div class="fw-semibold small text-truncate"><?= $h($user['nom_complet'] ?? $username) ?></div>
                <div class="text-muted" style="font-size:.74rem;">@<?= $h($username) ?></div>
            </div>
        </div>
        <a href="<?= APP_URL ?>/backend/auth/logout.php" class="btn btn-outline-danger btn-sm w-100">
            <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
        </a>
    </div>
</aside>

<!-- CONTENU PRINCIPAL -->
<main class="dashboard-main">

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

    <?php if ($bienvenue): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <div>
                <strong>Bienvenue, <?= $h($username) ?> !</strong>
                Votre mini-site : <a href="<?= $h($minisite) ?>" target="_blank" class="fw-semibold"><?= $h($minisite) ?></a>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

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

    <!-- URL mini-site -->
    <div class="panel d-flex align-items-center gap-3 flex-wrap mb-4">
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

    <!-- SECTION PROFIL -->
    <div id="section-profil" class="dash-section <?= $sectionActive === 'projets' ? 'd-none' : '' ?>">
        <div class="panel">
            <h5 class="fw-bold font-display mb-4 pb-3 border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-person-circle text-primary"></i> Informations personnelles
            </h5>

            <form method="POST" action="<?= APP_URL ?>/backend/dashboard.php" enctype="multipart/form-data">
                <input type="hidden" name="action" value="profil"/>

                <!-- Photo -->
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

                <!-- Champs -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-medium">Nom complet</label>
                        <input type="text" name="nom_complet" class="form-control"
                               placeholder="Jean Dupont" value="<?= $h($user['nom_complet'] ?? '') ?>"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-medium">Titre professionnel</label>
                        <input type="text" name="titre" class="form-control"
                               placeholder="Développeur Web Full Stack" value="<?= $h($user['titre'] ?? '') ?>"/>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-medium">Biographie</label>
                        <textarea name="bio" class="form-control" rows="3"
                                  placeholder="Parlez de vous..."><?= $h($user['bio'] ?? '') ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-medium">
                            Compétences <span class="fw-normal text-muted">(séparées par des virgules)</span>
                        </label>
                        <input type="text" name="competences" class="form-control"
                               placeholder="PHP, JavaScript, MySQL, HTML, CSS"
                               value="<?= $h($user['competences'] ?? '') ?>"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-medium">Email public</label>
                        <input type="email" name="email_public" class="form-control"
                               placeholder="contact@exemple.com" value="<?= $h($user['email_public'] ?? '') ?>"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-medium">Téléphone</label>
                        <input type="text" name="telephone" class="form-control"
                               placeholder="+226 XX XX XX XX" value="<?= $h($user['telephone'] ?? '') ?>"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-medium"><i class="bi bi-github me-1"></i>GitHub</label>
                        <input type="url" name="github" class="form-control"
                               placeholder="https://github.com/username" value="<?= $h($user['github'] ?? '') ?>"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-medium"><i class="bi bi-linkedin me-1"></i>LinkedIn</label>
                        <input type="url" name="linkedin" class="form-control"
                               placeholder="https://linkedin.com/in/username" value="<?= $h($user['linkedin'] ?? '') ?>"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-medium"><i class="bi bi-globe me-1"></i>Site web</label>
                        <input type="url" name="site_web" class="form-control"
                               placeholder="https://monsite.com" value="<?= $h($user['site_web'] ?? '') ?>"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-medium"><i class="bi bi-geo-alt me-1"></i>Localisation</label>
                        <input type="text" name="localisation" class="form-control"
                               placeholder="Ouagadougou, Burkina Faso" value="<?= $h($user['localisation'] ?? '') ?>"/>
                    </div>
                </div>

                <!-- Thème -->
                <div class="mt-4">
                    <label class="form-label small fw-medium">Thème du mini-site</label>
                    <div class="row g-2">
                        <?php foreach ($themes as $th): ?>
                            <div class="col-6 col-md-3">
                                <label class="theme-label w-100 mb-0">
                                    <input type="radio" name="theme_id"
                                           value="<?= (int)$th['id'] ?>" class="d-none"
                                           <?= (($user['theme_id'] ?? 1) == $th['id']) ? 'checked' : '' ?>/>
                                    <div class="theme-box <?= (($user['theme_id'] ?? 1) == $th['id']) ? 'selected' : '' ?>">
                                        <div class="theme-dot-sm"
                                             style="background:<?= $h($th['couleur'] ?? '#6C63FF') ?>;"></div>
                                        <span class="small fw-medium"><?= $h($th['nom'] ?? '') ?></span>
                                    </div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- BOUTON ENREGISTRER -->
                <div class="mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer les modifications
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- SECTION PROJETS -->
    <div id="section-projets" class="dash-section <?= $sectionActive !== 'projets' ? 'd-none' : '' ?>">
        <div class="panel">
            <h5 class="fw-bold font-display mb-4 pb-3 border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-folder2-open text-primary"></i> Mes projets
            </h5>

            <div class="bg-light rounded-3 p-3 mb-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-plus-circle text-primary me-1"></i> Ajouter un projet
                </h6>
                <form method="POST" action="<?= APP_URL ?>/backend/dashboard.php">
                    <input type="hidden" name="action" value="projet"/>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="projet_titre" class="form-control form-control-sm"
                                   placeholder="Titre du projet *" required/>
                        </div>
                        <div class="col-md-6">
                            <input type="url" name="projet_url" class="form-control form-control-sm"
                                   placeholder="Lien du projet (optionnel)"/>
                        </div>
                        <div class="col-12">
                            <textarea name="projet_desc" class="form-control form-control-sm" rows="2"
                                      placeholder="Description du projet..."></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-lg me-1"></i> Ajouter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <?php if (empty($projets)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-folder-x d-block fs-1 mb-3 opacity-25"></i>
                    <p class="mb-0">Aucun projet pour l'instant.<br/>Ajoutez votre premier projet ci-dessus !</p>
                </div>
            <?php else: ?>
                <?php foreach ($projets as $p): ?>
                    <div class="project-item">
                        <div class="flex-grow-1 me-3 overflow-hidden">
                            <div class="fw-semibold text-truncate"><?= $h($p['titre']) ?></div>
                            <div class="text-muted small text-truncate">
                                <?= $h(mb_substr($p['description'] ?? '', 0, 90)) ?>
                            </div>
                            <?php if ($p['url']): ?>
                                <a href="<?= $h($p['url']) ?>" target="_blank"
                                   class="text-primary small text-decoration-none">
                                    <i class="bi bi-link-45deg"></i> Voir le projet
                                </a>
                            <?php endif; ?>
                        </div>
                        <a href="<?= APP_URL ?>/backend/dashboard.php?suppr=<?= (int)$p['id'] ?>"
                           class="btn btn-outline-danger btn-sm flex-shrink-0"
                           onclick="return confirm('Supprimer ce projet ?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= APP_URL ?>/frontend/js/main.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const section = '<?= $h($sectionActive) ?>';
        if (section === 'projets') {
            document.querySelectorAll('.sidebar-link')[1]?.classList.add('active');
            document.querySelectorAll('.sidebar-link')[0]?.classList.remove('active');
        }
    });
</script>
</body>
</html>
