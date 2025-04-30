<?php 
    include('../../inc/connection.php');
    include('../../inc/fonctionClient.php');
    include('../../inc/fonctionReactionRetour.php');

    if(isset($_GET['id'])) {
        $id = intval($_GET['id']);
        if(deleteRetourClient($id)) {
            header('Location: ../CRM-page.php?page=crm/ajout-retour-client&success=Retour supprimé');
        } else {
            header('Location: ../CRM-page.php?page=crm/ajout-retour-client&erreur=Erreur suppression');
        }
    } else {
        header('Location: ../CRM-page.php?page=crm/ajout-retour-client&erreur=ID manquant');
    }
    exit();
?>
