<?php
    session_start();
    include('../../inc/connection.php');
    include('../../inc/fonctionClient.php');
    include('../../inc/fonctionReactionRetour.php');

if (
    isset($_POST['typeAction'], $_POST['etapeAction'],
          $_POST['dateAction'], $_POST['coutsPrevision'], $_POST['coutsRealisation'])
) {
    $typeAction = $_POST['typeAction'];
    $etapeAction = $_POST['etapeAction'];
    $dateAction = $_POST['dateAction'];
    $coutsPrevision = $_POST['coutsPrevision'];
    $coutsRealisation = $_POST['coutsRealisation'];

    $res = insertActionCrm($typeAction, $etapeAction, $dateAction, $coutsPrevision, $coutsRealisation);

    if ($res) {
        header("Location: ../CRM-page.php?page=crm/ajout-action-crm&success=Action CRM ajoutée avec succès");
    } else {
        header("Location: ../CRM-page.php?page=crm/ajout-action-crm.php&erreur=Échec de l'ajout");
    }
} else {
    header("Location: ../CRM-page.php?page=crm/ajout-action-crm.php&erreur=Champs manquants");
}
?>
