<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>404 — Profil introuvable</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=DM+Sans&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            background: #0a0a0a;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            margin: 0;
        }
        h1 { font-family: 'Syne', sans-serif; font-size: 5rem; color: #F5C518; }
        p  { color: #9ca3af; margin-bottom: 2rem; }
        a  { color: #F5C518; text-decoration: none; border: 2px solid #F5C518;
             padding: 0.75rem 2rem; border-radius: 50px; font-weight: 600; }
    </style>
</head>
<body>
    <div>
        <h1>404</h1>
        <p>Ce profil n'existe pas ou a été supprimé.</p>
        <a href="<?= defined('APP_URL') ? APP_URL : '/' ?>/frontend/index.html">
            ← Retour à l'accueil
        </a>
    </div>
</body>
</html>
