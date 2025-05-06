<?php
require("../inc/fonctionClient.php");

$annee = isset($_GET['annee']) ? $_GET['annee'] : date("Y");

function getStatistiquesClientsParSegment($annee) {
    $con = dbConnect();

    // 1. Statistiques par sexe
    $queryParSexe = "
        SELECT 
            sexe,
            COUNT(*) as nombre_clients
        FROM clients
        WHERE YEAR(dateInscription) <= :annee
        GROUP BY sexe
    ";

    // 2. Statistiques par classe
    $queryParClasse = "
        SELECT 
            classe,
            COUNT(*) as nombre_clients
        FROM clients
        WHERE YEAR(dateInscription) <= :annee
        GROUP BY classe
    ";

    // 3. Statistiques croisées (sexe et classe)
    $queryCroise = "
        SELECT 
            sexe,
            classe,
            COUNT(*) as nombre_clients
        FROM clients
        WHERE YEAR(dateInscription) <= :annee
        GROUP BY sexe, classe
    ";

    $stmtSexe = $con->prepare($queryParSexe);
    $stmtSexe->bindParam(':annee', $annee, PDO::PARAM_INT);
    $stmtSexe->execute();
    $statsSexe = $stmtSexe->fetchAll(PDO::FETCH_ASSOC);

    $stmtClasse = $con->prepare($queryParClasse);
    $stmtClasse->bindParam(':annee', $annee, PDO::PARAM_INT);
    $stmtClasse->execute();
    $statsClasse = $stmtClasse->fetchAll(PDO::FETCH_ASSOC);

    $stmtCroise = $con->prepare($queryCroise);
    $stmtCroise->bindParam(':annee', $annee, PDO::PARAM_INT);
    $stmtCroise->execute();
    $statsCroise = $stmtCroise->fetchAll(PDO::FETCH_ASSOC);

    return [
        'par_sexe' => $statsSexe,
        'par_classe' => $statsClasse,
        'croise' => $statsCroise
    ];
}

$stats = getStatistiquesClientsParSegment($annee);

$labelsSexe = [];
$dataSexe = [];
foreach ($stats['par_sexe'] as $item) {
    $labelsSexe[] = $item['sexe'];
    $dataSexe[] = (int)$item['nombre_clients'];
}

$labelsClasse = [];
$dataClasse = [];
foreach ($stats['par_classe'] as $item) {
    $labelsClasse[] = $item['classe'];
    $dataClasse[] = (int)$item['nombre_clients'];
}

$classes = ['eleve', 'moyen', 'bas'];
$sexes = ['Homme', 'Femme'];

$dataHommes = array_fill(0, count($classes), 0);
$dataFemmes = array_fill(0, count($classes), 0);

foreach ($stats['croise'] as $item) {
    $classeIndex = array_search($item['classe'], $classes);
    if ($classeIndex !== false) {
        if ($item['sexe'] === 'Homme') {
            $dataHommes[$classeIndex] = (int)$item['nombre_clients'];
        } else {
            $dataFemmes[$classeIndex] = (int)$item['nombre_clients'];
        }
    }
}

$totalClients = array_sum($dataSexe);
?>

<section id="statistique-clients-segments">
    <h1 class="main-title">Statistique : Analyse des clients par segment</h1>

    <form method="get" action="CRM-page.php">
        <input type="hidden" name="page" value="crm/statistiques/statistique-total-clients">
        <label for="annee">Année :</label>
        <input type="number" name="annee" id="annee" value="<?= htmlspecialchars($annee) ?>" min="2000" max="<?= date("Y") ?>">
        <button type="submit">Afficher</button>
    </form>

    <?php if ($totalClients === 0) { ?>
        <p style="color: red; font-weight: bold;">Aucun client enregistré jusqu'en <?= htmlspecialchars($annee) ?>.</p>
    <?php } else { ?>
        <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
            <!-- Graphique par sexe -->
            <div style="width: 400px; height: 400px;">
                <br>
                <br>
                <h3>Répartition des clients par sexe</h3>
                <canvas id="graphiqueClientsSexe"></canvas>
                <div style="margin-top: 15px; text-align: center;">
                    <p>Total clients: <?= $totalClients ?></p>
                    <?php foreach ($stats['par_sexe'] as $item) { ?>
                        <p><?= $item['sexe'] ?>: <?= $item['nombre_clients'] ?> (<?= round(($item['nombre_clients']/$totalClients)*100, 2) ?>%)</p>
                    <?php } ?>
                </div>
            </div>

            <!-- Graphique par classe -->
            <div style="width: 400px; height: 400px;">
                <h3>Répartition des clients par classe</h3>
                <canvas id="graphiqueClientsClasse"></canvas>
                <div style="margin-top: 15px; text-align: center;">
                    <?php foreach ($stats['par_classe'] as $item) { ?>
                        <p><?= ucfirst($item['classe']) ?>: <?= $item['nombre_clients'] ?> (<?= round(($item['nombre_clients']/$totalClients)*100, 2) ?>%)</p>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Graphique croisé (sexe et classe) -->
        <div style="margin-top: 40px; width: 100%;">
            <h3>Répartition des clients par sexe et classe</h3>
            <div style="height: 400px;">
                <canvas id="graphiqueClientsCroise"></canvas>
            </div>
        </div>

        <script src="../assets/js/chart.umd.js"></script>
        <script>
            // Graphique par sexe
            const ctxSexe = document.getElementById('graphiqueClientsSexe').getContext('2d');
            new Chart(ctxSexe, {
                type: 'pie',
                data: {
                    labels: <?= json_encode($labelsSexe) ?>,
                    datasets: [{
                        label: 'Répartition par sexe en <?= $annee ?>',
                        data: <?= json_encode($dataSexe) ?>,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 99, 132, 0.7)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
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

            // Graphique par classe
            const ctxClasse = document.getElementById('graphiqueClientsClasse').getContext('2d');
            new Chart(ctxClasse, {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode(array_map('ucfirst', $labelsClasse)) ?>,
                    datasets: [{
                        label: 'Répartition par classe en <?= $annee ?>',
                        data: <?= json_encode($dataClasse) ?>,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(153, 102, 255, 0.7)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(153, 102, 255, 1)'
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

            // Graphique croisé
            const ctxCroise = document.getElementById('graphiqueClientsCroise').getContext('2d');
            new Chart(ctxCroise, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_map('ucfirst', $classes)) ?>,
                    datasets: [
                        {
                            label: 'Hommes',
                            data: <?= json_encode($dataHommes) ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Femmes',
                            data: <?= json_encode($dataFemmes) ?>,
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
                                text: 'Nombre de clients'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Classe'
                            }
                        }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw + ' clients';
                                }
                            }
                        }
                    }
                }
            });
        </script>
    <?php } ?>
</section>