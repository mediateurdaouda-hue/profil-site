<?php
/* ============================================================
   backend/profil.php
   Rôle : récupérer les données du profil depuis la BDD
          puis inclure la vue frontend
   URL  : /profil.php?u=username  (via le point d'entrée)
   ============================================================ */

require_once __DIR__ . '/config.php';

/* ---------- 1. Paramètre URL ---------- */
$username = trim($_GET['u'] ?? '');

if (empty($username)) {
    header('Location: ' . APP_URL . '/frontend/index.html');
    exit;
}

/* ---------- 2. Connexion BDD ---------- */
$db = getDB();

/* ---------- 3. Récupération du profil ---------- */
$stmt = $db->prepare('
    SELECT  u.username,
            p.nom_complet,
            p.titre,
            p.bio,
            p.competences,
            p.email_public,
            p.telephone,
            p.github,
            p.linkedin,
            p.site_web,
            p.localisation,
            p.photo,
            p.theme_id
    FROM    users    u
    LEFT JOIN profiles p ON p.user_id = u.id
    WHERE   u.username = ?
');
$stmt->execute([$username]);
$profil = $stmt->fetch(PDO::FETCH_ASSOC);

/* ---------- 4. Profil introuvable ---------- */
if (!$profil) {
    http_response_code(404);
    require_once __DIR__ . '/../frontend/404.php';
    exit;
}

/* ---------- 5. Compteur de vues ---------- */
try {
    $db->prepare('UPDATE sites SET vues = vues + 1 WHERE url_slug = ?')
       ->execute([$username]);
} catch (PDOException $e) {
    // Colonne manquante — on ignore silencieusement
}

/* ---------- 6. Projets ---------- */
$stmt = $db->prepare('
    SELECT  *
    FROM    projects
    WHERE   user_id = (SELECT id FROM users WHERE username = ?)
    ORDER   BY created_at DESC
');
$stmt->execute([$username]);
$projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ---------- 7. Préparation des variables pour la vue ---------- */
$nom         = $profil['nom_complet'] ?: $username;
$titre       = $profil['titre']       ?: 'Développeur Web';
$bio         = $profil['bio']         ?: '';
$photoUrl    = $profil['photo']
                ? APP_URL . '/uploads/' . $profil['photo']
                : '';
$initiale    = strtoupper(substr($nom, 0, 1));
$themeClass  = 'theme-' . ($profil['theme_id'] ?? 1);
$ville       = $profil['localisation']
                ? trim(explode(',', $profil['localisation'])[0])
                : '';
$competences = [];
if (!empty($profil['competences'])) {
    $competences = array_filter(
        array_map('trim', explode(',', $profil['competences']))
    );
}

/* ---------- 8. Chargement de la vue ---------- */
require_once __DIR__ . '/../frontend/profil.view.php';