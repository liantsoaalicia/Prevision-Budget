<?php

    include_once('../inc/fonctions.php');
    $nom = $_POST['nom'];
    $success = insertDepartement($nom);
    if($success) {
        header('Location: template.php?page=ajout-departement&success=Departement ajouté avec succès');
        exit();
    } else {
        header('Location: template.php?page=ajout-departement&erreur=Erreur lors de l\'ajout du département');
        exit();
    }

?>