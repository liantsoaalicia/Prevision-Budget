<?php
    include('../../inc/fonctions.php');

if (isset($_GET['id'])) {

    if (validerAction($_GET['id'])) {
        // echo 'OK';
        header('Location: ../CRM-page.php?page=crm/valider-action-crm&success=Action validee avec succes');
    } 
    else {
        // echo 'NON';
        header('Location: ../CRM-page.php?page=crm/valider-action-crm&erreur=Échec de la validation');
    }
    exit;
} else {
    // echo 'else';
    header('Location: ../CRM-page.php?page=crm/valider-action-crm&erreur=ID manquant');
    exit;
}
