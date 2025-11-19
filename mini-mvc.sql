-- Script SQL de base pour le mini-projet MVC

CREATE DATABASE IF NOT EXISTS mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mvc;

CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    body TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Jeu de données de test : Table article
-- Thème : Développement Web - PHP, HTML, CSS

INSERT INTO articles (title, body) VALUES
-- 1. Article d’introduction générale au développement web
('Introduction au développement web',
 'Le développement web regroupe l’ensemble des techniques permettant de créer et maintenir des sites ou applications web. 
  Il repose sur plusieurs langages comme HTML pour la structure, CSS pour la mise en forme, et JavaScript ou PHP pour la logique applicative. 
  Le parcours DWWM (Développeur Web et Web Mobile) forme aux bases de ces technologies et à la gestion de projets web.'),

-- 2. Article sur le HTML
('Les bases du HTML5',
 'HTML5 est le langage de balisage utilisé pour structurer le contenu des pages web. 
  Il introduit de nouvelles balises sémantiques comme <header>, <section>, <article> et <footer>, 
  améliorant ainsi l’accessibilité et le référencement naturel. 
  L apprentissage du HTML est la première étape pour tout futur développeur web.'),

-- 3. Article sur le CSS
('Mise en forme avec CSS3 : principes de base',
 'CSS3 permet de définir la présentation visuelle des éléments HTML. 
  Grâce aux sélecteurs, aux propriétés et aux unités flexibles (em, rem, %), 
  il est possible de créer des designs responsives adaptés à tous les écrans. 
  Les nouvelles fonctionnalités comme les animations, les transitions et les variables CSS facilitent la création d interfaces modernes.'),

-- 4. Article sur PHP
('Introduction à PHP pour le développement backend',
 'PHP est un langage de script côté serveur utilisé pour générer des pages dynamiques. 
  Il s’intègre facilement avec HTML et permet de gérer des formulaires, des sessions et des bases de données. 
  PHP reste un pilier du web, notamment grâce à des frameworks comme Laravel et Symfony.'),

-- 5. Article sur la connexion PHP/MySQL
('Connexion d un site web à une base de données MySQL avec PHP',
 'Pour stocker et gérer des données dynamiques, un site web doit se connecter à une base de données. 
  En PHP, cela se fait via l’extension PDO ou MySQLi. 
  L’utilisation de requêtes préparées est recommandée pour éviter les failles de type injection SQL. 
  Exemple :
  $pdo = new PDO("mysql:host=localhost;dbname=test", "root", "");
  $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->execute([1]);'),

-- 6. Article sur le responsive design
('Créer un site responsive avec CSS Flexbox et Grid',
 'Le responsive design consiste à adapter l affichage d un site à tous les formats d’écran (mobile, tablette, PC). 
  Les modules CSS Flexbox et Grid permettent de positionner facilement les éléments sans recourir aux float. 
  Flexbox est idéal pour les alignements linéaires, tandis que Grid facilite la mise en page complexe en deux dimensions.'),

-- 7. Article sur les bonnes pratiques
('Bonnes pratiques en développement web',
 'Le développement web professionnel nécessite de respecter certaines bonnes pratiques : 
  - Séparer le contenu (HTML), la présentation (CSS) et la logique (PHP/JS)
  - Utiliser des noms de classes explicites et un code indenté
  - Tester sur plusieurs navigateurs
  - Documenter son code et versionner ses projets avec Git.'),

-- 8. Article sur l accessibilité
('Accessibilité web : rendre son site utilisable par tous',
 'L accessibilité web vise à rendre les sites utilisables par les personnes en situation de handicap. 
  Les bonnes pratiques incluent l’utilisation correcte des balises HTML sémantiques, 
  l’ajout de textes alternatifs pour les images, et la gestion du contraste des couleurs. 
  Respecter les normes WCAG améliore l’expérience utilisateur pour tous.'),

-- 9. Article sur la validation et le débogage
('Valider et déboguer son code HTML/CSS',
 'Avant la mise en ligne d’un site, il est essentiel de vérifier la validité du code. 
  Les outils comme le validateur W3C ou les DevTools des navigateurs aident à corriger les erreurs. 
  Un code propre facilite la maintenance et améliore la performance du site.'),

-- 10. Article de conclusion / synthèse
('Devenir développeur web : le parcours DWWM',
 'La formation DWWM offre une base solide pour entrer dans le monde du développement web. 
  Elle aborde les langages essentiels (HTML, CSS, PHP, JavaScript) et les notions de gestion de projet, d ergonomie et de responsive design. 
  La clé de la réussite : pratiquer régulièrement et rester curieux des nouvelles technologies.');