<?php
require_once('../inc/fonctionTicket.php');
$tickets = getResolvedTickets();
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            background-color: #573d34;
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
                <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?= $ticket['idTicket'] ?></td>
                    <td><?= htmlspecialchars($ticket['prenom'] . ' ' . $ticket['nom']) ?></td>
                    <td><?= htmlspecialchars($ticket['sujet']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($ticket['dateCreation'])) ?></td>
                    <td>
                        <?php if ($ticket['hasFeedback']): ?>
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
            </tbody>
        </table>
    </div>
</body>
</html>