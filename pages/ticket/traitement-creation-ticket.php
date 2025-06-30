<?php 
    require_once '../../inc/fonctionTicket.php';
    include('../../inc/fonctions.php');

    $sujet = $_POST['titre'];
    $description = $_POST['description'];
    $priorite = $_POST['priorite'];
    $idClient = $_POST['id_client'];

    $resultatUpload = uploadFichier('file');
    // var_dump($resultatUpload);
    if ($resultatUpload['success']) {
        $nomFichier = $resultatUpload['filename']; 
    } else {
        $nomFichier = null; 
    }

    $success = insertTicket($idClient, $sujet, $description, $priorite, $nomFichier);
    if ($success) {
        header("Location: ../CRM-page.php?page=ticket/creation-ticket&success=Ticket créé avec succès");
        exit();
    } else {
    header("Location: ../CRM-page.php?page=ticket/creation-ticket&erreur=" . urlencode($resultatUpload['message']));
        exit();
    }

?>