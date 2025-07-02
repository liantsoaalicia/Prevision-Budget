<?php 

    include_once('../inc/fonctions.php');

    $categorie = $_POST['categorie'];
    $type = $_POST['type'];
    $nature = $_POST['nature'];

    $success = insertCategorie($categorie, $type, $nature);
    if($success) {
        header('Location: template.php?page=ajout-categorie&success=Catégorie ajoutée avec succès');
        exit();
    } else {
        header('Location: template.php?page=ajout-categorie&erreur=Erreur lors de l\'ajout de la catégorie');
        exit();
    }

?>