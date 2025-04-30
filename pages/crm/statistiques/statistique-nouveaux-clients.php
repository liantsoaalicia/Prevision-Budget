<?php
require("../inc/fonctionClient.php");

$annee = isset($_GET['annee']) ? $_GET['annee'] : date("Y");
$stats = getNouveauxClientsParMois($annee);

$labels = json_encode(array_keys($stats));
$valeurs = json_encode(array_values($stats));
?>

<section id="statistique-nouveaux-clients">
    <h1 class="main-title">Statistique : Nouveaux clients par mois</h1>

    <form method="get" action="CRM-page.php">
        <input type="hidden" name="page" value="crm/statistiques/statistique-nouveaux-clients">
        <label for="annee">Annee :</label>
        <input type="number" name="annee" id="annee" value="<?= htmlspecialchars($annee) ?>" min="2000" max="<?= date("Y") ?>">
        <button type="submit">Afficher</button>
    </form>

    <?php if (array_sum($stats) === 0) { ?>
        <p style="color: red; font-weight: bold;">Aucun client inscrit en <?= htmlspecialchars($annee) ?>.</p>
    <?php } ?>

    <canvas id="graphiqueClients" width="800" height="400"></canvas>

    <script src="../assets/js/chart.umd.js"></script>
    <script>
        const ctx = document.getElementById('graphiqueClients').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= $labels ?>,
                datasets: [{
                    label: 'Nouveaux clients en <?= $annee ?>',
                    data: <?= $valeurs ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</section>
