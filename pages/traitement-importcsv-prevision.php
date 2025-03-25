<?php 

    include('../inc/fonctionCSV.php');
    if(isset($_FILES['csvFile'])) {
        $csvFile = $_FILES['csvFile']['tmp_name'];  
        $tableName = "prevision";

        $success = importCsv($csvFile, $tableName);
        if($success) {
            header('Location: template.php?page=ajout-prevision&success=Prévision ajoutée avec succès');
            exit();
        } else {
            header('Location: template.php?page=ajout-prevision&erreur=Erreur lors de l\'ajout du prévision');
            exit();
        }
    }


?>