<?php
    include('../../inc/fonctions.php');

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if (validerAction($id)) {
        header('Location: ../CRM-page.php?page=crm/valider-action-crm&success=Action validee avec succes');
    } else {
        header('Location: ../CRM-page.php?page=crm/valider-action-crm&erreur=Échec de la validation');
    }
    exit;
} else {
    header('Location: ../CRM-page.php?page=crm/valider-action-crm&erreur=ID manquant');
    exit;
}
