<?php
// Chargement de l'autoloader de Composer
require_once __DIR__ . '/vendor/autoload.php';

// Démarrage de la session
session_start();

// Initialisation du moteur de templates Twig pour gérer les vues.
$loader = new \Twig\Loader\FilesystemLoader('vues'); // Définit le dossier des templates
$twig = new \Twig\Environment($loader, [
    'debug' => true // Active le mode debug (utile pour voir les erreurs dans les templates)
]);

// Vérifie si une URI est présente dans l'URL (paramètre GET 'uri')
if (isset($_GET['uri'])) {
    $uri = $_GET['uri']; // Récupère la valeur de l'URI
} else {
    $uri = '/'; // Valeur par défaut : page d'accueil
}

// Récupérer le contrôleur et exécuter l'action appropriée
switch ($uri) {
    case '/':
        // Afficher la page d'accueil
        echo $twig->render('Home.twig.html', [
            'user_name' => 'Mazou Marine',  // Exemple de nom d'utilisateur
            'user_role' => 'Admin'      // Exemple de rôle de l'utilisateur
        ]);
        break;

    case '/login':
        // Afficher la page de connexion
        echo $twig->render('Login.twig.html');
        break;

    // Ajouter d'autres cas ici si nécessaire, par exemple pour les autres pages
    default:
        echo 'Page non trouvée';
        break;
}
