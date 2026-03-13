<?php
/* ============================================================
   backend/auth/forgot-password.php
   Traite la demande de réinitialisation de mot de passe
   ============================================================ */

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . APP_URL . '/frontend/forgot-password.php');
    exit;
}

$email  = trim($_POST['email'] ?? '');
$erreur = '';
$succes = '';

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erreur = 'Veuillez entrer une adresse email valide.';
} else {
    $db = getDB();

    // Vérifier si l'email existe
    $stmt = $db->prepare('SELECT id, username FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Supprimer les anciens tokens de cet email
        $db->prepare('DELETE FROM password_resets WHERE email = ?')->execute([$email]);

        // Générer un token unique
        $token     = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Enregistrer le token en BDD
        $db->prepare('INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)')
           ->execute([$email, $token, $expiresAt]);

        // Lien de réinitialisation
        $resetLink = APP_URL . '/frontend/reset-password.php?token=' . $token;

        // Tentative d'envoi d'email
        $sujet  = 'ProfilSite — Réinitialisation de votre mot de passe';
        $corps  = "Bonjour {$user['username']},\n\n";
        $corps .= "Vous avez demandé la réinitialisation de votre mot de passe.\n\n";
        $corps .= "Cliquez sur ce lien (valable 1 heure) :\n";
        $corps .= $resetLink . "\n\n";
        $corps .= "Si vous n'avez pas fait cette demande, ignorez cet email.\n\n";
        $corps .= "— L'équipe ProfilSite";

        $headers = "From: noreply@profilsite.com\r\nContent-Type: text/plain; charset=UTF-8";
        $emailEnvoye = @mail($email, $sujet, $corps, $headers);

        // Message de succès (avec lien visible en local si email non envoyé)
        if ($emailEnvoye) {
            $succes = "Un lien de réinitialisation a été envoyé à <strong>{$email}</strong>. Vérifiez votre boîte mail (valable 1 heure).";
        } else {
            // En local WAMP, mail() ne fonctionne pas — on affiche le lien directement
            $succes = "Lien de réinitialisation (valable 1 heure) :<br/>
                       <a href='{$resetLink}' class='fw-semibold'>{$resetLink}</a>
                       <br/><small class='text-muted'>Note : en production, ce lien sera envoyé par email.</small>";
        }
    } else {
        // On ne révèle pas si l'email existe ou non (sécurité)
        $succes = "Si cet email est associé à un compte, vous recevrez un lien de réinitialisation.";
    }
}

// Charger la vue
require_once __DIR__ . '/../../frontend/forgot-password.php';
