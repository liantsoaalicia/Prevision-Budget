<?php 
    include('../../inc/connection.php');
    include('../../inc/fonctionClient.php');
    include('../../inc/fonctionReactionRetour.php');

    $idCommande = $_POST['idCommande'];
    $avis = $_POST['avis'];
    $commentaire = $_POST['commentaire'];

    $commandes = getCommandesClients();
    $idClient = null;
    foreach($commandes as $cmd) {
        if($cmd['idCommande'] == $idCommande) {
            $idClient = $cmd['idClient'];
            break;
        }
    }

    if($idClient !== null && insertRetourClient($idClient, $idCommande, $avis, $commentaire)) {
        header('Location: ../CRM-page.php?page=crm/ajout-retour-client&success=Retour ajouté avec succès');
    } else {
        header('Location: ../CRM-page.php?page=crm/ajout-retour-client&erreur=Erreur lors de l\'ajout');
    }
    exit();
?>
