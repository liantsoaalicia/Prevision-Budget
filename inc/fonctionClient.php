<?php

function insertClient($prenom, $nom, $email, $age, $sexe, $classe) {
    $con = dbConnect();
    $query = "INSERT INTO clients(prenom, nom, email, age, sexe, classe) 
              VALUES (:prenom, :nom, :email, :age, :sexe, :classe)";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':age', $age, PDO::PARAM_INT);
    $stmt->bindParam(':sexe', $sexe, PDO::PARAM_STR);
    $stmt->bindParam(':classe', $classe, PDO::PARAM_STR);

    return $stmt->execute();
}


function C_getAllClients() {
    $con = dbConnect();
    $query = "SELECT * FROM clients ORDER BY nom";
    $stmt = $con->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
