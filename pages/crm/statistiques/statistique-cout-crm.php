<?php
require("../inc/fonctionClient.php");

$annee = isset($_GET['annee']) ? $_GET['annee'] : date("Y");

// Fonction pour obtenir les statistiques des coûts CRM
function getStatistiquesCoutsCRM($annee) {
    $con = dbConnect();

    // 1. Coûts totaux par mois (prévision vs réalisation)
    $queryParMois = "
        SELECT 
            MONTH(dateAction) as mois,
            SUM(coutsPrevision) as total_prevision,
            SUM(coutsRealisation) as total_realisation
        FROM actionsCrm
        WHERE YEAR(dateAction) = :annee
        GROUP BY mois
        ORDER BY mois
    ";

    // 2. Coûts totaux par département
    $queryParDepartement = "
        SELECT 
            d.nom as departement,
            SUM(a.coutsPrevision) as total_prevision,
            SUM(a.coutsRealisation) as total_realisation
        FROM actionsCrm a
        JOIN departement d ON a.idDepartement = d.idDepartement
        WHERE YEAR(a.dateAction) = :annee
        GROUP BY a.idDepartement
        ORDER BY total_realisation DESC
    ";

    // 3. Coûts par type d'action
    $queryParType = "
        SELECT 
            typeAction,
            SUM(coutsPrevision) as total_prevision,
            SUM(coutsRealisation) as total_realisation
        FROM actionsCrm
        WHERE YEAR(dateAction) = :annee
        GROUP BY typeAction
        ORDER BY total_realisation DESC
    ";

    $stmtMois = $con->prepare($queryParMois);
    $stmtMois->bindParam(':annee', $annee, PDO::PARAM_INT);
    $stmtMois->execute();
    $statsMois = $stmtMois->fetchAll(PDO::FETCH_ASSOC);

    $stmtDepartement = $con->prepare($queryParDepartement);
    $stmtDepartement->bindParam(':annee', $annee, PDO::PARAM_INT);
    $stmtDepartement->execute();
    $statsDepartement = $stmtDepartement->fetchAll(PDO::FETCH_ASSOC);

    $stmtType = $con->prepare($queryParType);
    $stmtType->bindParam(':annee', $annee, PDO::PARAM_INT);
    $stmtType->execute();
    $statsType = $stmtType->fetchAll(PDO::FETCH_ASSOC);

    // Calcul des totaux
    $totalPrevision = 0;
    $totalRealisation = 0;

    foreach ($statsMois as $stat) {
        $totalPrevision += $stat['total_prevision'];
        $totalRealisation += $stat['total_realisation'];
    }

    return [
        'par_mois' => $statsMois,
        'par_departement' => $statsDepartement,
        'par_type' => $statsType,
        'total_prevision' => $totalPrevision,
        'total_realisation' => $totalRealisation
    ];
}

$stats = getStatistiquesCoutsCRM($annee);

// Noms des mois
$moisNoms = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 
             'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

// Préparation des données pour le graphique par mois
$dataPrevisionMois = array_fill(0, 12, 0);
$dataRealisationMois = array_fill(0, 12, 0);

foreach ($stats['par_mois'] as $stat) {
    $moisIndex = $stat['mois'] - 1;
    $dataPrevisionMois[$moisIndex] = (float)$stat['total_prevision'];
    $dataRealisationMois[$moisIndex] = (float)$stat['total_realisation'];
}

// Calculer l'écart en pourcentage
$ecartPourcentage = 0;
if ($stats['total_prevision'] > 0) {
    $ecartPourcentage = round((($stats['total_realisation'] - $stats['total_prevision']) / $stats['total_prevision']) * 100, 2);
}

// Préparation des données pour le graphique par département
$departements = [];
$dataPrevisionDept = [];
$dataRealisationDept = [];

foreach ($stats['par_departement'] as $stat) {
    $departements[] = $stat['departement'];
    $dataPrevisionDept[] = (float)$stat['total_prevision'];
    $dataRealisationDept[] = (float)$stat['total_realisation'];
}

// Préparation des données pour le graphique par type d'action
$typesAction = [];
$dataPrevisionType = [];
$dataRealisationType = [];

foreach ($stats['par_type'] as $stat) {
    $typesAction[] = $stat['typeAction'];
    $dataPrevisionType[] = (float)$stat['total_prevision'];
    $dataRealisationType[] = (float)$stat['total_realisation'];
}
?>

<section id="statistique-couts-crm">
    <h1 class="main-title">Statistique : Coûts des actions CRM (Prévision vs Réalisation)</h1>

    <form method="get" action="CRM-page.php">
        <input type="hidden" name="page" value="crm/statistiques/statistique-couts-crm">
        <label for="annee">Année :</label>
        <input type="number" name="annee" id="annee" value="<?= htmlspecialchars($annee) ?>" min="2000" max="<?= date("Y") ?>">
        <button type="submit">Afficher</button>
    </form>

    <?php if ($stats['total_prevision'] == 0 && $stats['total_realisation'] == 0) { ?>
        <p style="color: red; font-weight: bold;">Aucune action CRM enregistrée en <?= htmlspecialchars($annee) ?>.</p>
    <?php } else { ?>
        <!-- Résumé des coûts totaux -->
        <div style="margin: 20px 0; padding: 20px; background-color: #f9f9f9; border-radius: 8px; text-align: center;">
            <h2>Résumé des coûts pour l'année <?= htmlspecialchars($annee) ?></h2>
            <div style="display: flex; justify-content: space-around; flex-wrap: wrap;">
                <div style="min-width: 200px; margin: 10px; padding: 15px; background-color: rgba(54, 162, 235, 0.1); border-radius: 5px;">
                    <h3>Coût Total Prévu</h3>
                    <p style="font-size: 24px; font-weight: bold;"><?= number_format($stats['total_prevision'], 2, ',', ' ') ?></p>
                </div>
                <div style="min-width: 200px; margin: 10px; padding: 15px; background-color: rgba(255, 99, 132, 0.1); border-radius: 5px;">
                    <h3>Coût Total Réalisé</h3>
                    <p style="font-size: 24px; font-weight: bold;"><?= number_format($stats['total_realisation'], 2, ',', ' ') ?></p>
                </div>
                <div style="min-width: 200px; margin: 10px; padding: 15px; background-color: <?= $ecartPourcentage <= 0 ? 'rgba(75, 192, 192, 0.1)' : 'rgba(255, 159, 64, 0.1)' ?>; border-radius: 5px;">
                    <h3>Écart</h3>
                    <p style="font-size: 24px; font-weight: bold; color: <?= $ecartPourcentage <= 0 ? 'green' : 'orange' ?>">
                        <?= number_format($stats['total_realisation'] - $stats['total_prevision'], 2, ',', ' ') ?> 
                        (<?= $ecartPourcentage ?>%)
                    </p>
                </div>
            </div>
        </div>

        <!-- Graphique par mois -->
        <div style="margin-top: 40px;">
            <h3>Évolution mensuelle des coûts</h3>
            <div style="height: 400px;">
                <canvas id="graphiqueCoutsMensuels"></canvas>
            </div>
        </div>

        <!-- Graphique par département -->
        <div style="margin-top: 40px;">
            <h3>Coûts par département</h3>
            <div style="height: 400px;">
                <canvas id="graphiqueCoutsDepartements"></canvas>
            </div>
        </div>

        <!-- Graphique par type d'action -->
        <div style="margin-top: 40px;">
            <h3>Coûts par type d'action</h3>
            <div style="height: 400px;">
                <canvas id="graphiqueTypesAction"></canvas>
            </div>
        </div>

        <script src="../assets/js/chart.umd.js"></script>
        <script>
            // Graphique par mois
            const ctxMois = document.getElementById('graphiqueCoutsMensuels').getContext('2d');
            new Chart(ctxMois, {
                type: 'line',
                data: {
                    labels: <?= json_encode($moisNoms) ?>,
                    datasets: [
                        {
                            label: 'Prévision',
                            data: <?= json_encode($dataPrevisionMois) ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            pointRadius: 4,
                            fill: true
                        },
                        {
                            label: 'Réalisation',
                            data: <?= json_encode($dataRealisationMois) ?>,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2,
                            pointRadius: 4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Coût'
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
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw.toFixed(2);
                                }
                            }
                        }
                    }
                }
            });

            // Graphique par département
            const ctxDepartement = document.getElementById('graphiqueCoutsDepartements').getContext('2d');
            new Chart(ctxDepartement, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($departements) ?>,
                    datasets: [
                        {
                            label: 'Prévision',
                            data: <?= json_encode($dataPrevisionDept) ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Réalisation',
                            data: <?= json_encode($dataRealisationDept) ?>,
                            backgroundColor: 'rgba(255, 99, 132, 0.7)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Coût'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Département'
                            }
                        }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw.toFixed(2);
                                }
                            }
                        }
                    }
                }
            });

            // Graphique par type d'action
            const ctxType = document.getElementById('graphiqueTypesAction').getContext('2d');
            new Chart(ctxType, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($typesAction) ?>,
                    datasets: [
                        {
                            label: 'Prévision',
                            data: <?= json_encode($dataPrevisionType) ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Réalisation',
                            data: <?= json_encode($dataRealisationType) ?>,
                            backgroundColor: 'rgba(255, 99, 132, 0.7)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Coût'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Type d\'action'
                            }
                        }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw.toFixed(2);
                                }
                            }
                        }
                    }
                }
            });
        </script>
    <?php } ?>
</section>