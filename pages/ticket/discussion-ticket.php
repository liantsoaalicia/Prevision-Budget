<?php
    include('../../inc/fonctionDiscussion.php');
    include('../../inc/fonctionTicket.php');

    // Check if ticket ID is provided
    if (!isset($_GET['idTicket'])) {
        header("Location: statut-ticket.php?erreur=" . urlencode("ID de ticket manquant"));
        exit();
    }
    
    $idTicket = $_GET['idTicket'];
    $idAgent = $_GET['idAgent'] ?? null;
    $idClient = $_GET['idClient'];
    
    // Get ticket details
    $tickets = getAllTickets();
    $currentTicket = null;
    foreach ($tickets as $ticket) {
        if ($ticket['idTicket'] == $idTicket) {
            $currentTicket = $ticket;
            break;
        }
    }
    
    if (!$currentTicket) {
        header("Location: statut-ticket.php?erreur=" . urlencode("Ticket non trouvé"));
        exit();
    }
    
    // Check if discussion exists
    $discussionExists = checkDiscussionExists($idTicket);
    $canCreateDiscussion = (!$discussionExists && $idAgent != 0);
    $messages = null;
    if ($discussionExists) {
        $idDiscussion = getIdDiscussion($idTicket);
        error_log("idDiscussion: ".$idDiscussion);
        $messages = getDiscussion($idDiscussion);
        error_log("message Size: ".count($messages));
        error_log(print_r($messages, true));
    }
    else {
        $idDiscussion = null;
    }
    
    // Mock messages for demonstration (you would get these from database)
    /*$messages = [
        [
            'id' => 1,
            'sender' => 'client',
            'message' => 'Bonjour, j\'ai un problème avec ma commande.',
            'timestamp' => '2025-06-29 10:30:00'
        ],
        [
            'id' => 2,
            'sender' => 'agent',
            'message' => 'Bonjour, je vais regarder votre problème immédiatement. Pouvez-vous me donner plus de détails ?',
            'timestamp' => '2025-06-29 10:35:00'
        ],
        [
            'id' => 3,
            'sender' => 'client',
            'message' => 'Ma commande n\'est pas arrivée à la bonne adresse.',
            'timestamp' => '2025-06-29 10:40:00'
        ]
    ];*/
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion - Ticket #<?= $idTicket ?></title>
    <link rel="stylesheet" href="../../assets/style.css">
    <style>
        .discussion-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .ticket-header {
            background: linear-gradient(135deg, #8D6E63, #5D4037);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin: -20px -20px 20px -20px;
        }
        
        .ticket-header h1 {
            margin: 0;
            font-size: 24px;
        }
        
        .ticket-info {
            margin-top: 10px;
            opacity: 0.9;
        }
        
        .messages-container {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background: #f9f9f9;
        }
        
        .message {
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 8px;
            max-width: 70%;
            word-wrap: break-word;
        }
        
        .message.client {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            margin-left: 0;
            margin-right: auto;
        }
        
        .message.agent {
            background: #e8f5e8;
            border-left: 4px solid #4caf50;
            margin-left: auto;
            margin-right: 0;
        }
        
        .message-header {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .message-time {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        
        .message-form {
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        
        .form-group select {
            width: 200px;
            padding: 8px;
            border: 2px solid #ddd;
            border-radius: 4px;
            background: white;
        }
        
        .form-group textarea {
            width: 100%;
            min-height: 100px;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }
        
        .form-group textarea:focus {
            outline: none;
            border-color: #8D6E63;
        }
        
        .btn-send {
            background: linear-gradient(135deg, #8D6E63, #5D4037);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        
        .btn-send:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(141, 110, 99, 0.3);
        }
        
        .btn-back {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        .btn-back:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
        }
        
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: bold;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .no-messages {
            text-align: center;
            color: #888;
            font-style: italic;
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="discussion-container">
        <a href="../CRM-page.php?page=ticket/statut-ticket" class="btn-back">← Retour aux tickets</a>
        
        <div class="ticket-header">
            <h1>💬 Discussion - Ticket #<?= $idTicket ?></h1>
            <div class="ticket-info">
                <strong>Description:</strong> <?= htmlspecialchars($currentTicket['description'] ?? 'N/A') ?><br>
                <strong>Date création:</strong> <?= isset($currentTicket['dateCreation']) ? date('d/m/Y H:i', strtotime($currentTicket['dateCreation'])) : 'N/A' ?>
            </div>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($discussionExists == 0): ?>
            <?php if ($canCreateDiscussion): ?>
                <div style="text-align: center; padding: 40px;">
                    <h3>Aucune discussion n'existe pour ce ticket</h3>
                    <p>Vous pouvez créer une nouvelle discussion avec le client.</p>
                    <form method="POST" style="display: inline;">
                        <a href="traitement-add-discussion.php?idTicket=<?= urlencode($idTicket) ?>&idAgent=<?= urlencode($idAgent) ?>&idClient=<?= urlencode($idClient) ?>" class="btn-send" style="font-size: 18px; padding: 15px 30px; display: inline-block; text-align: center; text-decoration: none;">
                            🆕 Créer une nouvelle discussion
                        </a>
                    </form>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 40px; color: #888;">
                    <h3>Aucune discussion disponible</h3>
                    <p>
                        <?php if ($idAgent == 0): ?>
                            Un agent doit être assigné à ce ticket pour créer une discussion.
                        <?php else: ?>
                            Aucune discussion n'a été initiée pour ce ticket.
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="messages-container" id="messagesContainer">
                <?php if (empty($messages)): ?>
                    <div class="no-messages">
                        Aucun message dans cette discussion. Commencez la conversation !
                    </div>
                <?php else: ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="message <?= $message['auteur'] ?>">
                            <div class="message-header">
                                <?= $message['auteur'] === 'client' ? '👤 Client' : '🛠️ Agent Support' ?>
                            </div>
                            <div class="message-content">
                                <?= htmlspecialchars($message['message']) ?>
                            </div>
                            <div class="message-time">
                                <?= date('d/m/Y H:i', strtotime($message['dateMessage'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <form method="POST" class="message-form" action="traitement-add-message.php">
                <input type="hidden" name="idDiscussion" value="<?= $idDiscussion ?>">
                <input type="hidden" name="idAgent" value="<?= $idAgent ?>">
                <input type="hidden" name="idClient" value="<?= $idClient ?>">
                <input type="hidden" name="idTicket" value="<?= $idTicket ?>">
                <div class="form-group">
                    <label for="sender">Envoyer en tant que:</label>
                    <select name="sender" id="sender" required>
                        <option value="agent" selected>🛠️ Agent Support</option>
                        <option value="client">👤 Client</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">Votre message:</label>
                    <textarea name="message" id="message" placeholder="Tapez votre message ici..." required></textarea>
                </div>
                
                <button type="submit" name="send_message" class="btn-send">📤 Envoyer le message</button>
            </form>
            <div class="form-group" style="text-align: right;">
                <a href="traitement-resoudre-ticket.php?idTicket=<?= urlencode($idTicket) ?>&idAgent=<?= urlencode($idAgent) ?>&idClient=<?= urlencode($idClient) ?>" class="btn-send" style="background: #388e3c; margin-bottom: 15px; display: inline-block; text-align: center; text-decoration: none;">
                    ✅ Résoudre le ticket
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        <?php if ($discussionExists != 0): ?>
        // Auto-scroll to bottom of messages
        const messagesContainer = document.getElementById('messagesContainer');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        // Auto-focus on message textarea
        const messageTextarea = document.getElementById('message');
        if (messageTextarea) {
            messageTextarea.focus();
        }
        
        // Auto-refresh every 30 seconds to check for new messages
        setInterval(function() {
            location.reload();
        }, 30000);
        
        // Handle Enter key to send message (Ctrl+Enter)
        if (messageTextarea) {
            messageTextarea.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'Enter') {
                    document.querySelector('form').submit();
                }
            });
        }
        <?php endif; ?>
    </script>
</body>
</html>
