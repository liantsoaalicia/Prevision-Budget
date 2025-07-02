<?php
session_start();
include('../../inc/fonctions.php');
include('../../inc/fonctionTicket.php');

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../CRM-page.php?page=ticket/filtre-ticket&erreur=" . urlencode("Méthode non autorisée"));
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../CRM-page.php?page=ticket/filtre-ticket&erreur=" . urlencode("Non autorisé"));
    exit();
}

try {
    // Get POST data
    $idTicket = isset($_POST['idTicket']) ? intval($_POST['idTicket']) : 0;
    $nouvellePriorite = isset($_POST['nouvellePriorite']) ? trim($_POST['nouvellePriorite']) : '';
    
    // Validate required fields
    if ($idTicket <= 0) {
        throw new Exception("ID de ticket invalide");
    }
    
    if (empty($nouvellePriorite)) {
        throw new Exception("La priorité ne peut pas être vide");
    }
    
    // Validate priority values
    $prioritesValides = ['basse', 'normale', 'haute'];
    if (!in_array($nouvellePriorite, $prioritesValides)) {
        throw new Exception("Priorité invalide");
    }
    
    // Get database connection
    $con = dbConnect();
    
    // Start transaction
    $con->beginTransaction();
    
    try {
        // First, get the current priority
        $getCurrentQuery = "SELECT priorite FROM tickets WHERE idTicket = :idTicket";
        $getCurrentStmt = $con->prepare($getCurrentQuery);
        $getCurrentStmt->bindParam(':idTicket', $idTicket, PDO::PARAM_INT);
        $getCurrentStmt->execute();
        $currentTicket = $getCurrentStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$currentTicket) {
            throw new Exception("Ticket non trouvé");
        }
        
        $anciennePriorite = $currentTicket['priorite'];
        
        // Check if priority is actually changing
        if ($anciennePriorite === $nouvellePriorite) {
            throw new Exception("La priorité est déjà à cette valeur");
        }
        
        // Update the ticket priority
        $updateQuery = "UPDATE tickets SET priorite = :priorite WHERE idTicket = :idTicket";
        $updateStmt = $con->prepare($updateQuery);
        $updateStmt->bindParam(':priorite', $nouvellePriorite, PDO::PARAM_STR);
        $updateStmt->bindParam(':idTicket', $idTicket, PDO::PARAM_INT);
        
        if (!$updateStmt->execute()) {
            throw new Exception("Erreur lors de la mise à jour de la priorité");
        }
        
        // Insert into history table
        $historyQuery = "INSERT INTO ticket_priority_history (idTicket, anciennePriorite, nouvellePriorite, commentaire) 
                         VALUES (:idTicket, :anciennePriorite, :nouvellePriorite, :commentaire)";
        $historyStmt = $con->prepare($historyQuery);
        $historyStmt->bindParam(':idTicket', $idTicket, PDO::PARAM_INT);
        $historyStmt->bindParam(':anciennePriorite', $anciennePriorite, PDO::PARAM_STR);
        $historyStmt->bindParam(':nouvellePriorite', $nouvellePriorite, PDO::PARAM_STR);
        // Optional comment for the change
        $commentaire = "Priorité modifiée de '$anciennePriorite' vers '$nouvellePriorite'";
        $historyStmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
        
        if (!$historyStmt->execute()) {
            throw new Exception("Erreur lors de l'enregistrement de l'historique");
        }
        
        // Commit transaction
        $con->commit();
        
        // Success - redirect back to filter page
        $successMessage = "Priorité du ticket #" . $idTicket . " modifiée avec succès (de '$anciennePriorite' vers '$nouvellePriorite')";
        header("Location: ../CRM-page.php?page=ticket/filtre-ticket&success=" . urlencode($successMessage));
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $con->rollBack();
        throw $e;
    }
    
} catch (Exception $e) {
    // Error - redirect back with error message
    $errorMessage = "Erreur : " . $e->getMessage();
    header("Location: ../CRM-page.php?page=ticket/filtre-ticket&erreur=" . urlencode($errorMessage));
    exit();
}
?>
