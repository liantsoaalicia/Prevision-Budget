<?php

include('../../inc/fonctionDiscussion.php');
    // Handle new discussion creation
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $idTicket = $_GET['idTicket'];
        $idAgent = $_GET['idAgent'];
        $idClient = $_GET['idClient'];
        try {
            // Here you would create a new discussion in database
            // For now, we'll simulate it
            addDiscussion($idAgent, $idClient, $idTicket);
            header("Location: discussion-ticket.php?idTicket=" . urlencode($idTicket) . "&idAgent=" . urlencode($idAgent) . "&idClient=" . urlencode($idClient) . "&success=" . urlencode("Discussion créée avec succès"));
            exit();
        } catch (Exception $e) {
            header("Location: discussion-ticket.php?idTicket=" . urlencode($idTicket) . "&idAgent=" . urlencode($idAgent) . "&idClient=" . urlencode($idClient) . "&error=" . urlencode("Erreur lors de la création : " . $e->getMessage()));
        }
    } else {
        header("Location: discussion-ticket.php?error=" . urlencode("Méthode non autorisée"));
        exit();
    }

?>