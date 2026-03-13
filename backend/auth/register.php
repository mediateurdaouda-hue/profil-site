<?php
//  BACKEND — TRAITEMENT DE L'INSCRIPTION

require_once __DIR__ . '/../config.php';
redirectIfLoggedIn(); // Si déjà connecté → dashboard

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupérer et nettoyer les données du formulaire
    $username  = trim($_POST['username']  ?? '');
    $email     = trim($_POST['email']     ?? '');
    $password  = $_POST['password']       ?? '';
    $password2 = $_POST['password2']      ?? '';

    // --- Validation des données ---
    if (empty($username) || empty($email) || empty($password)) {
        $erreur = 'Tous les champs sont obligatoires.';

    } elseif (!preg_match('/^[a-zA-Z0-9_ ]{3,30}$/', $username)) {
        $erreur = 'Nom d\'utilisateur : 3 à 30 caractères (lettres, chiffres, espace, underscore).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'Adresse email invalide.';

    } elseif (strlen($password) < 6) {
        $erreur = 'Le mot de passe doit contenir au moins 6 caractères.';

    } elseif ($password !== $password2) {
        $erreur = 'Les deux mots de passe ne correspondent pas.';

    } else {
        $db = getDB();

        // Vérifier l'unicité du nom d'utilisateur et de l'email
        $stmt = $db->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);

        if ($stmt->fetch()) {
            $erreur = 'Ce nom d\'utilisateur ou cet email est déjà utilisé.';
        } else {
            // Hachage sécurisé du mot de passe (bcrypt)
            $hash = password_hash($password, PASSWORD_BCRYPT);

            // Insertion de l'utilisateur
            $db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)')
               ->execute([$username, $email, $hash]);

            $userId = (int) $db->lastInsertId();

            // Création automatique du profil vide
            $db->prepare('INSERT INTO profiles (user_id) VALUES (?)')
               ->execute([$userId]);

            // Création automatique du mini-site (slug = username)
            $db->prepare('INSERT INTO sites (user_id, url_slug) VALUES (?, ?)')
               ->execute([$userId, $username]);

            // Connexion automatique après inscription
            $_SESSION['user_id']  = $userId;
            $_SESSION['username'] = $username;

            // Redirection vers le dashboard avec message de bienvenue
            header('Location: ' . APP_URL . '/backend/dashboard.php?welcome=1');
            exit;
        }
    }
}

// Afficher la vue du formulaire d'inscription
include __DIR__ . '/../../frontend/register.php';
