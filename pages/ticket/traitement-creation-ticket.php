<?php 
require_once '../../inc/fonctionTicket.php';
include('../../inc/fonctions.php');

// Récupération des données du formulaire
$sujet = $_POST['titre'];
$description = $_POST['description'];
$priorite = $_POST['priorite'];
$idClient = $_POST['id_client'];
$budgetPrevisionnel = isset($_POST['budget_previsionnel']) ? (float)$_POST['budget_previsionnel'] : null;

// Gestion du fichier
$resultatUpload = uploadFichier('file');
if ($resultatUpload['success']) {
    $nomFichier = $resultatUpload['filename']; 
} else {
    $nomFichier = null; 
}

// Insertion du ticket
$idTicket = insertTicket($idClient, $sujet, $description, $priorite, $nomFichier);

if ($idTicket) {
    // Insertion du budget prévisionnel associé
    $insertBudget = insertBudgetTicket($idTicket, $budgetPrevisionnel);

    if ($insertBudget) {
        header("Location: ../CRM-page.php?page=ticket/creation-ticket&success=Ticket créé avec succès");
        exit();
    } else {
        header("Location: ../CRM-page.php?page=ticket/creation-ticket&erreur=Erreur lors de l'enregistrement du budget.");
        exit();
    }
} else {
    header("Location: ../CRM-page.php?page=ticket/creation-ticket&erreur=" . urlencode($resultatUpload['message']));
    exit();
}
?>
