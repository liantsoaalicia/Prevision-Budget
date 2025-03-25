<?php 

    include('../inc/fonctionCSV.php');
    if(isset($_FILES['csvFile'])) {
        $csvFile = $_FILES['csvFile']['tmp_name'];  
        $tableName = "categorie";

        $success = importCsv($csvFile, $tableName);
        if($success) {
            header('Location: template.php?page=ajout-categorie&success=Catégorie ajoutée avec succès');
            exit();
        } else {
            header('Location: template.php?page=ajout-categorie&erreur=Erreur lors de l\'ajout de la catégorie');
            exit();
        }
    }


?>