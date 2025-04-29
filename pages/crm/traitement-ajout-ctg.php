<?php 

    include('../../inc/fonctionProduit.php');

    $nom = $_POST['nom'];
    $success = insertCategorieProduit($nom);
    if($success) {
        header('Location: ../CRM-page.php?page=crm/ajout-categorie-produit&success=Catégorie ajoutée avec succès');
        exit();
    } else {
        header('Location: ../CRM-page.php?page=crm/ajout-categorie-produit&erreur=Erreur lors de l\'ajout de la catégorie');
        exit();
    }

?>