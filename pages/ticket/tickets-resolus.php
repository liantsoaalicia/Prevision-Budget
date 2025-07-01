<?php
require_once('../inc/fonctionTicket.php');

$hasFeedback = $_GET['hasFeedback'] ?? null; // 'oui', 'non', ou null
$dateDebut = $_GET['dateDebut'] ?? null;    // format YYYY-MM-DD ou null
$dateFin = $_GET['dateFin'] ?? null;

$tickets = getResolvedTickets($hasFeedback, $dateDebut, $dateFin);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tickets Résolus</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #573d34;
            padding-bottom: 10px;
        }
        form {
            margin-bottom: 20px;
        }
        form label {
            font-weight: bold;
            margin-right: 5px;
        }
        form select, form input[type="date"] {
            padding: 5px;
            margin-right: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #573d34;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-primary {
            background-color: #573d34;
        }
        .btn-primary:hover {
            background-color: #6f4c3a;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tickets Résolus</h1>

        <form method="GET" action="CRM-page.php">
            <input type="hidden" name="page" value="ticket/tickets-resolus">
            <label for="hasFeedback">Feedback :</label>
            <select name="hasFeedback" id="hasFeedback">
                <option value="" <?= ($hasFeedback === null || $hasFeedback === '') ? 'selected' : '' ?>>Tous</option>
                <option value="oui" <?= ($hasFeedback === 'oui') ? 'selected' : '' ?>>Avec feedback</option>
                <option value="non" <?= ($hasFeedback === 'non') ? 'selected' : '' ?>>Sans feedback</option>
            </select>

            <label for="dateDebut">Date début :</label>
            <input type="date" id="dateDebut" name="dateDebut" value="<?= htmlspecialchars($dateDebut) ?>">

            <label for="dateFin">Date fin :</label>
            <input type="date" id="dateFin" name="dateFin" value="<?= htmlspecialchars($dateFin) ?>">

            <button type="submit" class="btn btn-primary">Filtrer</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Sujet</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tickets) === 0): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">Aucun ticket trouvé pour ces filtres.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?= $ticket['idTicket'] ?></td>
                        <td><?= htmlspecialchars($ticket['prenom'] . ' ' . $ticket['nom']) ?></td>
                        <td><?= htmlspecialchars($ticket['sujet']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($ticket['dateCreation'])) ?></td>
                        <td>
                            <?php if ($ticket['hasFeedback'] > 0): ?>
                                <a href="CRM-page.php?page=ticket/voir-retour&idTicket=<?= $ticket['idTicket'] ?>" class="btn btn-success">
                                    Voir retour
                                </a>
                            <?php else: ?>
                                <a href="CRM-page.php?page=ticket/ajout-retour-client&idTicket=<?= $ticket['idTicket'] ?>" class="btn btn-primary">
                                    Ajouter retour client
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
