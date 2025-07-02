<?php
require_once('../inc/connection.php');

$annee = isset($_GET['annee']) ? (int)$_GET['annee'] : date("Y");

function getStatsBudgetPrevisionVsReel($annee) {
    $con = dbConnect();

    $sql = "
        SELECT 
            MONTH(t.dateCreation) AS mois,
            SUM(bt.budgetPrevisionnel) AS totalPrevu,
            SUM(bt.coutReel) AS totalReel
        FROM budget_ticket bt
        JOIN tickets t ON bt.idTicket = t.idTicket
        WHERE YEAR(t.dateCreation) = :annee AND bt.valideFinance = 1
        GROUP BY mois
        ORDER BY mois
    ";

    $stmt = $con->prepare($sql);
    $stmt->bindParam(':annee', $annee, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$dataRows = getStatsBudgetPrevisionVsReel($annee);

$moisLabels = [1=>'Janvier', 2=>'Février', 3=>'Mars', 4=>'Avril', 5=>'Mai', 6=>'Juin',
               7=>'Juillet', 8=>'Août', 9=>'Septembre', 10=>'Octobre', 11=>'Novembre', 12=>'Décembre'];

$labels = [];
$dataPrevus = [];
$dataReels = [];
$totalPrevu = 0;
$totalReel = 0;

foreach ($moisLabels as $mois => $nom) {
    $labels[] = $nom;
    $row = array_filter($dataRows, fn($r) => (int)$r['mois'] === $mois);
    if (!empty($row)) {
        $r = array_values($row)[0];
        $prevu = (float)$r['totalPrevu'];
        $reel = (float)$r['totalReel'];
    } else {
        $prevu = 0;
        $reel = 0;
    }
    $dataPrevus[] = $prevu;
    $dataReels[] = $reel;
    $totalPrevu += $prevu;
    $totalReel += $reel;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Statistiques Budgets - Année <?= htmlspecialchars($annee) ?></title>
    <script src="../assets/js/chart.umd.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        form { margin-bottom: 30px; }
        .chart-container { max-width: 1000px; margin: auto; }
        .stats-text { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Statistiques Budgets Tickets - Année <?= htmlspecialchars($annee) ?></h1>

    <form method="get" action="CRM-page.php">
        <input type="hidden" name="page" value="ticket/statistique-prevision-vs-reel">
        <label for="annee">Choisir une année :</label>
        <input type="number" id="annee" name="annee" min="2000" max="<?= date('Y') ?>" value="<?= htmlspecialchars($annee) ?>" required>
        <button type="submit">Afficher</button>
    </form>

    <?php if ($totalPrevu === 0 && $totalReel === 0): ?>
        <p style="color: red; font-weight: bold;">Aucune donnée validée pour l'année <?= htmlspecialchars($annee) ?>.</p>
    <?php else: ?>
        <div class="chart-container">
            <canvas id="barChart"></canvas>
            <div class="stats-text">
                <p>Total Prévisionnel : <strong><?= number_format($totalPrevu, 2, ',', ' ') ?> Ar</strong></p>
                <p>Total Réel : <strong><?= number_format($totalReel, 2, ',', ' ') ?> Ar</strong></p>
            </div>
        </div>

        <script>
            const ctx = document.getElementById('barChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels) ?>,
                    datasets: [
                        {
                            label: 'Prévisionnel',
                            data: <?= json_encode($dataPrevus) ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Réel',
                            data: <?= json_encode($dataReels) ?>,
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
                                text: 'Montant en Ariary'
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Prévisions vs Réels par mois'
                        }
                    }
                }
            });
        </script>
    <?php endif; ?>
</body>
</html>
