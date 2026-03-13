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
    <title>Connexion — ProfilSite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="<?= APP_URL ?>/frontend/css/style.css"/>
</head>
<body class="auth-body">

<div class="auth-wrapper">
    <div class="auth-card">

        <!-- Logo -->
        <div class="text-center mb-4">
            <a href="<?= APP_URL ?>/frontend/index.html" class="text-decoration-none">
                <span class="fs-3 fw-bold font-display">
                    Profil<span class="text-primary">Site</span>
                </span>
            </a>
            <p class="text-muted small mt-1 mb-0">Bon retour 👋</p>
        </div>

        <!-- Alerte erreur -->
        <?php if ($erreur): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 py-2 small mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                <?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire -->
        <form method="POST" action="<?= APP_URL ?>/backend/auth/login.php" novalidate>

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

            <div class="mb-4">
                <label class="form-label small fw-medium">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" name="password" id="pwd" class="form-control"
                           placeholder="••••••••" required/>
                    <button class="btn btn-outline-secondary" type="button"
                            onclick="togglePwd('pwd','eyePwd')">
                        <i class="bi bi-eye" id="eyePwd"></i>
                    </button>
                </div>
            </div>
            <div class="text-center mb-3">
    <a href="<?= APP_URL ?>/backend/auth/forgot-password.php" 
       class="text-primary small text-decoration-none">
        Mot de passe oublié ?
    </a>
</div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-medium">
                Se connecter <i class="bi bi-arrow-right ms-1"></i>
            </button>

        </form>

        <p class="text-center text-muted small mt-4 mb-0">
            Pas encore de compte ?
            <a href="<?= APP_URL ?>/frontend/register.php"
               class="text-primary fw-medium text-decoration-none">
                S'inscrire gratuitement
            </a>
        </p>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= APP_URL ?>/frontend/js/main.js"></script>
</body>
</html>