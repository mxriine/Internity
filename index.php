<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/vues');
$twig = new \Twig\Environment($loader, [
    'debug' => true,
    'cache' => false
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$uri = strtok($uri, '?'); // Supprime les paramètres GET

switch ($uri) {
    case '/':
        echo $twig->render('Home.twig.html', [
            'role' => $_SESSION['role'] ?? 'inconnu',
            'name' => $_SESSION['name'] ?? '',
            'current_page' => '/',
            'is_logged' => isset($_SESSION['role']),
            
        ]);
        
        break;

    default:
        http_response_code(404);
        echo 'Page non trouvée';
        break;
}