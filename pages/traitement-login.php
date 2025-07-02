<?php 

    session_start();
    include_once('../inc/fonctions.php');
    $nom = $_POST['nom'];
    $mdp = $_POST['mdp'];

    $log = login($nom, $mdp);
    var_dump($log);
    if($log[0] == 1) {
        $_SESSION['id'] = $log[1];
        header('Location:template.php?page=accueil');
        exit();
    } else {
        header('Location:login.php?error='.$log);
        exit();
    }

?>