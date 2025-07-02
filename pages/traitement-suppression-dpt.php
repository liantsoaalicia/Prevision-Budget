<?php
include_once("../inc/fonctions.php");

if(isset($_GET['id'])) {
    try {
        $resultat = supprimerDepartement($_GET['id']);
        if($resultat === true) {
            header('Location:template.php?page=listeDepartement');
            exit;
        }
    } catch(Exception $e) {
        $erreur = $e->getMessage();
    }
} else {
    $erreur = "Parametre manquant";
    header('Location:template.php?page=supprimerDepartement&erreur='.$erreur);
}

?>