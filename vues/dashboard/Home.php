<!-- FORMULAIRE EN PHP -->
<?php
require_once('../../src/Controllers/Login.php');
require_once('../../src/Controllers/CheckAuth.php');
require_once('../../src/Controllers/Companies.php');
require_once('../../src/Controllers/Wishlist.php');
?>

<!doctype html>
<html lang="fr">

<head>
    <title>Internity - Discover</title>
    <meta charset="UTF-8">
    <meta name="description" content="Internity - Le meilleur de l'Internet">
    <meta name="author" content="Internity">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/dashboard/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard/home.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body>
    <!-- Barre de navigation -->
    <?php include '../include/Navbar.php'; ?>

    <?php include 'includes/Menu.php'; ?>
    <main>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-gray-500">Entreprises</p>
                <p class="text-2xl font-bold text-blue-600"><?= $total_companies ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-gray-500">Offres</p>
                <p class="text-2xl font-bold text-green-600"><?= $countOffers ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-gray-500">Etudiants</p>
                <p class="text-2xl font-bold text-purple-600"><?= $countStudents ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-gray-500">Pilotes</p>
                <p class="text-2xl font-bold text-yellow-600"><?= $countPilots ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-gray-500">Postulations</p>
                <p class="text-2xl font-bold text-red-600"><?= $countApplications ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-gray-500">Wishlist</p>
                <p class="text-2xl font-bold text-pink-600"><?= $nbr_wishlist ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-gray-500">Moy. Evaluations</p>
                <p class="text-2xl font-bold text-indigo-600"><?= number_format($moyenne, 2) ?>/5</p>
            </div>
        </div>

        <!-- Graphe Offres par statut -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Statut des Offres</h2>
            <canvas id="offersStatusChart" width="400" height="200"></canvas>
        </div>
        </div>
    </main>

</body>

<style>
    .dashboard-menu>ul>li:nth-child(1)::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: var(--widthslect);
        background-color: var(--CpBlue);
    }
</style>

<script>
    const ctx = document.getElementById('offersStatusChart').getContext('2d');
    const offersStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['En attente', 'Acceptée', 'Refusée'],
            datasets: [{
                label: 'Offres',
                data: [<?= $pendingOffers ?>, <?= $acceptedOffers ?>, <?= $refusedOffers ?>],
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
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

</html>