<?php

    include('../inc/fonctions.php');

    if(isset($_GET['id'])) {
        $success = validerPrevision($_GET['id']);

        if($success) {
            header('Location: template.php?page=valider-prevision&success=Prevision validée');
            exit();
        } else {
            header('Location: template.php?page=valider-prevision&erreur=Erreur de la validation de la prevision');
            exit();
        }
    }

?>