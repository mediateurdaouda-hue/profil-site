<?php
if (!defined('APP_URL')) {
    require_once __DIR__ . '/../backend/config.php';
}
if (!isset($erreur)) $erreur = '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Inscription — ProfilSite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="<?= APP_URL ?>/frontend/css/style.css"/>
</head>
<body class="auth-body">

<div class="auth-wrapper">
    <div class="auth-card">

        <div class="text-center mb-4">
            <a href="<?= APP_URL ?>/frontend/index.html" class="text-decoration-none">
                <span class="fs-3 fw-bold font-display">
                    Profil<span class="text-primary">Site</span>
                </span>
            </a>
            <p class="text-muted small mt-1 mb-0">Créez votre mini-site en 30 secondes ✨</p>
        </div>

        <!-- Alerte erreur -->
        <?php if ($erreur): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 py-2 small mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                <?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/backend/auth/register.php" novalidate>

            <!-- Nom d'utilisateur -->
            <div class="mb-3">
                <label class="form-label small fw-medium">Nom d'utilisateur</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-at text-muted"></i>
                    </span>
                    <input type="text" name="username" class="form-control"
                           id="usernameInput"
                           placeholder="jean_dupont" required maxlength="30"
                           value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           pattern="[a-zA-Z0-9_]{3,30}"/>
                </div>
                <div class="form-text">
                    Votre URL : profilsite.com/<strong id="urlPreview">votre_nom</strong>
                </div>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label small fw-medium">Adresse email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input type="email" name="email" class="form-control"
                           placeholder="jean@exemple.com" required
                           value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/>
                </div>
            </div>

            <!-- Mot de passe -->
            <div class="mb-3">
                <label class="form-label small fw-medium">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" name="password" id="pwd1" class="form-control"
                           placeholder="Minimum 6 caractères" required/>
                    <button class="btn btn-outline-secondary" type="button"
                            onclick="togglePwd('pwd1','eye1')">
                        <i class="bi bi-eye" id="eye1"></i>
                    </button>
                </div>
            </div>

            <!-- Confirmation -->
            <div class="mb-4">
                <label class="form-label small fw-medium">Confirmer le mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-lock-fill text-muted"></i>
                    </span>
                    <input type="password" name="password2" id="pwd2" class="form-control"
                           placeholder="Répétez votre mot de passe" required/>
                    <button class="btn btn-outline-secondary" type="button"
                            onclick="togglePwd('pwd2','eye2')">
                        <i class="bi bi-eye" id="eye2"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-medium">
                Créer mon compte <i class="bi bi-arrow-right ms-1"></i>
            </button>

        </form>

        <p class="text-center text-muted small mt-4 mb-0">
            Déjà un compte ?
            <a href="<?= APP_URL ?>/frontend/login.php"
               class="text-primary fw-medium text-decoration-none">
                Se connecter
            </a>
        </p>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= APP_URL ?>/frontend/js/main.js"></script>
<script>
    document.getElementById('usernameInput').addEventListener('input', function () {
        document.getElementById('urlPreview').textContent = this.value || 'votre_nom';
    });
</script>
</body>
</html>