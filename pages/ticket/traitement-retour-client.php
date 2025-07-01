<?php
include('../../inc/connection.php');
require_once('../../inc/fonctionTicket.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idTicket'])) {
    $idTicket = $_POST['idTicket'];
    $note = $_POST['note'];
    $commentaire = $_POST['commentaire'];
    $add = addClientFeedback($idTicket, $note, $commentaire);
    error_log("Tentative d'ajout pour ticket $idTicket, note $note");
    if ($add) {
        header('Location: ../CRM-page.php?page=ticket/tickets-resolus&success=1');
    } else {
        header('Location: ../CRM-page.php?page=ticket/ajout-retour-client&idTicket=' . $idTicket . '&error=1');
    }
    exit();
} else {
    error_log("Accès direct ou paramètres manquants");
    header('Location: ../CRM-page.php?page=ticket/tickets-resolus');
    exit();
}
?>