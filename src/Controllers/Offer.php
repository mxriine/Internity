<?php
// Chargement des dépendances nécessaires
require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Controllers/Login.php');
require_once(__DIR__ . '/../Models/Offer.php');

use Models\Offer;

// Instanciation du modèle Offer
$offerModel = new Offer($conn);

$current_page = basename($_SERVER['PHP_SELF']);

// SECTION 1 : Pagination des offres
// Nombre d'offres à afficher par page
$elements_par_page = 9;

// Récupérer le numéro de la page actuelle depuis l'URL
$page_actuelle = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page_actuelle < 1) {
    $page_actuelle = 1; // Assurer que la page actuelle est au minimum 1
}

// Calculer l'offset pour la pagination
$offset = ($page_actuelle - 1) * $elements_par_page;

// Récupérer les offres paginées depuis la base de données
$offers = $offerModel->getPaginatedOffers($elements_par_page, $offset);

// Compter le nombre total d'offres disponibles
$total_offers = $offerModel->getTotalOffersCount();

// Calculer le nombre total de pages nécessaires
$total_pages = ceil($total_offers / $elements_par_page);

// SECTION 2 : Gestion des détails d'une offre spécifique
// Vérifier si l'utilisateur est sur la page Offer.php ou Apply.php et si un ID d'offre est spécifié dans l'URL
$needsOffer = in_array($current_page, ['Offer.php', 'Apply.php']);

if ($needsOffer && isset($_GET['offer_id'])) {
    $offerId = intval($_GET['offer_id']); // Convertir en entier pour éviter les injections SQL

    // Charger les détails de l'offre depuis la base de données
    $offerDetails = $offerModel->getOfferById($offerId);

    if (!$offerDetails) {
        // Si l'offre n'existe pas, afficher un message d'erreur ou rediriger
        die("Offre non trouvée.");
    }

    // Récupérer les compétences associées à l'offre
    $skills = $offerModel->getOfferSkills($offerId);

    $companiesDetails = $offerModel->getOffersCompanies($offerId);

} elseif ($needsOffer) {
    die("ID de l'offre manquant.");
}

// SECTION 3 : Fonction utilitaire pour créer des slugs URL-friendly
/**
 * Crée un slug à partir d'une chaîne de caractères.
 * Exemple : "Offre de stage développeur" devient "offre-de-stage-developpeur".
 *
 * @param string $string La chaîne à transformer.
 * @return string Le slug généré.
 */
function createSlug($string)
{
    // Convertir en minuscules
    $string = strtolower($string);

    // Remplacer les espaces par des tirets
    $string = preg_replace('/\s+/', '-', $string);

    // Supprimer les caractères spéciaux
    $string = preg_replace('/[^a-z0-9\-]/', '', $string);

    return $string;
}
