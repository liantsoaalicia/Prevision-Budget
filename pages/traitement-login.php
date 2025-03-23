<?php 

    include('../inc/fonctions.php');
    $id = $_POST['idDepartement'];
    $mdp = $_POST['mdp'];

    $log = login($id, $mdp);
    if($log == 1) {
        $_SESSION['id'] = $id;
        header('Location:template.php?page=accueil');
        exit();
    } else {
        header('Location:login.php?error='.$log);
        exit();
    }

?>