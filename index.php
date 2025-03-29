<?php
// Chargement de l'autoloader de Composer
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ .'/src/Controllers/Offer.php';
require_once __DIR__ .'/src/Controllers/Company.php';

// Démarrage de la session
session_start();

// Initialisation du moteur de templates Twig
$loader = new \Twig\Loader\FilesystemLoader('vues'); // Définit le dossier des templates
$twig = new \Twig\Environment($loader, [
    'debug' => true, // Active le mode debug
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());


// Vérifie si une URI est présente dans l'URL (paramètre GET 'uri')
$uri = $_GET['uri'] ?? '/'; // Valeur par défaut : page d'accueil

// Récupérer le contrôleur et exécuter l'action appropriée
switch ($uri) {
    case '/':
        // Afficher la page d'accueil
        echo $twig->render('Home.twig.html');
        
        break;

    default:
        echo 'Page non trouvée';
        break;
}
?>