<?php
// Inclusion des classes Request, Response et Router
require_once __DIR__ . '/../src/Http/Request.php';
require_once __DIR__ . '/../src/Http/Response.php';
require_once __DIR__ . '/../src/Routing/Router.php';

// Inclusion des contrôleurs
require_once __DIR__ . '/../src/controllers/userController.php';
require_once __DIR__ . '/../src/controllers/classeController.php';
require_once __DIR__ . '/../src/controllers/eleveController.php';
require_once __DIR__ . '/../src/controllers/professeurController.php';
require_once __DIR__ . '/../src/controllers/sanctionController.php';

// Création des objets Request, Response et Router
$rep = new Response();
$router = new Router();

// Définition de toutes les routes de l'application
$router->addRoute('index', 'indexSanction', ['GET'])
    ->addRoute('inscription', 'inscriptionSanction', ['GET', 'POST'])
    ->addRoute('connexion', 'connexionSanction', ['GET', 'POST'])
    ->addRoute('dashboard', 'dashboardSanction', ['GET'])
    ->addRoute('logout', 'logoutSanction', ['GET'])
    ->addRoute('creationClasse', 'creationClasseSanction', ['GET', 'POST'])
    ->addRoute('classes', 'listeClassesSanction', ['GET'])
    ->addRoute('creationEleve', 'creationEleveSanction', ['GET', 'POST'])
    ->addRoute('eleves', 'listeElevesSanction', ['GET'])
    ->addRoute('creationProfesseur', 'creationProfesseurSanction', ['GET', 'POST'])
    ->addRoute('professeurs', 'listeProfesseursSanction', ['GET'])
    ->addRoute('creationSanction', 'creationSanctionSanction', ['GET', 'POST'])
    ->addRoute('sanctions', 'listeSanctionsSanction', ['GET']);

// Ajout de routes personnalisées supplémentaires
// $router->addRoute('about', 'aboutPage', ['GET']);
// Traitement de la requête courante
$router->handleRequest();

$rep->send();
