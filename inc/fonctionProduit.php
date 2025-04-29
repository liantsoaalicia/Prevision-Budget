<?php 

    include("connection.php");

    function insertCategorieProduit($nom) {
        $con = dbConnect();
        $query = "INSERT INTO categorieProduit(nom) VALUES (:nom)";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        if($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function insertProduit($nom ,$desc, $idcategorie, $prix, $qte) {
        $con = dbConnect();
        $query = "INSERT INTO produits(nom, description, idCategorie, prix, quantiteStock) VALUES 
        (:nom, :desc, :idcategorie, :prix, :qt)";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':desc', $desc, PDO::PARAM_STR);
        $stmt->bindParam(':idcategorie', $idcategorie, PDO::PARAM_INT);
        $stmt->bindParam(':prix', $prix, PDO::PARAM_STR);
        $stmt->bindParam(':qt', $qte, PDO::PARAM_STR);

        if($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

?>