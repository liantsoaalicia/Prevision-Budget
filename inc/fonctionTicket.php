<?php
    // include('connection.php');

    function getAllTickets() {
        $con = dbConnect();
        $query = "SELECT * FROM tickets";
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

    function insertTicket($idClient, $sujet, $description, $priorite, $fichier) {
        $con = dbConnect();
        $query = "INSERT INTO tickets (idClient, idStatus, sujet, description, priorite, fichier) 
                    VALUES (:idClient, 1, :sujet, :description, :priorite, :fichier)";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':idClient', $idClient);
        $stmt->bindParam(':sujet', $sujet);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':priorite', $priorite);
        $stmt->bindParam(':fichier', $fichier);
        return $stmt->execute();
    }

    function uploadFichier($inputName) {
        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Aucun fichier envoyé ou erreur lors de l\'upload.'];
        }

        $uploadDir = __DIR__ . '/upload/';
        // var_dump("Chemin complet du dossier upload: " . $uploadDir); 
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileTmpPath = $_FILES[$inputName]['tmp_name'];
        $fileName = $_FILES[$inputName]['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = uniqid('file_', true) . '.' . $fileExtension;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destination)) {
            return ['success' => true, 'message' => 'Fichier uploadé avec succès.', 'filename' => $newFileName];
        } else {
            return ['success' => false, 'message' => 'Erreur lors du déplacement du fichier.'];
        }
    }

    function getAllAgents() {
        $con = dbConnect();
        $query = "SELECT * FROM agents";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function assignTicketToAgent($idAgent, $idTicket) {
        $con = dbConnect();
        $query = "INSERT INTO agent_ticket (idAgent, idTicket) 
                    VALUES (:idAgent, :idTicket)";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':idAgent', $idAgent);
        $stmt->bindParam(':idTicket', $idTicket);
        return $stmt->execute();
    }

   function getAgentById($idAgent) {
        $con = dbConnect();
        $query = "SELECT * FROM agents WHERE idAgent = :idAgent";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':idAgent', $idAgent);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
?>