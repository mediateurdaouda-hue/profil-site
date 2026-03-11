<?php
// ============================================================
//  BACKEND — TRAITEMENT DE LA CONNEXION
// ============================================================

require_once __DIR__ . '/../../backend/config.php';
redirectIfLoggedIn();

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']      ?? '';

    if (empty($email) || empty($password)) {
        $erreur = 'Veuillez remplir tous les champs.';
    } else {
        $db   = getDB();
        $stmt = $db->prepare('SELECT id, username, password FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Vérification du mot de passe avec password_verify (sécurisé contre timing attacks)
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];

            header('Location: ' . APP_URL . '/backend/dashboard.php');
            exit;
        } else {
            $erreur = 'Email ou mot de passe incorrect.';
        }
    }
}

include __DIR__ . '/../../frontend/login.php';
