<?php 
    include('../inc/fonctionProduit.php');

    $nom = $_POST['nom'];
    $desc = $_POST['desc'];
    $idcategorie = $_POST['categorie'];
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];

    $success = insertProduit($nom, $desc, $idcategorie, $prix, $qte);
    if($success) {
        header('Location: CRM-page.php?page=crm/ajout-produit&success=Produit ajouté avec succès');
        exit();
    } else {
        header('Location: CRM-page.php?page=crm/ajout-produit&erreur=Erreur lors de l\'ajout du produit');
        exit();
    }

?>