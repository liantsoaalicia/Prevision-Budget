<?php
require("../inc/fonctionClient.php");
require("../inc/fonctionReactionRetour.php");

$annee = isset($_GET['annee']) ? $_GET['annee'] : date("Y");

$stats = getRetoursClients($annee);

$labels = json_encode(array_keys($stats));
$valeurs = json_encode(array_values($stats));
?>

<section id="statistique-retours-clients">
    <h1 class="main-title">Statistique : Retours clients - Tsara vs Ratsy</h1>

    <form method="get" action="CRM-page.php">
        <input type="hidden" name="page" value="crm/statistiques/statistique-retours-clients">
        <label for="annee">Année :</label>
        <input type="number" name="annee" id="annee" value="<?= htmlspecialchars($annee) ?>" min="2000" max="<?= date("Y") ?>">
        <button type="submit">Afficher</button>
    </form>

    <?php if (array_sum($stats) === 0) { ?>
        <p style="color: red; font-weight: bold;">Aucun retour client enregistré en <?= htmlspecialchars($annee) ?>.</p>
    <?php } ?>

    <!-- Canvas avec style inline pour taille fixe -->
    <div style="width: 300px; height: 300px; margin: auto;">
        <canvas id="graphiqueRetoursClients" style="width: 100%; height: 100%;"></canvas>
    </div>

    <script src="../assets/js/chart.umd.js"></script>
    <script>
        const ctx = document.getElementById('graphiqueRetoursClients').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?= $labels ?>,
                datasets: [{
                    label: 'Répartition des retours clients en <?= $annee ?>',
                    data: <?= $valeurs ?>,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Important pour s’adapter au conteneur fixe
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' retours';
                            }
                        }
                    }
                }
            }
        });
    </script>
</section>

