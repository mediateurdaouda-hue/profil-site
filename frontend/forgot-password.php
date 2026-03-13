<?php
if (!defined('APP_URL')) {
    require_once __DIR__ . '/../backend/config.php';
}
$erreur = $erreur ?? '';
$succes = $succes ?? '';
$h = fn($s) => htmlspecialchars((string)($s ?? ''), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Mot de passe oublié — ProfilSite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="<?= APP_URL ?>/frontend/css/style.css"/>
</head>
<body class="auth-body">

<div class="auth-card">

    <!-- Logo -->
    <div class="text-center mb-4">
        <a href="<?= APP_URL ?>/frontend/index.html" class="text-decoration-none">
            <span class="fw-bold font-display fs-4">
                Profil<span class="text-primary">Site</span>
            </span>
        </a>
        <p class="text-muted mt-2 mb-0">Réinitialisez votre mot de passe 🔐</p>
    </div>

    <?php if ($succes): ?>
        <div class="alert alert-success d-flex align-items-center gap-2 py-2 small">
            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
            <?= $h($succes) ?>
        </div>
    <?php endif; ?>

    <?php if ($erreur): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 py-2 small">
            <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
            <?= $h($erreur) ?>
        </div>
    <?php endif; ?>

    <?php if (!$succes): ?>
    <form method="POST" action="<?= APP_URL ?>/backend/auth/forgot-password.php">

        <div class="mb-3">
            <label class="form-label small fw-semibold text-primary">Adresse email</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-envelope text-muted"></i>
                </span>
                <input type="email" name="email" class="form-control border-start-0 ps-0"
                       placeholder="votre@email.com" required
                       value="<?= $h($_POST['email'] ?? '') ?>"/>
            </div>
            <div class="form-text">
                Entrez l'adresse email associée à votre compte.
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
            Envoyer le lien de réinitialisation <i class="bi bi-arrow-right ms-1"></i>
        </button>

    </form>
    <?php endif; ?>

    <div class="text-center mt-4 small">
        <a href="<?= APP_URL ?>/frontend/login.php" class="text-primary text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i> Retour à la connexion
        </a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
