<?php
include_once __DIR__ . '/../../inc/fonctionTicket.php';
include_once __DIR__ . '/../../inc/connection.php';

$con = dbConnect();

// Tickets ouverts par semaine (création)
$sql_ouverts = "SELECT YEAR(dateCreation) as annee, WEEK(dateCreation, 1) as semaine, COUNT(*) as nb_ouverts
FROM tickets
GROUP BY annee, semaine
ORDER BY annee DESC, semaine DESC
LIMIT 10";
$ouverts = $con->query($sql_ouverts)->fetchAll(PDO::FETCH_ASSOC);

// Tickets fermés par semaine (statut 5 = fermé)
$sql_fermes = "SELECT YEAR(dateChangement) as annee, WEEK(dateChangement, 1) as semaine, COUNT(*) as nb_fermes
FROM ticket_status_history
WHERE idStatus = 5
GROUP BY annee, semaine
ORDER BY annee DESC, semaine DESC
LIMIT 10";
$fermes = $con->query($sql_fermes)->fetchAll(PDO::FETCH_ASSOC);

// Indexation par annee+semaine pour fusionner les deux tableaux
// Indexation par annee+semaine pour accès rapide
$data = [];
foreach ($ouverts as $o) {
    $data[$o['annee'].'-'.$o['semaine']]['ouverts'] = $o['nb_ouverts'];
}
foreach ($fermes as $f) {
    $data[$f['annee'].'-'.$f['semaine']]['fermes'] = $f['nb_fermes'];
}

// Déterminer la semaine courante et afficher les 12 dernières semaines
$now = new DateTime();
$currentYear = (int)$now->format('Y');
$currentWeek = (int)$now->format('W');
$stats = [];
for ($i = 11; $i >= 0; $i--) {
    $dt = clone $now;
    $dt->modify("-{$i} week");
    $year = (int)$dt->format('Y');
    $week = (int)$dt->format('W');
    $key = $year.'-'.$week;
    // Calculer la date de début et de fin de la semaine ISO
    $date_debut = new DateTime();
    $date_debut->setISODate($year, $week);
    $date_fin = clone $date_debut;
    $date_fin->modify('+6 days');
    $stats[] = [
        'annee' => $year,
        'semaine' => $week,
        'debut' => $date_debut->format('d/m/Y'),
        'fin' => $date_fin->format('d/m/Y'),
        'ouverts' => isset($data[$key]['ouverts']) ? $data[$key]['ouverts'] : 0,
        'fermes' => isset($data[$key]['fermes']) ? $data[$key]['fermes'] : 0
    ];
}
?>
<section id="rapport-performance">
    <h2>Statistiques des tickets ouverts / fermés par semaine (12 semaines)</h2>
    <table border="1" style="width: 60%; margin: 20px auto; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Année</th>
                <th>Semaine</th>
                <th>Période</th>
                <th>Tickets ouverts</th>
                <th>Tickets fermés</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stats as $s): ?>
                <tr>
                    <td><?= $s['annee'] ?></td>
                    <td><?= $s['semaine'] ?> e</td>
                    <td><?= $s['debut'] ?> - <?= $s['fin'] ?></td>
                    <td><?= isset($s['ouverts']) ? $s['ouverts'] : 0 ?></td>
                    <td><?= isset($s['fermes']) ? $s['fermes'] : 0 ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
