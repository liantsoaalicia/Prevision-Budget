<?php
    include("connection.php");

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
    
    function listerDepartements() {
        try {
            $pdo = dbConnect();
            
            $sql = "SELECT idDepartement, nom FROM departement ORDER BY nom";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    function supprimerDepartement($idDepartement) {
        try {
            $pdo = dbConnect();
            
            $sql = "SELECT COUNT(*) as count FROM departement WHERE idDepartement = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$idDepartement]);
            $result = $stmt->fetch();
            
            if ($result['count'] == 0) {
                throw new Exception("Le département n'existe pas");
            }
            
            $sql = "SELECT COUNT(*) as count FROM prevision WHERE idDepartement = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$idDepartement]);
            $result = $stmt->fetch();
            
            if ($result['count'] > 0) {
                throw new Exception("Impossible de supprimer ce département car il contient des prévisions");
            }
            
            $sql = "DELETE FROM departement WHERE idDepartement = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$idDepartement]);
            
            return true;
        } catch(PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
?>