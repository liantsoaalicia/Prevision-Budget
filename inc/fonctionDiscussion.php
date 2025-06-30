<?php 

    include('connection.php');
    function checkDiscussionExists($idTicket) {
        $con = dbConnect();
        $query = "SELECT COUNT(*) FROM discussion WHERE idTicket = :idTicket";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':idTicket', $idTicket);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    function addDiscussion($idAgent = null, $idClient = null, $idTicket = null) {
        $con = dbConnect();
        try {
            // Start transaction
            $con->beginTransaction();

            // Insérer une nouvelle discussion
            $query = "INSERT INTO discussion (dateCreation, idAgent, idClient, idTicket) VALUES (NOW(), :idAgent, :idClient, :idTicket)";
            $stmt = $con->prepare($query);
            $stmt->bindParam(':idAgent', $idAgent);
            $stmt->bindParam(':idClient', $idClient);
            $stmt->bindParam(':idTicket', $idTicket);
            $stmt->execute();
            $idDiscussion = $con->lastInsertId();

            // Commit transaction
            $con->commit();
            return $idDiscussion;
        } catch (Exception $e) {
            // Rollback transaction on error
            $con->rollBack();
            throw new Exception("Erreur lors de l'ajout de la discussion : " . $e->getMessage());
        }
    }
    // Ajout d'un message à la discussion
    function addDiscussionMessage($idDiscussion, $auteur, $message, $idClient = null, $idAgent = null, $fichier = null) {
        $con = dbConnect();
        try {
            // Start transaction
            $con->beginTransaction();

            // Insérer le message dans discussion_ticket
            $query = "INSERT INTO discussion_ticket 
                (idDiscussion, auteur, idClient, idAgent, message, fichier)
                VALUES (:idDiscussion, :auteur, :idClient, :idAgent, :message, :fichier)";

            $stmt = $con->prepare($query);
            $stmt->bindParam(':idDiscussion', $idDiscussion);
            $stmt->bindParam(':auteur', $auteur);
            $stmt->bindParam(':idClient', $idClient);
            $stmt->bindParam(':idAgent', $idAgent);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':fichier', $fichier);
            $stmt->execute();

            // Commit transaction
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw new Exception("Erreur lors de l'ajout du message de discussion : " . $e->getMessage());
        }
    }

    function getIdDiscussion($idTicket) {
        $con = dbConnect();
        $query = "SELECT idDiscussion FROM discussion WHERE idTicket = :idTicket";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':idTicket', $idTicket);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    function getDiscussion($idDiscussion) {
        $con = dbConnect();
        $query = "SELECT idMessage, auteur, message, dateMessage FROM discussion_ticket 
                  WHERE idDiscussion = :idDiscussion
                  ORDER BY dateMessage ASC";
        $stmt = $con->prepare($query);
        $stmt -> bindParam(':idDiscussion', $idDiscussion);
        $stmt -> execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>