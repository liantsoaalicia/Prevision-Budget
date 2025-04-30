<?php
require("../inc/fonctionClient.php");
require("../inc/fonctionReactionRetour.php");

$annee = isset($_GET['annee']) ? $_GET['annee'] : date("Y");
$stats = getProduitsVendusParMois($annee);

$labels = json_encode(array_keys($stats));
$valeurs = json_encode(array_values($stats));
?>

<section id="statistique-produits-vendus">
    <h1 class="main-title">Statistique : Produits vendus par mois</h1>

    <form method="get" action="CRM-page.php">
        <input type="hidden" name="page" value="crm/statistiques/statistique-produits-vendus">
        <label for="annee">Annee :</label>
        <input type="number" name="annee" id="annee" value="<?= htmlspecialchars($annee) ?>" min="2000" max="<?= date("Y") ?>">
        <button type="submit">Afficher</button>
    </form>

    <?php if (array_sum($stats) === 0) { ?>
        <p style="color: red; font-weight: bold;">Aucune vente enregistree en <?= htmlspecialchars($annee) ?>.</p>
    <?php } ?>

    <canvas id="graphiqueProduitsVendus" width="800" height="400"></canvas>

    <script src="../assets/js/chart.umd.js"></script>
    <script>
        const ctx = document.getElementById('graphiqueProduitsVendus').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= $labels ?>,
                datasets: [{
                    label: 'Produits vendus (quantité) en <?= $annee ?>',
                    data: <?= $valeurs ?>,
                    backgroundColor: 'rgba(255, 159, 64, 0.6)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantite vendue'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Mois'
                        }
                    }
                }
            }
        });
    </script>
</section>
