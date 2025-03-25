<?php 

    include('../inc/fonctionCSV.php');
    if(isset($_FILES['csvFile'])) {
        $csvFile = $_FILES['csvFile']['tmp_name'];  
        $tableName = "solde";

        $success = importCsv($csvFile, $tableName);
        if($success) {
            header('Location: template.php?page=soldes&success=Solde ajoutée avec succès');
            exit();
        } else {
            header('Location: template.php?page=soldes&erreur=Erreur lors de l\'ajout du solde');
            exit();
        }
    }


?>