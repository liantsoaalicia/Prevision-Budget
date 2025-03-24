<?php
    session_start();
    include('../inc/fonctionRandie.php');
    $idDepartement = $_SESSION['id'];
    $idPeriode = $_POST['idPeriode'];
    $idCategorie = $_POST['idCategorie'];
    $prevision = $_POST['prevision'];
    $realisation = $_POST['realisation'];
    $valide = 0;

    $success = ajoutPrevision($idDepartement, $idPeriode, $idCategorie, $prevision, $realisation, $valide);
    if($success) {
        header('Location:template.php?page=ajout-prevision&success=Prevision ajoutée avec succès');
        exit();
    } else {
        header('Location:template.php?page=ajout-prevision&erreur=Erreur lors de l\'ajout de prévision');
        exit();
    }

?>