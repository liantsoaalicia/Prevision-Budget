<?php
    function ajouterEvenement($nomEvenement, $dateDebut, $dateFin) {
        $con = dbConnect();
    
        try {
            $query = "INSERT INTO evenement (nomEvenement, dateDebut, dateFin) VALUES (?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->execute([$nomEvenement, $dateDebut, $dateFin]);
    
            // Optionally return the ID of the inserted event
            return $con->lastInsertId();
    
        } catch (PDOException $e) {
            // Log error message if needed: error_log($e->getMessage());
            return false;
        }
    }
    
    function getAllEvenement() {
        $con = dbConnect();
        $query = "SELECT * FROM evenement";
        $stmt = $con->prepare($query);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

?>