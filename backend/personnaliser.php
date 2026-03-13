<?php
/* ============================================================
   backend/personnaliser.php
   Rôle : charger les données layout et afficher la page personnaliser
   ============================================================ */

require_once __DIR__ . '/config.php';
requireLogin();

$db = getDB();

// Récupérer le profil avec layout
$stmt = $db->prepare('SELECT * FROM profiles WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$profil = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer les infos user
$stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Décoder le layout
$layoutRaw = $profil['layout'] ?? null;
$layout    = $layoutRaw ? json_decode($layoutRaw, true) : [];

// Message succès depuis session
$succes = $_SESSION['succes'] ?? '';
unset($_SESSION['succes']);

require_once __DIR__ . '/../frontend/personnaliser.php';
