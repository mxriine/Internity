<?php

require_once(__DIR__ . '/../Core/DataBase.php');
require_once(__DIR__ . '/../Models/Offer.php');

use Models\Offer;

$offerModel = new Offer($conn);

// ➤ Récupérer toutes les offres
$offers = $offerModel->getAllOffers();

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
