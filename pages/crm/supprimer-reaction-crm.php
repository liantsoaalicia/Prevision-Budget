<?php 
    include('../../inc/connection.php');
    include('../../inc/fonctionClient.php');
    include('../../inc/fonctionReactionRetour.php');

    if(isset($_GET['id'])) {
        $id = intval($_GET['id']);
        if(deleteReactionCRM($id)) {
            header('Location: ../CRM-page.php?page=crm/ajout-reaction-crm&success=Réaction supprimée');
        } else {
            header('Location: ../CRM-page.php?page=crm/ajout-reaction-crm&erreur=Erreur suppression');
        }
    } else {
        header('Location: ../CRM-page.php?page=crm/ajout-reaction-crm&erreur=ID manquant');
    }
    exit();
?>
