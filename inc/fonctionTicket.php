<?php
    
    function getAllTickets() {
        $con = dbConnect();
        $query = "SELECT t.*, COALESCE(at.idAgent, 0) AS idAgent
              FROM tickets t
              LEFT JOIN agent_ticket at ON t.idTicket = at.idTicket";
        $stmt = $con->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
    
    function closeOldEnCoursTickets() {
        $con = dbConnect();
        // Select tickets with status 'en cours' (3) for more than 5 days
        $query = "SELECT t.idTicket
              FROM tickets t
              INNER JOIN (
                  SELECT idTicket, MAX(dateChangement) AS lastChange
                  FROM ticket_status_history
                  WHERE idStatus = 3
                  GROUP BY idTicket
              ) h ON t.idTicket = h.idTicket
              WHERE t.idStatus = 3
                AND h.lastChange <= DATE_SUB(NOW(), INTERVAL 5 DAY)";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tickets as $ticket) {
            updateTicketStatus($ticket['idTicket'], 5, "Fermé automatiquement après 5 jours en cours");
        }
    }

    function updateTicketStatus($idTicket, $idStatus, $commentaire = null) {
        $con = dbConnect();
        try {
            // Start transaction
            $con->beginTransaction();

            // Update ticket status
            $query = "UPDATE tickets SET idStatus = :idStatus WHERE idTicket = :idTicket";
            $stmt = $con->prepare($query);
            $stmt->bindParam(':idStatus', $idStatus);
            $stmt->bindParam(':idTicket', $idTicket);
            $stmt->execute();

            // Insert into ticket_status_history
            $queryHistory = "INSERT INTO ticket_status_history (idTicket, idStatus, commentaire) VALUES (:idTicket, :idStatus, :commentaire)";
            $stmtHistory = $con->prepare($queryHistory);
            $stmtHistory->bindParam(':idTicket', $idTicket);
            $stmtHistory->bindParam(':idStatus', $idStatus);
            if ($commentaire === null) {
                $commentaire = '';
            }
            $stmtHistory->bindParam(':commentaire', $commentaire);
            $stmtHistory->execute();

            // Commit transaction
            $con->commit();
        } catch (Exception $e) {
            // Rollback transaction on error
            $con->rollBack();
            throw new Exception("Erreur lors de la mise à jour du statut du ticket : " . $e->getMessage());
        }
    }

    function getAllStatusTicket() {
        $con = dbConnect();
        $query = "SELECT * FROM status_ticket";
        $stmt = $con->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    
   
?>