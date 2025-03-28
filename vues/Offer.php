<!-- FORMULAIRE DE CONNEXION (EN PHP) -->
<?php
require_once('../src/Controllers/LoginController.php');
require_once('Navbar.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Internity - Stage</title>
    <meta charset="UTF-8">
    <meta name="description" content="Internity - Le meilleur de l'Internet">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/offer.css">

</head>




<body>

<main>
    <!-- Hero Image Section -->
    <div class="hero-section">
        <div class="card-header">
            <div class="card-img">
                <img src="/assets/icons/star-circle.svg" alt="Favori">
            </div>
        </div>
        <div class="hero-title">Titre du stage</div>
    </div>

    <!-- Main Content Section -->
    <div class="content-container">
        <!-- Text Section -->
        <div class="text-section">
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, tortor ac varius fermentum,
                neque mi interdum neque, id convallis odio justo id orci. Curabitur ut felis sed purus fringilla
                pharetra. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae;
                Duis ut magna vitae velit facilisis bibendum. Sed vehicula orci sit amet purus dictum, nec ullamcorper
                felis rhoncus. Integer aliquet quam nec nisi egestas, et facilisis ipsum aliquet. Nam dictum felis nec
                lacus scelerisque, at pulvinar ligula sodales. Fusce luctus libero id ipsum tincidunt, a luctus odio
                rhoncus.
            </p>
            <p>
                Suspendisse potenti. Nulla facilisi. Ut consectetur, neque in sollicitudin consectetur, dolor mi dictum
                ex, ut scelerisque enim magna ac lorem. Cras tristique, libero vel consectetur fermentum, nulla est
                bibendum lectus, id vestibulum elit purus nec felis. In hac habitasse platea dictumst. Integer auctor
                augue ut nisl placerat, eget aliquet felis dictum. Vestibulum non eros massa. Quisque elementum justo id
                dolor malesuada, vel scelerisque mauris elementum.
            </p>
            <p>
                Donec dignissim malesuada urna, nec vestibulum justo in sapien egestas, non hendrerit lectus fermentum.
                Pellentesque et tortor id metus auctor laoreet et nec nunc. Etiam convallis purus justo, in condimentum
                nunc fermentum eu. Duis ut purus id ex efficitur laoreet. Morbi sollicitudin libero sit amet tortor
                eleifend, a scelerisque augue sagittis. Mauris auctor urna ut massa dictum, eget interdum tortor
                rhoncus.
            </p>
        </div>

        <!-- Card Section -->
        <div class="card-section">
            <div class="card">
                <div class="card-header">
                    <img src="https://via.placeholder.com/100" alt="Logo ou icône" style="border-radius: 50%;">
                    <h2>Titre du stage</h2>
                </div>
                <div class="card-body">
                    <p><strong>Thématique :</strong> kouizine</p>
                    <p><strong>Niveau :</strong> Bac+2</p>
                    <p><strong>Ville :</strong> Pau</p>
                </div>
                <div class="card-footer">
                    <button class="card-button">POSTULER</button>
                </div>
            </div>
        </div>
    </div>

</main>

    <!-- Footer -->
    <footer>
        <a class="legal" href="/vues/publisher.php">Mention légale</a>
        <p>© 2025 - Internity</p>
    </footer>


</body>

</html>