<?php

    include('../inc/fonctions.php');
    $idDepartement = $_POST['idDepartement'];
    $idPeriode = $_POST['idPerode'];
    $idCategorie = $_POST['idCategorie'];
    $montant = $_POST['montant'];
    $valide = 0;

    $success = ajoutPrevision($idDepartement, $idPeriode, $idCategorie, $montant, $valide);
    if($success) {
        header('Location:template.php?page=ajout-prevision&success=Prevision ajoutée avec succès');
        exit();
    } else {
        header('Location:template.php?page=ajout-prevision&erreur=Erreur lors de l\'ajout de prévision');
        exit();
    }

?>