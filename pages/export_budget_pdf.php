<?php

include_once '../inc/fonctions.php'; 

session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ../pages/login.php');
    exit;
}

if (!isset($_GET['idDepartement'])) {
    die('Erreur: ID de département manquant');
}

$idDepartement = $_GET['idDepartement'];

$isItFinance = verifyIfFinance($_SESSION['id']);
$userDept = getDepartementById($_SESSION['id']);

if (!$isItFinance && $userDept['idDepartement'] != $idDepartement) {
    die('Accès non autorisé à ce département');
}

require_once '../inc/fonctionPDF.php';

exportBudgetPDF($idDepartement);
?>