<?php
require("../inc/fonctionClient.php");
require("../inc/fonctionReactionRetour.php");

$annee = isset($_GET['annee']) ? $_GET['annee'] : date("Y");

// Fonction pour obtenir les retours clients avec plus de détails
function getStatistiquesRetoursClients($annee) {
    $con = dbConnect();

    // 1. Statistiques globales Tsara/Ratsy
    $queryGlobal = "
        SELECT 
            avis,
            COUNT(*) as nombre_retours
        FROM retoursClients
        WHERE YEAR(dateRetour) = :annee
        GROUP BY avis
    ";

    // 2. Statistiques par produit
    $queryParProduit = "
        SELECT 
            p.nom as produit,
            r.avis,
            COUNT(*) as nombre_retours
        FROM retoursClients r
        JOIN commandes c ON r.idCommande = c.idCommande
        JOIN ligneCommandes l ON c.idCommande = l.idCommande
        JOIN produits p ON l.idProduit = p.idProduit
        WHERE YEAR(r.dateRetour) = :annee
        GROUP BY p.idProduit, r.avis
        ORDER BY nombre_retours DESC
        LIMIT 5
    ";

    $stmtGlobal = $con->prepare($queryGlobal);
    $stmtGlobal->bindParam(':annee', $annee, PDO::PARAM_INT);
    $stmtGlobal->execute();
    $statsGlobal = $stmtGlobal->fetchAll(PDO::FETCH_ASSOC);

    $stmtProduit = $con->prepare($queryParProduit);
    $stmtProduit->bindParam(':annee', $annee, PDO::PARAM_INT);
    $stmtProduit->execute();
    $statsProduit = $stmtProduit->fetchAll(PDO::FETCH_ASSOC);

    return [
        'global' => $statsGlobal,
        'par_produit' => $statsProduit
    ];
}

$stats = getStatistiquesRetoursClients($annee);

// Préparer les données pour le graphique global
$labelsGlobal = ['Tsara', 'Ratsy'];
$dataGlobal = [0, 0]; // Initialiser à 0

foreach ($stats['global'] as $retour) {
    if ($retour['avis'] === 'tsara') {
        $dataGlobal[0] = (int)$retour['nombre_retours'];
    } else {
        $dataGlobal[1] = (int)$retour['nombre_retours'];
    }
}

$totalRetours = array_sum($dataGlobal);
?>

<section id="statistique-retours-clients">
    <h1 class="main-title">Statistique : Analyse des retours clients</h1>

    <form method="get" action="CRM-page.php">
        <input type="hidden" name="page" value="crm/statistiques/statistique-retours-clients">
        <label for="annee">Année :</label>
        <input type="number" name="annee" id="annee" value="<?= htmlspecialchars($annee) ?>" min="2000" max="<?= date("Y") ?>">
        <button type="submit">Afficher</button>
    </form>

    <?php if ($totalRetours === 0) { ?>
        <p style="color: red; font-weight: bold;">Aucun retour client enregistré en <?= htmlspecialchars($annee) ?>.</p>
    <?php } else { ?>
        <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
            <!-- Graphique global -->
            <div style="width: 400px; height: 400px;">
                <h3>Répartition globale des retours</h3>
                <canvas id="graphiqueRetoursGlobaux"></canvas>
                <?php if ($totalRetours > 0) { ?>
                    <p>Total retours: <?= $totalRetours ?></p>
                    <p>Tsara: <?= round(($dataGlobal[0]/$totalRetours)*100, 2) ?>%</p>
                    <p>Ratsy: <?= round(($dataGlobal[1]/$totalRetours)*100, 2) ?>%</p>
                <?php } ?>
            </div>

            <!-- Graphique par produit -->
            <div style="width: 400px; height: 400px;">
                <h3>Top 5 produits avec retours</h3>
                <canvas id="graphiqueRetoursProduits"></canvas>
            </div>
        </div>

        <script src="../assets/js/chart.umd.js"></script>
        <script>
            // Graphique global
            const ctxGlobal = document.getElementById('graphiqueRetoursGlobaux').getContext('2d');
            new Chart(ctxGlobal, {
                type: 'pie',
                data: {
                    labels: <?= json_encode($labelsGlobal) ?>,
                    datasets: [{
                        label: 'Répartition des retours en <?= $annee ?>',
                        data: <?= json_encode($dataGlobal) ?>,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(255, 99, 132, 0.7)'
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
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Préparation des données pour le graphique par produit
            const produits = <?= json_encode(array_unique(array_column($stats['par_produit'], 'produit'))) ?>;
            const dataProduits = {
                labels: produits,
                datasets: [
                    {
                        label: 'Tsara',
                        data: produits.map(prod => {
                            return <?= json_encode($stats['par_produit']) ?>.filter(r => r.produit === prod && r.avis === 'tsara').reduce((sum, r) => sum + parseInt(r.nombre_retours), 0);
                        }),
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Ratsy',
                        data: produits.map(prod => {
                            return <?= json_encode($stats['par_produit']) ?>.filter(r => r.produit === prod && r.avis === 'ratsy').reduce((sum, r) => sum + parseInt(r.nombre_retours), 0);
                        }),
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            };

            // Graphique par produit (stacked bar chart)
            const ctxProduits = document.getElementById('graphiqueRetoursProduits').getContext('2d');
            new Chart(ctxProduits, {
                type: 'bar',
                data: dataProduits,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw} retours`;
                                }
                            }
                        }
                    }
                }
            });
        </script>
    <?php } ?>
</section>