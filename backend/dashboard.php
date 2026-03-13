<?php
// ============================================================
//  BACKEND — TABLEAU DE BORD UTILISATEUR
// ============================================================

require_once __DIR__ . '/config.php';
requireLogin(); // Protège la page : redirige si non connecté

$db     = getDB();
$userId = (int) $_SESSION['user_id'];
$succes = '';
$erreur = '';

// --- Charger les données de l'utilisateur connecté ---
function chargerUtilisateur(PDO $db, int $userId): array
{
    $stmt = $db->prepare('
        SELECT u.username, u.email, p.*
        FROM   users    u
        LEFT JOIN profiles p ON p.user_id = u.id
        WHERE  u.id = ?
    ');
    $stmt->execute([$userId]);
    return $stmt->fetch() ?: [];
}

$user   = chargerUtilisateur($db, $userId);
$themes = $db->query('SELECT * FROM themes ORDER BY id')->fetchAll();

// --- Charger les projets ---
$stmt = $db->prepare('SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$userId]);
$projets = $stmt->fetchAll();

// ============================================================
//  TRAITEMENT DES ACTIONS POST
// ============================================================

$action = $_POST['action'] ?? '';

if ($action === 'layout') {
    error_log("USER_ID SESSION: " . ($_SESSION['user_id'] ?? 'VIDE'));
    error_log("USER_ID VAR: " . $userId);
}


// ------ Mise à jour du profil ------
if ($action === 'profil') {

    $champs = ['nom_complet','titre','bio','competences',
               'email_public','telephone','github','linkedin','site_web','localisation'];

    $vals = [];
    foreach ($champs as $c) {
        $vals[$c] = trim($_POST[$c] ?? '');
    }
    $vals['theme_id'] = (int)($_POST['theme_id'] ?? 1);

    // Gestion de l'upload de photo
    $vals['photo'] = $user['photo'] ?? '';

    if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $ext     = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            $erreur = 'Format de photo non autorisé (JPG, PNG, GIF, WEBP).';
        } elseif ($_FILES['photo']['size'] > 2_000_000) {
            $erreur = 'La photo ne doit pas dépasser 2 Mo.';
        } else {
            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0755, true);
            }
            $filename = 'user_' . $userId . '_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], UPLOAD_DIR . $filename)) {
                $vals['photo'] = $filename;
            }
        }
    }

    if (!$erreur) {
        $sql = '
            UPDATE profiles
            SET nom_complet=?, titre=?, bio=?, competences=?,
                email_public=?, telephone=?, github=?, linkedin=?,
                site_web=?, localisation=?, photo=?, theme_id=?
            WHERE user_id=?
        ';
        $db->prepare($sql)->execute([
            $vals['nom_complet'], $vals['titre'],    $vals['bio'],
            $vals['competences'], $vals['email_public'], $vals['telephone'],
            $vals['github'],      $vals['linkedin'],  $vals['site_web'],
            $vals['localisation'],$vals['photo'],    $vals['theme_id'],
            $userId
        ]);
        $succes = '✅ Profil mis à jour avec succès !';
        $user   = chargerUtilisateur($db, $userId); // Rafraîchir
    }
}

// ------ Ajout d'un projet ------
if ($action === 'projet') {
    $titre = trim($_POST['projet_titre'] ?? '');
    $desc  = trim($_POST['projet_desc']  ?? '');
    $url   = trim($_POST['projet_url']   ?? '');

    if (empty($titre)) {
        $erreur = 'Le titre du projet est obligatoire.';
    } else {
        $db->prepare('INSERT INTO projects (user_id, titre, description, url) VALUES (?,?,?,?)')
           ->execute([$userId, $titre, $desc, $url]);
        $succes = '✅ Projet ajouté !';

        // Rafraîchir la liste
        $stmt->execute([$userId]);
        $projets = $stmt->fetchAll();
    }
}

// ------ Layout disposition ------
if ($action === 'layout') {
    $photoPosition  = in_array($_POST['photo_position'] ?? '', ['left','right'])
                      ? $_POST['photo_position'] : 'right';
    $sectionsRaw    = $_POST['sections_order'] ?? 'about,skills,projects,contact';
    $allowed = ['nom_titre','bio','competences','projets','contact','reseaux'];
    $sectionsOrder  = array_filter(
        array_map('trim', explode(',', $sectionsRaw)),
        fn($s) => in_array($s, $allowed)
    );
    // Ajouter les manquantes
    foreach ($allowed as $s) {
        if (!in_array($s, $sectionsOrder)) $sectionsOrder[] = $s;
    }
    $layout = json_encode([
        'photo_position'  => $photoPosition,
        'sections_order'  => array_values($sectionsOrder),
    ]);
    $db = getDB();
    $stmt = $db->prepare('UPDATE profiles SET layout = ? WHERE user_id = ?');
    $stmt->execute([$layout, $userId]);

    $_SESSION['succes'] = 'Disposition enregistrée !';
    if (($_POST['redirect'] ?? '') === 'personnaliser') {
        header('Location: ' . APP_URL . '/frontend/personnaliser.php');
    } else {
        header('Location: ' . APP_URL . '/backend/dashboard.php?section=disposition');
    }
    exit;
}

// ------ Choix de l'extension de domaine ------
if ($action === 'extension') {
    $extensionsValides = ['.com', '.bf', '.net', '.org'];
    $ext = $_POST['extension'] ?? '.com';
    if (in_array($ext, $extensionsValides)) {
        $_SESSION['extension'] = $ext;
    }
    header('Location: ' . APP_URL . '/backend/dashboard.php');
    exit;
}

// ------ Suppression d'un projet ------
if (isset($_GET['suppr']) && is_numeric($_GET['suppr'])) {
    $db->prepare('DELETE FROM projects WHERE id = ? AND user_id = ?')
       ->execute([(int)$_GET['suppr'], $userId]);
    header('Location: ' . APP_URL . '/backend/dashboard.php?section=projets');
    exit;
}

// Paramètre section active (via URL après suppression)
$sectionActive = $_GET['section'] ?? 'profil';
$bienvenue     = isset($_GET['welcome']);

// Inclure la vue du dashboard
include __DIR__ . '/../frontend/dashboard.php';

