<?php 

    include_once('../inc/fonctions.php');

    $idDepartement = $_POST['idDepartement'];
    $debut = $_POST['debut'];

    $success = insertSoldes($idDepartement, $debut);
    if($success) {
        header('Location:template.php?page=soldes&success=Solde ajoutée avec succès');
        exit();
    } else {
        header('Location:template.php?page=soldes&erreur=Erreur lors de l\'ajout de solde');
        exit();
    }
?>