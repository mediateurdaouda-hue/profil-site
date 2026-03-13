<?php
if (!defined('APP_URL')) {
    require_once __DIR__ . '/../backend/config.php';
}
$erreur = $erreur ?? '';
$succes = $succes ?? '';
$token  = $token  ?? ($_GET['token'] ?? '');
$h = fn($s) => htmlspecialchars((string)($s ?? ''), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Nouveau mot de passe — ProfilSite</title>
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
        <p class="text-muted mt-2 mb-0">Choisissez un nouveau mot de passe 🔑</p>
    </div>

    <?php if ($succes): ?>
        <div class="alert alert-success d-flex align-items-center gap-2 py-2 small">
            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
            <?= $h($succes) ?>
        </div>
        <div class="text-center mt-3">
            <a href="<?= APP_URL ?>/frontend/login.php" class="btn btn-primary px-4">
                <i class="bi bi-box-arrow-in-right me-1"></i> Se connecter
            </a>
        </div>
    <?php elseif ($erreur): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 py-2 small">
            <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
            <?= $h($erreur) ?>
        </div>
        <div class="text-center mt-3">
            <a href="<?= APP_URL ?>/frontend/forgot-password.php" class="text-primary small">
                ← Demander un nouveau lien
            </a>
        </div>
    <?php else: ?>
    <form method="POST" action="<?= APP_URL ?>/backend/auth/reset-password.php">
        <input type="hidden" name="token" value="<?= $h($token) ?>"/>

        <div class="mb-3">
            <label class="form-label small fw-semibold text-primary">Nouveau mot de passe</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-lock text-muted"></i>
                </span>
                <input type="password" name="password" id="password"
                       class="form-control border-start-0 ps-0"
                       placeholder="••••••••" required minlength="8"/>
                <button type="button" class="btn btn-outline-secondary border-start-0"
                        onclick="togglePwd('password', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <div class="form-text">Minimum 8 caractères.</div>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-semibold text-primary">Confirmer le mot de passe</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-lock-fill text-muted"></i>
                </span>
                <input type="password" name="password_confirm" id="password_confirm"
                       class="form-control border-start-0 ps-0"
                       placeholder="••••••••" required minlength="8"/>
                <button type="button" class="btn btn-outline-secondary border-start-0"
                        onclick="togglePwd('password_confirm', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
            Réinitialiser le mot de passe <i class="bi bi-check-lg ms-1"></i>
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
<script>
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
</body>
</html>
