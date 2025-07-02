<?php
require_once('../inc/fonctionTicket.php');

if (!isset($_GET['idTicket'])) {
    header('Location: ../CRM-page.php?page=ticket/tickets-resolus');
    exit();
}

$idTicket = $_GET['idTicket'];
$client = getClientByTicket($idTicket);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Retour Client</title>
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
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #573d34;
            padding-bottom: 10px;
            margin-top: 0;
        }
        .client-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        textarea {
            min-height: 100px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #573d34;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        .btn:hover {
            background-color: #573d34;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Retour Client pour le Ticket #<?= $idTicket ?></h1>
        <div class="client-info">
            <p><strong>Client:</strong> <?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($client['email']) ?></p>
        </div>
        
        <form method="post" action="ticket/traitement-retour-client.php">
            <input type="hidden" name="idTicket" value="<?= htmlspecialchars($idTicket) ?>">

            <div class="form-group">
                <label for="note">Note (1-5):</label>
                <select id="note" name="note" required>
                    <option value="">Choisir une note</option>
                    <option value="1">1 - Très mauvais</option>
                    <option value="2">2 - Mauvais</option>
                    <option value="3">3 - Moyen</option>
                    <option value="4">4 - Bon</option>
                    <option value="5">5 - Excellent</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="commentaire">Commentaire:</label>
                <textarea id="commentaire" name="commentaire"></textarea>
            </div>
            
            <button type="submit" class="btn">Envoyer le retour</button>
            <a href="../CRM-page.php?page=ticket/tickets-resolus" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>
