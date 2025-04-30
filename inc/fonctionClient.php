<?php

function insertClient($prenom, $nom, $email, $age, $sexe, $classe, $dateInscription) {
    $con = dbConnect();
    $query = "INSERT INTO clients(prenom, nom, email, age, sexe, classe, dateInscription) 
              VALUES (:prenom, :nom, :email, :age, :sexe, :classe, :dateInscription)";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':age', $age, PDO::PARAM_INT);
    $stmt->bindParam(':sexe', $sexe, PDO::PARAM_STR);
    $stmt->bindParam(':classe', $classe, PDO::PARAM_STR);
    $stmt->bindParam(':dateInscription', $dateInscription, PDO::PARAM_STR); // Ajout de la date

    return $stmt->execute();
}



function C_getAllClients() {
    $con = dbConnect();
    $query = "SELECT * FROM clients ORDER BY nom";
    $stmt = $con->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getNouveauxClientsParMois($annee) {
    $con = dbConnect();

    $mois = [
        "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
        "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
    ];

    // Initialiser à 0
    $resultats = array_fill_keys($mois, 0);

    $sql = "SELECT MONTH(dateInscription) AS mois, COUNT(*) AS total
            FROM clients
            WHERE YEAR(dateInscription) = ?
            GROUP BY MONTH(dateInscription)";
    $stmt = $con->prepare($sql);
    $stmt->execute([$annee]);

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $moisNum = (int)$row['mois'];
        $resultats[$mois[$moisNum - 1]] = (int)$row['total'];
    }

    return $resultats;
}

?>
