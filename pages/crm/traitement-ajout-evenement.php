<?php
include("../../inc/connection.php");

function ajouterEvenement($nomEvenement, $dateDebut, $dateFin) {
    $con = dbConnect();

    try {
        $query = "INSERT INTO evenement (nomEvenement, dateDebut, dateFin) VALUES (?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->execute([$nomEvenement, $dateDebut, $dateFin]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Récupération des données POST
$nomEvenement = $_POST['nomEvenement'] ?? null;
$dateDebut = $_POST['dateDebut'] ?? null;
$dateFin = $_POST['dateFin'] ?? null;

// Validation
if (empty($nomEvenement) || empty($dateDebut) || empty($dateFin)) {
    header("Location: ../CRM-page.php?page=crm/ajout-evenement&erreur=Veuillez remplir tous les champs");
    exit;
}

if ($dateDebut > $dateFin) {
    header("Location: ../CRM-page.php?page=crm/ajout-evenement&erreur=La date de début doit être avant la date de fin");
    exit;
}

// Appel de la fonction d'insertion
$success = ajouterEvenement($nomEvenement, $dateDebut, $dateFin);

if ($success) {
    header("Location: ../CRM-page.php?page=crm/ajout-evenement&success=Événement ajouté avec succès");
} else {
    header("Location: ../CRM-page.php?page=crm/ajout-evenement&erreur=Erreur lors de l'ajout de l'événement");
}
exit;
?>
