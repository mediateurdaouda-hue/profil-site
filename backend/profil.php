<?php
require_once __DIR__ . '/config.php';

$username = trim($_GET['u'] ?? '');
if (empty($username)) {
    header('Location: ' . APP_URL . '/frontend/index.html');
    exit;
}

$db = getDB();

$stmt = $db->prepare('
    SELECT  u.username,
            p.nom_complet, p.titre, p.bio, p.competences,
            p.email_public, p.telephone, p.github, p.linkedin,
            p.site_web, p.localisation, p.photo, p.theme_id, p.layout
    FROM    users u
    LEFT JOIN profiles p ON p.user_id = u.id
    WHERE   u.username = ?
');
$stmt->execute([$username]);
$profil = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profil) {
    http_response_code(404);
    require_once __DIR__ . '/../frontend/404.php';
    exit;
}

try {
    $db->prepare('UPDATE sites SET vues = vues + 1 WHERE url_slug = ?')->execute([$username]);
} catch (PDOException $e) {}

$stmt = $db->prepare('SELECT * FROM projects WHERE user_id = (SELECT id FROM users WHERE username = ?) ORDER BY created_at DESC');
$stmt->execute([$username]);
$projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

$nom         = $profil['nom_complet'] ?: $username;
$titre       = $profil['titre']       ?: 'Développeur Web';
$bio         = $profil['bio']         ?: '';
$photoUrl    = $profil['photo'] ? APP_URL . '/uploads/' . $profil['photo'] : '';
$initiale    = strtoupper(substr($nom, 0, 1));
$themeClass  = 'theme-' . ($profil['theme_id'] ?? 1);
$ville       = $profil['localisation'] ? trim(explode(',', $profil['localisation'])[0]) : '';
$competences = [];
if (!empty($profil['competences'])) {
    $competences = array_filter(array_map('trim', explode(',', $profil['competences'])));
}

// Layout
$layoutRaw     = $profil['layout'] ?? null;
$layout        = $layoutRaw ? json_decode($layoutRaw, true) : [];
$photoPosition = $layout['photo_position'] ?? 'right';
$sectionsOrder = $layout['sections_order']  ?? ['nom_titre','bio','competences','projets','contact','reseaux'];

require_once __DIR__ . '/../frontend/profil.view.php';
