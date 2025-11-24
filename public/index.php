<?php

session_start();

require __DIR__ . '/../vendor/autoload.php'; // Chargement de l'autoloader généré par Composer

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

// Importation des classes avec namespaces pour éviter les conflits de noms
use Core\Router;

// Initialisation du routeur
$router = new Router();

// Définition des routes de l'application
// La route "/" pointe vers la méthode "index" du contrôleur HomeController
$router->get('/', 'App\\Controllers\\HomeController@index');

$router->get('/upload', 'App\\Controllers\\HomeController@upload');
$router->post('/upload', 'App\\Controllers\\HomeController@upload');
$router->post('/set-difficulty', 'App\\Controllers\\HomeController@setDifficulty');
$router->post('/', 'App\\Controllers\\HomeController@resetSession');
// La route "/articles" pointe vers la méthode "index" du contrôleur ArticleController
// $router->get(path: '/articles', 'App\\Controllers\\ArticleController@index');

// Exécution du routeur :
// On analyse l'URI et la méthode HTTP pour appeler le contrôleur et la méthode correspondants
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
