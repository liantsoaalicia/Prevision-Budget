<?php 
    require_once '../../inc/fonctionTicket.php';
    include('../../inc/fonctions.php');

    $idTicket = $_POST['id_ticket'];
    $idAgent = $_POST['id_agent'];

    $success = assignTicketToAgent($idAgent, $idTicket);
    $agent = getAgentById($idAgent);
     if ($success) {
        header("Location: ../CRM-page.php?page=ticket/assignation-ticket&success=Ticket assigné a l'agent". urlencode($agent['nom'] . ' ' . $agent['prenom']));
        exit();
    } else {
        header("Location: ../CRM-page.php?page=ticket/assignation-ticket&erreur=" . urlencode($resultatUpload['message']));
        exit();
    }

?>