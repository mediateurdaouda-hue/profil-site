<?php
/* ============================================================
   backend/auth/reset-password.php
   Traite la réinitialisation du mot de passe
   ============================================================ */

require_once __DIR__ . '/../config.php';

$token  = trim($_GET['token'] ?? $_POST['token'] ?? '');
$erreur = '';
$succes = '';

// Vérifier que le token existe et est valide
if (empty($token)) {
    $erreur = 'Lien invalide ou expiré.';
    require_once __DIR__ . '/../../frontend/reset-password.php';
    exit;
}

$db = getDB();

$stmt = $db->prepare('
    SELECT * FROM password_resets
    WHERE token = ?
      AND used = 0
      AND expires_at > NOW()
');
$stmt->execute([$token]);
$reset = $stmt->fetch();

if (!$reset) {
    $erreur = 'Ce lien est invalide ou a expiré. Veuillez faire une nouvelle demande.';
    require_once __DIR__ . '/../../frontend/reset-password.php';
    exit;
}

// Si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password        = $_POST['password']         ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if (strlen($password) < 8) {
        $erreur = 'Le mot de passe doit contenir au moins 8 caractères.';
    } elseif ($password !== $passwordConfirm) {
        $erreur = 'Les deux mots de passe ne correspondent pas.';
    } else {
        // Mettre à jour le mot de passe
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $db->prepare('UPDATE users SET password = ? WHERE email = ?')
           ->execute([$hash, $reset['email']]);

        // Marquer le token comme utilisé
        $db->prepare('UPDATE password_resets SET used = 1 WHERE token = ?')
           ->execute([$token]);

        $succes = '✅ Votre mot de passe a été réinitialisé avec succès !';
    }
}

require_once __DIR__ . '/../../frontend/reset-password.php';
