<?php

    function ajoutCommandeAvecLignes($idClient, $lignesCommande, $montantTotal, $statut = 'EnAttente') {
        $con = dbConnect();
    
        try {
            // Démarrer une transaction
            $con->beginTransaction();
    
            // 1. Insertion de la commande
            $queryCommande = "INSERT INTO commandes (idClient, montantTotal, statut) VALUES (?, ?, ?)";
            $stmtCommande = $con->prepare($queryCommande);
            $stmtCommande->execute([$idClient, $montantTotal, $statut]);
    
            // Récupérer l'ID de la commande insérée
            $idCommande = $con->lastInsertId();
    
            // 2. Insertion des lignes de commande
            $queryLigne = "INSERT INTO ligneCommandes (idCommande, idProduit, quantite, prixUnitaire) VALUES (?, ?, ?, ?)";
            $stmtLigne = $con->prepare($queryLigne);
    
            foreach ($lignesCommande as $ligne) {
                // Chaque $ligne doit contenir : idProduit, quantite, prixUnitaire
                $stmtLigne->execute([
                    $idCommande,
                    $ligne['idProduit'],
                    $ligne['quantite'],
                    $ligne['prixUnitaire']
                ]);
            }
    
            // Valider la transaction
            $con->commit();
            return true;
    
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $con->rollBack();
            return false;
        }
    }

    function getAllClients() {
        $con = dbConnect();
        $query = "SELECT * FROM clients";
        $stmt = $con->prepare($query);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getAllProduits() {
        $con = dbConnect();
        $query = "SELECT * FROM produits";
        $stmt = $con->prepare($query);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
?>