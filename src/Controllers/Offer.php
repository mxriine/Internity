<?php

require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Models/Offer.php');

use Models\Offer;

$offerModel = new Offer($conn);

// ➤ Récupérer toutes les offres
$offers = $offerModel->getAllOffers();

// Nombre d'offres par page
$elements_par_page = 9;

// Récupérer le numéro de la page actuelle depuis l'URL
$page_actuelle = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page_actuelle < 1) {
    $page_actuelle = 1;
}

// Calculer l'offset
$offset = ($page_actuelle - 1) * $elements_par_page;

// Instancier le modèle Offer
$offerModel = new Offer($conn);

// Récupérer les offres paginées
$offers = $offerModel->getPaginatedOffers($elements_par_page, $offset);

// Compter le nombre total d'offres
$total_offers = $offerModel->getTotalOffersCount();

// Calculer le nombre total de pages
$total_pages = ceil($total_offers / $elements_par_page);

// Afficher les cartes d'offres
echo '<section class="cards">';
foreach ($offers as $offer) {
    // Récupérer les skills de l'offre
    $skills = $offerModel->getOfferSkills($offer['offer_id']);

    // Limiter à 3 skills max
    $limitedSkills = array_slice($skills, 0, 3);

    // Générer les labels pour chaque skill
    $skillsLabels = '';
    foreach ($limitedSkills as $skill) {
        $skillsLabels .= '<span class="skills">' . htmlspecialchars($skill['skills_name']) . '</span> ';
    }

    echo '
        <div class="card">
            <div class="card-header">
                <div class="card-img">
                    <img src="/assets/icons/star-circle.svg" alt="Favori">
                </div>
            </div>
            <div class="card-body">
                <div class="skills-container">
                    ' . $skillsLabels . '
                </div>
                <h2>' . htmlspecialchars($offer['offer_title']) . '</h2>
                <p>' . htmlspecialchars($offer['offer_desc']) . '</p>
                <button class="btn">Voir l\'offre</button>
            </div>
        </div>';
}
echo '</section>';

// Afficher la pagination
echo '<div class="pagination">';

// Bouton "Précédent"
if ($page_actuelle > 1) {
    echo '<a href="?page=' . ($page_actuelle - 1) . '">Précédent</a>';
}

// Numéros de page
for ($i = 1; $i <= $total_pages; $i++) {
    if ($i === $page_actuelle) {
        echo '<a href="?page=' . $i . '" class="active">' . $i . '</a>';
    } else {
        echo '<a href="?page=' . $i . '">' . $i . '</a>';
    }
}

// Bouton "Suivant"
if ($page_actuelle < $total_pages) {
    echo '<a href="?page=' . ($page_actuelle + 1) . '">Suivant</a>';
}

echo '</div>';
