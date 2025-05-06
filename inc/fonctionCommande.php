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
    
    function getIdCtgCommandes(){
        $conn = dbConnect();
        $query = "SELECT idCategorie FROM categorie where types='Commandes' AND nature='Commandes Realisees'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return $result;
    }

    function getIdPeriodeCommande($dateCommande) {
        $con = dbConnect();
        $stmt = $con->prepare("SELECT idPeriode FROM periode WHERE :dateAction BETWEEN dateDebut AND dateFin");
        $stmt->execute([':dateAction' => $dateCommande]);
        $periode = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$periode) return false;
    
        $idPeriode = $periode['idPeriode'];
        return $idPeriode;
    }

    function getCommandeById($idCommande) {
        $con = dbConnect();
        $stmt = $con->prepare("SELECT * FROM commandes WHERE idCommande=?");
        $stmt->execute([$idCommande]);
        $Commande = $stmt->fetch(PDO::FETCH_ASSOC);
        return $Commande;
    }

    function tokonyUpdate($dateCommande){
        $con = dbConnect();
        $idCategorie = getIdCtgCommandes();
        $idPeriode =  getIdPeriodeCommande($dateCommande);
        $stmt = $con->prepare("SELECT * FROM prevision WHERE idCategorie=? AND idPeriode=?");
        $stmt->execute([$idCategorie, $idPeriode]);
        $Ctg = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($Ctg)){
            return true;
        }
        return false;
    }

    function getDernierMontant($dateCommande){
        $con = dbConnect();
        $idCategorie = getIdCtgCommandes();
        $idPeriode =  getIdPeriodeCommande($dateCommande);
        $stmt = $con->prepare("SELECT realisation FROM prevision WHERE idCategorie=? AND idPeriode=?");
        $stmt->execute([$idCategorie, $idPeriode]);
        $montant = $stmt->fetchColumn();
        return $montant;
    }

    function insertCommandeInBudget($dateCommande, $montantTotal) {
        session_start();
        $con = dbConnect();
        $idDepartement = $_SESSION['id'];
        $idPeriode = getIdPeriodeCommande($dateCommande);
        $update = tokonyUpdate($dateCommande);
        $idCtg = getIdCtgCommandes();

        if($idPeriode!=false && !$update){
            $stmt = $con->prepare("INSERT INTO prevision (idDepartement, idPeriode, idCategorie, prevision, realisation, valide)
            VALUES (:idDepartement, :idPeriode, :idCategorie, :prevision, :realisation, 1)");
                return $stmt->execute([
                ':idDepartement' => $idDepartement,
                ':idPeriode' => $idPeriode,
                ':idCategorie' => $idCtg,
                ':prevision' => 0,
                ':realisation'=> $montantTotal
            ]);
        }
        else if($idPeriode!=false && $update){
            $dernierMontant = getDernierMontant($dateCommande);
            $nouveau = $dernierMontant + $montantTotal;
            $stmt = $con->prepare("UPDATE prevision SET realisation=? WHERE idCategorie=? AND idPeriode=?");
            $stmt->execute([$nouveau, $idCtg, $idPeriode]);
        }
    }
?>