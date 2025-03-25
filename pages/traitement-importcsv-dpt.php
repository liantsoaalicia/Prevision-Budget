<?php 

    include('../inc/fonctionCSV.php');
    if(isset($_FILES['csvFile'])) {
        $csvFile = $_FILES['csvFile']['tmp_name'];  
        $tableName = "departement";

        $success = importCsv($csvFile, $tableName);
        if($success) {
            header('Location: login.php?success=Département ajouté avec succès');
            exit();
        } else {
            header('Location: login.php?erreur=Erreur lors de l\'ajout du département');
            exit();
        }
    }


?>