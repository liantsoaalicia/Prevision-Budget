<?php 
    include('../../../inc/fonctionStatistique.php');
    $mois = $_POST['mois'];
    $sexe = $_POST['sexe'];
    $classe = $_POST['classe'];

    $stat1 = getStatistique1($mois, $sexe, $classe);
    

?>