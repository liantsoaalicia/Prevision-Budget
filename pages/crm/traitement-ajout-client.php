<?php
include('../../inc/connection.php');
include('../../inc/fonctionClient.php');

$prenom = $_POST['prenom'];
$nom = $_POST['nom'];
$email = $_POST['email'];
$age = $_POST['age'];
$sexe = $_POST['sexe'];
$classe = $_POST['classe'];
$dateInscription = $_POST['dateInscription']; 

$success = insertClient($prenom, $nom, $email, $age, $sexe, $classe, $dateInscription);
if ($success) {
    header('Location: ../CRM-page.php?page=crm/ajout-client&success=Client ajouté avec succès');
    exit();
} else {
    header('Location: ../CRM-page.php?page=crm/ajout-client&erreur=Erreur lors de l\'ajout du client');
    exit();
}
?>
