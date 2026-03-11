-- ============================================================
--  PROFILSITE — SCHÉMA COMPLET DE LA BASE DE DONNÉES
--  Compatible MySQL 5.7+
--  Importer via : phpMyAdmin > Importer > schema.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS profilsite
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE profilsite;

-- ------------------------------------------------------------
-- Table : themes
-- Stocke les 4 thèmes visuels disponibles pour les mini-sites
-- ------------------------------------------------------------
CREATE TABLE themes (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    nom     VARCHAR(50) NOT NULL,
    classe  VARCHAR(50) NOT NULL,   -- classe CSS appliquée au mini-site
    couleur VARCHAR(7)  NOT NULL DEFAULT '#6C63FF'
);

INSERT INTO themes (nom, classe, couleur) VALUES
    ('Sombre',   'theme-dark',     '#6C63FF'),
    ('Clair',    'theme-light',    '#4F46E5'),
    ('Dégradé',  'theme-gradient', '#764ba2'),
    ('Minimal',  'theme-minimal',  '#374151');

-- ------------------------------------------------------------
-- Table : users
-- Compte utilisateur (authentification)
-- ------------------------------------------------------------
CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,  -- sert d'URL slug
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,         -- haché avec bcrypt
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Table : profiles
-- Informations publiques affichées sur le mini-site
-- ------------------------------------------------------------
CREATE TABLE profiles (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT          NOT NULL UNIQUE,
    nom_complet  VARCHAR(100) DEFAULT '',
    titre        VARCHAR(100) DEFAULT '',    -- ex : "Développeur Web"
    bio          TEXT,
    competences  TEXT,                       -- ex : "PHP,JS,MySQL"
    email_public VARCHAR(100) DEFAULT '',
    telephone    VARCHAR(20)  DEFAULT '',
    github       VARCHAR(150) DEFAULT '',
    linkedin     VARCHAR(150) DEFAULT '',
    site_web     VARCHAR(150) DEFAULT '',
    localisation VARCHAR(100) DEFAULT '',
    photo        VARCHAR(255) DEFAULT '',    -- nom du fichier uploadé
    theme_id     INT          DEFAULT 1,
    FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    FOREIGN KEY (theme_id) REFERENCES themes(id) ON DELETE SET NULL
);

-- ------------------------------------------------------------
-- Table : projects
-- Projets ajoutés par l'utilisateur (portfolio)
-- ------------------------------------------------------------
CREATE TABLE projects (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT          NOT NULL,
    titre       VARCHAR(100) NOT NULL,
    description TEXT,
    url         VARCHAR(255) DEFAULT '',
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- Table : sites
-- Mini-site généré automatiquement à l'inscription
-- ------------------------------------------------------------
CREATE TABLE sites (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT         NOT NULL UNIQUE,
    url_slug   VARCHAR(50) NOT NULL UNIQUE,  -- = username
    actif      TINYINT(1)  DEFAULT 1,
    vues       INT         DEFAULT 0,        -- compteur de visites
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- Données de démonstration
-- Compte test : jean@exemple.com / password
-- ------------------------------------------------------------
INSERT INTO users (username, email, password) VALUES
    ('jean_dupont', 'jean@exemple.com',
     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO profiles (user_id, nom_complet, titre, bio, competences,
                      email_public, github, localisation, theme_id)
VALUES (
    1, 'Daouda Sawadogo', 'Développeur Web Full Stack',
    'Passionné par le développement web et les nouvelles technologies. J''aime créer des expériences utilisateur soignées et des backends robustes.',
    'PHP,JavaScript,MySQL,HTML,CSS,Bootstrap',
    'mediateurdaouda@gmail.com', 'https://github.com/jean',
    'Ouagadougou, Burkina Faso', 1
);

INSERT INTO projects (user_id, titre, description, url) VALUES
    (1, 'Portfolio Personnel',
        'Site portfolio réalisé avec HTML, CSS et JavaScript vanilla.',
        'https://exemple.com'),
    (1, 'App de Gestion de Tâches',
        'Application CRUD en PHP/MySQL avec interface Bootstrap.',
        ''),
    (1, 'API REST PHP',
        'API RESTful avec authentification JWT et documentation Swagger.',
        '');

INSERT INTO sites (user_id, url_slug) VALUES (1, 'jean_dupont');
