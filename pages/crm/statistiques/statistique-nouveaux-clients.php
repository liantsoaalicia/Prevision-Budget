<?php
require("../inc/fonctionClient.php");

$annee = isset($_GET['annee']) ? $_GET['annee'] : date("Y");

// Nouvelle fonction pour récupérer les nouveaux clients par sexe et par mois
function getNouveauxClientsParSexeEtMois($annee) {
    $con = dbConnect();

    $query = "
        SELECT 
            MONTH(dateInscription) AS mois,
            sexe,
            COUNT(*) AS nombre
        FROM clients
        WHERE YEAR(dateInscription) = :annee
        GROUP BY mois, sexe
        ORDER BY mois, sexe
    ";

    $stmt = $con->prepare($query);
    $stmt->bindParam(':annee', $annee, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$data = getNouveauxClientsParSexeEtMois($annee);

// Préparer les données pour le graphique
$moisNoms = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 
             'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

// Initialiser les données pour chaque sexe
$hommes = array_fill(0, 12, 0);
$femmes = array_fill(0, 12, 0);

foreach ($data as $row) {
    $moisIndex = $row['mois'] - 1;
    if ($row['sexe'] === 'Homme') {
        $hommes[$moisIndex] = (int)$row['nombre'];
    } else {
        $femmes[$moisIndex] = (int)$row['nombre'];
    }
}

$totalClients = array_sum($hommes) + array_sum($femmes);
?>

<section id="statistique-nouveaux-clients">
    <h1 class="main-title">Statistique : Nouveaux clients par sexe et par mois</h1>

    <form method="get" action="CRM-page.php">
        <input type="hidden" name="page" value="crm/statistiques/statistique-nouveaux-clients">
        <label for="annee">Année :</label>
        <input type="number" name="annee" id="annee" value="<?= htmlspecialchars($annee) ?>" min="2000" max="<?= date("Y") ?>">
        <button type="submit">Afficher</button>
    </form>

    <?php if ($totalClients === 0) { ?>
        <p style="color: red; font-weight: bold;">Aucun client inscrit en <?= htmlspecialchars($annee) ?>.</p>
    <?php } else { ?>
        <div style="display: flex; justify-content: space-between;">
            <div style="width: 75%;">
                <canvas id="graphiqueClients" width="800" height="400"></canvas>
            </div>
            <div style="width: 20%; padding: 20px;">
                <h3>Résumé annuel</h3>
                <p>Total clients: <?= $totalClients ?></p>
                <p>Hommes: <?= array_sum($hommes) ?> (<?= round(array_sum($hommes)/$totalClients*100, 1) ?>%)</p>
                <p>Femmes: <?= array_sum($femmes) ?> (<?= round(array_sum($femmes)/$totalClients*100, 1) ?>%)</p>
            </div>
        </div>

        <script src="../assets/js/chart.umd.js"></script>
        <script>
    const ctx = document.getElementById('graphiqueClients').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($moisNoms) ?>,
            datasets: [
                {
                    label: 'Hommes',
                    data: <?= json_encode($hommes) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Femmes',
                    data: <?= json_encode($femmes) ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nombre de nouveaux clients'
                    },
                    ticks: {
                        // Force les valeurs entières sans décimales
                        callback: function(value) {
                            if (value % 1 === 0) {
                                return value;
                            }
                        },
                        // Ajuste automatiquement le pas de graduation
                        stepSize: 1
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
                    text: 'Nouveaux clients par sexe en <?= $annee ?>'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' personne' + (context.parsed.y > 1 ? 's' : '');
                        }
                    }
                }
            }
        }
    });
</script>
    <?php } ?>
</section>