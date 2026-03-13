<?php
//  PROFILSITE — CONFIGURATION GÉNÉRALE
//  Inclure ce fichier en premier dans tous les scripts PHP

// --- Paramètres de connexion à la base de données ---
define('DB_HOST',    'localhost');
define('DB_NAME',    'profilsite');
define('DB_USER',    'root');       // Modifier selon votre config WAMP/XAMPP
define('DB_PASS',    '');           // Mot de passe MySQL (vide par défaut sur WAMP)
define('DB_CHARSET', 'utf8mb4');

// --- URL de base de l'application ---
// Adapter selon votre serveur local ou hébergement
define('APP_URL',    'http://localhost/profilsite');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', APP_URL . '/uploads/');

// --- Démarrage de la session PHP ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//  CONNEXION PDO (singleton — une seule instance)
function getDB(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST, DB_NAME, DB_CHARSET
        );
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Message d'erreur clair pour le développement
            die('
            <div style="font-family:sans-serif;padding:2rem;background:#FEF2F2;
                        border:1px solid #FCA5A5;border-radius:8px;max-width:600px;margin:2rem auto;">
                <h2 style="color:#DC2626;">❌ Erreur de connexion à la base de données</h2>
                <p><strong>Message :</strong> ' . $e->getMessage() . '</p>
                <p><strong>Vérifiez :</strong></p>
                <ul>
                    <li>WAMP/XAMPP est-il démarré ?</li>
                    <li>La base de données <code>profilsite</code> existe-t-elle ?</li>
                    <li>Les identifiants dans <code>config.php</code> sont-ils corrects ?</li>
                </ul>
            </div>');
        }
    }

    return $pdo;
}

//  FONCTIONS UTILITAIRES

/** Vérifie si un utilisateur est connecté */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/** Redirige vers la page de connexion si non authentifié */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: ' . APP_URL . '/frontend/login.php');
        exit;
    }
}

/** Redirige vers le dashboard si déjà connecté */
function redirectIfLoggedIn(): void
{
    if (isLoggedIn()) {
        header('Location: ' . APP_URL . '/backend/dashboard.php');
        exit;
    }
}

/** Échappe les caractères spéciaux HTML (protection XSS) */
function e(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
