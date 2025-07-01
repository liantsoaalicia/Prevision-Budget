<?php
include('../../inc/fonctionDiscussion.php');

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../statut-ticket.php?erreur=" . urlencode("Méthode non autorisée"));
    exit();
}

try {
    // Get POST data
    $idTicket = isset($_POST['idTicket']) ? intval($_POST['idTicket']) : 0;
    $idDiscussion = isset($_POST['idDiscussion']) ? intval($_POST['idDiscussion']) : 0;
    $idAgent = isset($_POST['idAgent']) ? intval($_POST['idAgent']) : 0;
    $idClient = isset($_POST['idClient']) ? intval($_POST['idClient']) : 0;
    $sender = isset($_POST['sender']) ? trim($_POST['sender']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Validate required fields
    
    if ($idDiscussion <= 0) {
        throw new Exception("ID de discussion invalide");
    }
    
    if (empty($message)) {
        throw new Exception("Le message ne peut pas être vide");
    }
    
    if (!in_array($sender, ['agent', 'client'])) {
        throw new Exception("Type d'expéditeur invalide");
    }
    
    // Prepare parameters for addDiscussionMessage function
    $auteur = $sender; // 'agent' or 'client'
    $clientParam = ($sender === 'client') ? $idClient : null;
    $agentParam = ($sender === 'agent') ? $idAgent : null;
    $fichier = null; // No file attachment for now
    
    // Use the existing function to add the message
    addDiscussionMessage($idDiscussion, $auteur, $message, $clientParam, $agentParam, $fichier);
    
    // Success - redirect back to discussion
    $successMessage = "Message envoyé avec succès";
    header("Location: discussion-ticket.php?idTicket=" . $idTicket . "&idAgent=" . $idAgent . "&idClient=" . $idClient . "&success=" . urlencode($successMessage));
    exit();
    
} catch (Exception $e) {
    // Error - redirect back with error message
    $errorMessage = "Erreur : " . $e->getMessage();
    $redirectUrl = "discussion-ticket.php?idTicket=" . ($idTicket ?? 0) . "&idAgent=" . ($idAgent ?? 0) . "&idClient=" . ($idClient ?? 0) . "&error=" . urlencode($errorMessage);
    header("Location: " . $redirectUrl);
    exit();
}
?>