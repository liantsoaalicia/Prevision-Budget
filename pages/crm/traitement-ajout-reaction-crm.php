<?php 
    include('../../inc/connection.php');
    include('../../inc/fonctionClient.php');
    include('../../inc/fonctionReactionRetour.php');

    $idClient = $_POST['idClient'];
    $reaction = $_POST['reaction'];

    if(insertReactionCRM($idClient, $reaction)) {
        header('Location: ../CRM-page.php?page=crm/ajout-reaction-crm&success=Reaction ajoutee avec succes');
    } else {
        header('Location: ../CRM-page.php?page=crm/ajout-reaction-crm&erreur=Erreur lors de l\'ajout');
    }
    exit();
?>
