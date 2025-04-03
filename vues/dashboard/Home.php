<?php
require_once('../../src/Controllers/Login.php');
require_once('../../src/Controllers/CheckAuth.php');
require_once('../../src/Controllers/Statistics.php');
?>

<!doctype html>
<html lang="fr">

<head>
    <title>Internity - Dashboard</title>
    <meta charset="UTF-8">
    <meta name="description" content="Internity - Le meilleur de l'Internet">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/dashboard/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard/home.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body class="bg-gray-50 text-CpBlack font-sans">
    <!-- Barre de navigation -->
    <?php include '../include/Navbar.php'; ?>

    <div class="flex">
        <!-- Menu latéral -->
        <?php include 'includes/Menu.php'; ?>

        <!-- Contenu principal -->
        <main class="flex-1 p-8">
            <!-- Section des statistiques -->
            <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 mb-12">
                <div class="bg-CWhite rounded-lg shadow-lg p-8 text-center">
                    <p class="text-base text-CGrey">Entreprises</p>
                    <p class="text-3xl font-bold text-cpblue"><?= $total_companies ?></p>
                </div>
                <div class="bg-CWhite rounded-lg shadow-lg p-8 text-center">
                    <p class="text-base text-CGrey">Offres</p>
                    <p class="text-3xl font-bold text-cpblue"><?= $total_offers ?></p>
                </div>
                <div class="bg-CWhite rounded-lg shadow-lg p-8 text-center">
                    <p class="text-base text-CGrey">Étudiants</p>
                    <p class="text-3xl font-bold text-cpblue"><?= $total_users ?></p>
                </div>
                <div class="bg-CWhite rounded-lg shadow-lg p-8 text-center">
                    <p class="text-base text-CGrey">Pilotes</p>
                    <p class="text-3xl font-bold text-cpblue"><?= $total_pilotes ?></p>
                </div>
                <div class="bg-CWhite rounded-lg shadow-lg p-8 text-center">
                    <p class="text-base text-CGrey">Postulations</p>
                    <p class="text-3xl font-bold text-cpblue"><?= $total_apply ?></p>
                </div>
                <div class="bg-CWhite rounded-lg shadow-lg p-8 text-center">
                    <p class="text-base text-CGrey">Wishlist</p>
                    <p class="text-3xl font-bold text-cpblue"><?= $total_wishlists ?></p>
                </div>
                <div class="bg-CWhite rounded-lg shadow-lg p-8 text-center">
                    <p class="text-base text-CGrey">Moy. Évaluations</p>
                    <p class="text-3xl font-bold text-cpblue"><?= number_format($moyenne, 2) ?>/5</p>
                </div>
            </section>

            <!-- Graphique des offres par statut -->
            <section class="bg-CWhite rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-semibold text-CpBlack mb-6">Statut des candidatures</h2>
                <div class="max-w-lg mx-auto">
                    <canvas id="offersStatusChart" width="500" height="300"></canvas>
                </div>
            </section>
        </main>
    </div>

    <style>
        :root {
            --CWhite: rgb(255, 255, 255);
            --CBlack: rgba(0, 0, 0);
            --CoBlack: rgba(0, 0, 0, 0.1);
            --CpBlack: rgb(34, 40, 49);
            --CsBlack: rgb(49, 54, 63);
            --CGrey: rgba(255, 255, 255, 0.6);
            --CpBlue: rgb(118, 171, 174);
        }

        .dashboard-menu>ul>li:nth-child(1)::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: var(--CpBlue);
        }

        .text-cpblue {
            color: var(--CpBlue);
        }
    </style>

    <script>
        const ctx = document.getElementById('offersStatusChart').getContext('2d');
        const offersStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['En attente', 'Acceptée', 'Refusée'],
                datasets: [{
                    label: 'Candidatures',
                    data: [<?= $apply_pending ?>, <?= $apply_accepted ?>, <?= $apply_rejected ?>],
                    backgroundColor: [
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 15 // Augmente la taille des légendes
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>