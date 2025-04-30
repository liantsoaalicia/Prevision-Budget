<?php

function insertReactionCRM($idClient, $reaction) {
    $con = dbConnect();
    $query = "INSERT INTO reactionActionCrm(idClient, reaction) VALUES (:idClient, :reaction)";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':idClient', $idClient, PDO::PARAM_INT);
    $stmt->bindParam(':reaction', $reaction, PDO::PARAM_STR);
    return $stmt->execute();
}

function getAllReactionsCRM() {
    $con = dbConnect();
    $query = "SELECT r.idReaction, r.reaction, c.prenom, c.nom 
              FROM reactionActionCrm r 
              JOIN clients c ON r.idClient = c.idClient
              ORDER BY r.idReaction DESC";
    $stmt = $con->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteReactionCRM($idReaction) {
    $con = dbConnect();
    $query = "DELETE FROM reactionActionCrm WHERE idReaction = :id";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id', $idReaction, PDO::PARAM_INT);
    return $stmt->execute();
}
function insertRetourClient($idClient, $idCommande, $avis, $commentaire) {
    $con = dbConnect();
    $query = "INSERT INTO retoursClients(idClient, idCommande, avis, commentaire) 
              VALUES (:idClient, :idCommande, :avis, :commentaire)";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':idClient', $idClient, PDO::PARAM_INT);
    $stmt->bindParam(':idCommande', $idCommande, PDO::PARAM_INT);
    $stmt->bindParam(':avis', $avis, PDO::PARAM_STR);
    $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
    return $stmt->execute();
}

function getAllRetoursClients() {
    $con = dbConnect();
    $query = "SELECT r.idRetour, r.avis, r.commentaire, r.dateRetour,
                     c.prenom, c.nom, cmd.idCommande
              FROM retoursClients r
              JOIN clients c ON r.idClient = c.idClient
              JOIN commandes cmd ON r.idCommande = cmd.idCommande
              ORDER BY r.dateRetour DESC";
    $stmt = $con->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCommandesClients() {
    $con = dbConnect();
    $query = "SELECT cmd.idCommande, cmd.idClient, c.prenom, c.nom
              FROM commandes cmd
              JOIN clients c ON cmd.idClient = c.idClient
              ORDER BY cmd.dateCommande DESC";
    $stmt = $con->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteRetourClient($idRetour) {
    $con = dbConnect();
    $query = "DELETE FROM retoursClients WHERE idRetour = :id";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id', $idRetour, PDO::PARAM_INT);
    return $stmt->execute();
}

function getRetoursClients($annee) {
    $con = dbConnect(); 

    $sql = "SELECT avis, COUNT(*) AS total
            FROM retoursClients
            WHERE YEAR(dateRetour) = ?
            GROUP BY avis";
    
    $stmt = $con->prepare($sql);
    $stmt->execute([$annee]);

    $resultats = [
        'tsara' => 0,  
        'ratsy' => 0   
    ];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        if ($row['avis'] === 'tsara') {
            $resultats['tsara'] = (int)$row['total'];  
        } elseif ($row['avis'] === 'ratsy') {
            $resultats['ratsy'] = (int)$row['total'];  
        }
    }

    return $resultats;  
}
?>
?>