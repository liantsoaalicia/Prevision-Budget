<?php
require_once('../inc/fonctionTicket.php');

if (!isset($_GET['idTicket'])) {
    header('Location: ../CRM-page.php?page=ticket/tickets-resolus');
    exit();
}

$idTicket = $_GET['idTicket'];
$feedback = getTicketFeedback($idTicket);
$client = getClientByTicket($idTicket);

if (!$feedback) {
    header('Location: ../CRM-page.php?page=ticket/tickets-resolus');
    exit();
}
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
        .client-info, .feedback-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .rating {
            font-size: 24px;
            color: #f39c12;
            margin: 10px 0;
        }
        .comment {
            padding: 15px;
            background-color: #e9f7ef;
            border-left: 4px solid #28a745;
            margin: 15px 0;
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
        }
        .btn:hover {
            background-color: #573d34;
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
        
        <div class="feedback-info">
            <h3>Évaluation du client</h3>
            <div class="rating">
                Note: <?= str_repeat('★', $feedback['note']) . str_repeat('☆', 5 - $feedback['note']) ?>
                (<?= $feedback['note'] ?>/5)
            </div>
            <p><strong>Date de l'évaluation:</strong> <?= date('d/m/Y H:i', strtotime($feedback['dateEvaluation'])) ?></p>
            
            <?php if (!empty($feedback['commentaire'])): ?>
                <h4>Commentaire:</h4>
                <div class="comment">
                    <?= nl2br(htmlspecialchars($feedback['commentaire'])) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <a href="CRM-page.php?page=ticket/tickets-resolus" class="btn">Retour à la liste</a>
    </div>
</body>
</html>