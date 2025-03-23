<?php 
    include('../inc/connection.php');

    function getAllDepartements() {
        $con = dbConnect();
        $query = "SELECT * FROM departement";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $departements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $departements;
    }

    function login($idDepartement, $mdp) {
        $con = dbConnect();
        $query = "SELECT * FROM departement WHERE idDepartement = :idDepartement";
        $stmt = $con->prepare($query);
        $stmt->bindParam('idDepartement', $idDepartement, PDO::PARAM_INT);
        $stmt->execute();
        $departement = $stmt->fetch(PDO::FETCH_ASSOC);

        if($departement) {
            if($mdp == $departement['mdp']) {
                return 1;
            } else {
                return "Mot de passe incorrect";
            }
        }

        return "Identifiant incorrect";
    }

?>