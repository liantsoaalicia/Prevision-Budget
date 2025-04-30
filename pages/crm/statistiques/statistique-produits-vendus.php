<?php
require("../inc/fonctionClient.php");
require("../inc/fonctionReactionRetour.php");

$annee = isset($_GET['annee']) ? $_GET['annee'] : date("Y");

function getDetailsProduitsVendusParMois($annee) {
    $con = dbConnect();

    $query = "
        SELECT 
            MONTH(c.dateCommande) AS mois,
            p.nom AS produit,
            p.idProduit,
            SUM(l.quantite) AS quantite
        FROM commandes c
        JOIN ligneCommandes l ON c.idCommande = l.idCommande
        JOIN produits p ON l.idProduit = p.idProduit
        WHERE YEAR(c.dateCommande) = :annee
        GROUP BY mois, p.idProduit
        ORDER BY mois, quantite DESC
    ";

    $stmt = $con->prepare($query);
    $stmt->bindParam(':annee', $annee, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$detailsVentes = getDetailsProduitsVendusParMois($annee);

// Noms des mois
$moisNoms = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 
             'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

// Récupérer les 5 produits les plus vendus sur l'année
$topProduits = [];
foreach ($detailsVentes as $vente) {
    if (!isset($topProduits[$vente['idProduit']])) {
        $topProduits[$vente['idProduit']] = [
            'nom' => $vente['produit'],
            'total' => 0,
            'mois' => array_fill(0, 12, 0) // Initialiser tous les mois à 0
        ];
    }
    $topProduits[$vente['idProduit']]['total'] += $vente['quantite'];
    $topProduits[$vente['idProduit']]['mois'][$vente['mois'] - 1] = (int)$vente['quantite'];
}

// Trier et garder les 5 premiers
usort($topProduits, function($a, $b) {
    return $b['total'] - $a['total'];
});
$topProduits = array_slice($topProduits, 0, 5);

// Préparer les données pour Chart.js
$datasets = [];
$colors = [
    'rgba(255, 99, 132, 0.7)',
    'rgba(54, 162, 235, 0.7)',
    'rgba(255, 206, 86, 0.7)',
    'rgba(75, 192, 192, 0.7)',
    'rgba(153, 102, 255, 0.7)'
];

foreach ($topProduits as $index => $produit) {
    $datasets[] = [
        'label' => $produit['nom'],
        'data' => $produit['mois'],
        'backgroundColor' => $colors[$index % count($colors)],
        'borderColor' => str_replace('0.7', '1', $colors[$index % count($colors)]),
        'borderWidth' => 1
    ];
}

$labels = json_encode($moisNoms);
$valeurs = json_encode($datasets);
?>

<section id="statistique-produits-vendus">
    <h1 class="main-title">Statistique : Produits vendus par mois</h1>

    <form method="get" action="CRM-page.php">
        <input type="hidden" name="page" value="crm/statistiques/statistique-produits-vendus">
        <label for="annee">Année :</label>
        <input type="number" name="annee" id="annee" value="<?= htmlspecialchars($annee) ?>" min="2000" max="<?= date("Y") ?>">
        <button type="submit">Afficher</button>
    </form>

    <?php if (empty($detailsVentes)) { ?>
        <p style="color: red; font-weight: bold;">Aucune vente enregistrée en <?= htmlspecialchars($annee) ?>.</p>
    <?php } else { ?>
        <div style="display: flex;">
            <div style="flex: 2;">
                <canvas id="graphiqueProduitsVendus" width="800" height="400"></canvas>
            </div>
            <div style="flex: 1; padding: 20px;">
                <h3>Top 5 des produits</h3>
                <ul>
                    <?php foreach ($topProduits as $produit) { ?>
                        <li><?= htmlspecialchars($produit['nom']) ?> (<?= $produit['total'] ?> unités)</li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <script src="../assets/js/chart.umd.js"></script>
        <script>
            const ctx = document.getElementById('graphiqueProduitsVendus').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= $labels ?>,
                    datasets: <?= $valeurs ?>
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Quantité vendue'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Mois'
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Ventes mensuelles par produit (<?= $annee ?>)'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw + ' unités';
                                }
                            }
                        }
                    }
                }
            });
        </script>
    <?php } ?>
</section>