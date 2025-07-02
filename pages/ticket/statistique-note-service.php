<?php
require_once('../inc/connection.php'); 

$annee = isset($_GET['annee']) ? (int)$_GET['annee'] : date("Y");

function getStatsEvaluationTickets($annee) {
    $con = dbConnect();


    $sql = "
        SELECT note, COUNT(*) AS nombre
        FROM evaluation_ticket e
        JOIN tickets t ON e.idTicket = t.idTicket
        INNER JOIN budget_ticket bt ON t.idTicket = bt.idTicket
        WHERE YEAR(e.dateEvaluation) = :annee
        AND bt.valideFinance = 1
        GROUP BY note
        ORDER BY note
    ";

    $stmt = $con->prepare($sql);
    $stmt->bindParam(':annee', $annee, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stats = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    foreach ($result as $row) {
        $stats[(int)$row['note']] = (int)$row['nombre'];
    }

    return $stats;
}

$stats = getStatsEvaluationTickets($annee);

$total = array_sum($stats);
$labels = array_map(fn($n) => "Note $n", array_keys($stats));
$data = array_values($stats);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Statistiques des évaluations tickets - Année <?= htmlspecialchars($annee) ?></title>
    <script src="../assets/js/chart.umd.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        form { margin-bottom: 30px; }
        .chart-container { max-width: 500px; margin: auto; }
        .stats-text { text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
    <h1>Statistiques des évaluations des tickets - Année <?= htmlspecialchars($annee) ?></h1>

    <form method="get" action="CRM-page.php">
        <input type="hidden" name="page" value="ticket/statistique-note-service">
        <label for="annee">Choisir une année :</label>
        <input type="number" id="annee" name="annee" min="2000" max="<?= date('Y') ?>" value="<?= htmlspecialchars($annee) ?>" required>
        <button type="submit">Afficher</button>
    </form>

    <?php if ($total === 0): ?>
        <p style="color: red; font-weight: bold;">Aucune évaluation enregistrée pour l'année <?= htmlspecialchars($annee) ?>.</p>
    <?php else: ?>
        <div class="chart-container">
            <canvas id="pieChart"></canvas>
            <div class="stats-text">
                <p>Total évaluations : <strong><?= $total ?></strong></p>
                <?php foreach ($stats as $note => $count): 
                    $pct = $total > 0 ? round($count * 100 / $total, 2) : 0;
                ?>
                    <p>Note <?= $note ?> : <?= $count ?> (<?= $pct ?>%)</p>
                <?php endforeach; ?>
            </div>
        </div>

        <script>
            const ctx = document.getElementById('pieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: <?= json_encode($labels) ?>,
                    datasets: [{
                        data: <?= json_encode($data) ?>,
                        backgroundColor: [
                            '#FF6384', // rouge
                            '#36A2EB', // bleu
                            '#FFCE56', // jaune
                            '#4BC0C0', // cyan
                            '#9966FF'  // violet
                        ],
                        borderColor: '#fff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                font: { size: 14 }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a,b) => a+b, 0);
                                    const percent = total ? (value*100/total).toFixed(2) : 0;
                                    return `${label} : ${value} (${percent}%)`;
                                }
                            }
                        }
                    }
                }
            });
        </script>
    <?php endif; ?>
</body>
</html>
