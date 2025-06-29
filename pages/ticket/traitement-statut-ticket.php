<?php
session_start();
include("../../inc/connection.php");
include('../../inc/fonctionTicket.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idTicket = $_POST['idTicket'];
    $newStatus = $_POST['newStatus'];
    $commentaire = isset($_POST['commentaire']) ? $_POST['commentaire'] : '';
    
    try {
        updateTicketStatus($idTicket, $newStatus, $commentaire);
        header("Location: ../CRM-page.php?page=ticket/statut-ticket&success=" . urlencode("Statut du ticket mis à jour avec succès"));
        exit();
    } catch (Exception $e) {
        header("Location: ../CRM-page.php?page=ticket/statut-ticket&erreur=" . urlencode("Erreur : " . $e->getMessage()));
        exit();
    }
} else {
    header("Location: ../CRM-page.php?page=ticket/statut-ticket&erreur=" . urlencode("Méthode de requête non autorisée"));
    exit();
}
?>
