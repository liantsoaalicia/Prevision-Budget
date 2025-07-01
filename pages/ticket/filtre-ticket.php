
<?php
// include('../../inc/fonctions.php');
include_once __DIR__ . '/../../inc/fonctionTicket.php';
include_once __DIR__ . '/../../inc/fonctionClient.php';

$clients = C_getAllClients();
$statuts = getAllStatusTicket();
$priorites = ['basse' => 'Basse', 'normale' => 'Normale', 'haute' => 'Haute'];

$filtre_client = isset($_GET['client']) ? $_GET['client'] : '';
$filtre_statut = isset($_GET['statut']) ? $_GET['statut'] : '';
$filtre_priorite = isset($_GET['priorite']) ? $_GET['priorite'] : '';

$con = dbConnect();
$where = [];
$params = [];
if ($filtre_client !== '') {
    $where[] = 'idClient = :client';
    $params[':client'] = $filtre_client;
}
if ($filtre_statut !== '') {
    $where[] = 'idStatus = :statut';
    $params[':statut'] = $filtre_statut;
}
if ($filtre_priorite !== '') {
    $where[] = 'priorite = :priorite';
    $params[':priorite'] = $filtre_priorite;
}
$sql = 'SELECT * FROM tickets';
if (count($where) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY idTicket DESC';
$stmt = $con->prepare($sql);
$stmt->execute($params);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<section id="filtre-ticket">
    <h2>Filtrer les tickets</h2>
    <form method="get" action="CRM-page.php" style="margin-bottom: 20px; display: flex; gap: 20px; align-items: flex-end;">
        <input type="hidden" name="page" value="ticket/filtre-ticket">
        <div>
            <label for="client">Client :</label>
            <select name="client" id="client">
                <option value="">Tous</option>
                <?php foreach ($clients as $c): ?>
                    <option value="<?= $c['idClient'] ?>" <?= ($filtre_client == $c['idClient']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nom'] . ' ' . $c['prenom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="statut">Statut :</label>
            <select name="statut" id="statut">
                <option value="">Tous</option>
                <?php foreach ($statuts as $s): ?>
                    <option value="<?= $s['idStatus'] ?>" <?= ($filtre_statut == $s['idStatus']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['libelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="priorite">Priorité :</label>
            <select name="priorite" id="priorite">
                <option value="">Toutes</option>
                <?php foreach ($priorites as $k => $v): ?>
                    <option value="<?= $k ?>" <?= ($filtre_priorite == $k) ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Filtrer</button>
    </form>

    <table border="1" style="width:100%; border-collapse:collapse;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sujet</th>
                <th>Description</th>
                <th>Client</th>
                <th>Statut</th>
                <th>Priorité</th>
                <th>Date création</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tickets)): ?>
                <tr><td colspan="7" style="text-align:center;">Aucun ticket trouvé</td></tr>
            <?php else: ?>
                <?php foreach ($tickets as $t): ?>
                    <tr>
                        <td><?= $t['idTicket'] ?></td>
                        <td><?= htmlspecialchars($t['sujet'] ?? '') ?></td>
                        <td><?= htmlspecialchars(substr($t['description'] ?? '', 0, 60)) ?><?= (strlen($t['description'] ?? '') > 60 ? '...' : '') ?></td>
                        <td>
                            <?php 
                            $cli = array_filter($clients, fn($c) => $c['idClient'] == $t['idClient']);
                            $cli = $cli ? reset($cli) : null;
                            echo $cli ? htmlspecialchars($cli['nom'] . ' ' . $cli['prenom']) : 'N/A';
                            ?>
                        </td>
                        <td>
                            <?php 
                            $st = array_filter($statuts, fn($s) => $s['idStatus'] == $t['idStatus']);
                            $st = $st ? reset($st) : null;
                            echo $st ? htmlspecialchars($st['libelle']) : 'N/A';
                            ?>
                        </td>
                        <td><?= htmlspecialchars(ucfirst($t['priorite'] ?? '')) ?></td>
                        <td><?= isset($t['dateCreation']) ? date('d/m/Y H:i', strtotime($t['dateCreation'])) : 'N/A' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>