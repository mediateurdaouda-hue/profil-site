# ProfilSite — Guide d'installation
Plateforme de mini-sites personnels · PHP · MySQL · Bootstrap 5

---

## Structure du projet

```
profilsite/
├── frontend/
│   ├── css/
│   │   └── style.css          # Styles personnalisés
│   ├── js/
│   │   └── main.js            # JavaScript partagé
│   ├── index.html             # Page d'accueil
│   ├── login.php              # Vue : formulaire connexion
│   ├── register.php           # Vue : formulaire inscription
│   └── dashboard.php          # Vue : tableau de bord
│
├── backend/
│   ├── config.php             # Configuration & connexion BDD
│   ├── dashboard.php          # Logique du dashboard
│   └── auth/
│       ├── register.php       # Traitement inscription
│       ├── login.php          # Traitement connexion
│       └── logout.php         # Déconnexion
│
├── database/
│   └── schema.sql             # Schéma SQL complet
│
├── uploads/                   # Photos de profil uploadées
│   └── .gitkeep
│
└── profil.php                 # Mini-site public (?u=username)
```

---

## Installation sur WAMP (Windows)

### Étape 1 — Copier le projet
Placer le dossier `profilsite` dans :
```
C:\wamp64\www\profilsite\
```

### Étape 2 — Créer la base de données
1. Ouvrir phpMyAdmin : http://localhost/phpmyadmin
2. Cliquer sur **"Importer"** dans la barre du haut
3. Sélectionner le fichier `database/schema.sql`
4. Cliquer sur **"Exécuter"**

### Étape 3 — Configurer la connexion
Ouvrir `backend/config.php` et adapter si nécessaire :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'profilsite');
define('DB_USER', 'root');
define('DB_PASS', '');           // Mot de passe MySQL (vide par défaut sur WAMP)
define('APP_URL', 'http://localhost/profilsite');
```

### Étape 4 — Lancer l'application
Ouvrir dans le navigateur :
```
http://localhost/profilsite/frontend/index.html
```

---

## Compte de démonstration

| Champ    | Valeur            |
|----------|-------------------|
| Email    | jean@exemple.com  |
| Mot de passe | password      |
| Mini-site | /profil.php?u=jean_dupont |

---

## URLs importantes

| Page              | URL                                              |
|-------------------|--------------------------------------------------|
| Accueil           | /frontend/index.html                             |
| Inscription       | /frontend/register.php                           |
| Connexion         | /frontend/login.php                              |
| Dashboard         | /backend/dashboard.php                           |
| Mini-site public  | /profil.php?u=jean_dupont                        |

---

## Technologies utilisées

- **Frontend** : HTML5, Bootstrap 5.3, JavaScript (ES6+), Bootstrap Icons
- **Backend**  : PHP 7.4+, PDO
- **BDD**      : MySQL 5.7+
- **Sécurité** : bcrypt (password_hash), PDO prepared statements, protection XSS
