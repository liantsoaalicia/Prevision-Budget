<?php
    include("connection.php");

    function ajoutPrevision($idDepartement, $idPeriode, $idCategorie, $montant, $valide){
        $con = dbConnect();
        $query = "INSERT INTO prevision (idDepartement, idPeriode, idCategorie, montant, valide) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);

        if ($stmt->execute([$idDepartement, $idPeriode, $idCategorie, $montant, $valide])) {
            return true; 
        } else {
            return false; 
        }        
    }

    function getAllPeriodes() {
        $con = dbConnect();
        $query = "SELECT * FROM periode";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $periodes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $periodes;
    }

    function afficherPeriode($nom, $dateDebut, $dateFin) {
        $dateDebut = new DateTime($dateDebut);
        $dateFin = new DateTime($dateFin);
        
        $mois = $dateDebut->format('F'); 
        $annee = $dateDebut->format('Y');
        
        $moisFrancais = [
            'January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars',
            'April' => 'Avril', 'May' => 'Mai', 'June' => 'Juin',
            'July' => 'Juillet', 'August' => 'Août', 'September' => 'Septembre',
            'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'
        ];
        
        $mois = $moisFrancais[$mois] ?? $mois;
        
        return "$nom ($mois $annee)";
    }

    function getTypeNature($type) {
        $con = dbConnect();
        $query = "SELECT * FROM categorie WHERE categorie=?";
        $stmt = $con->prepare($query);
        $stmt->execute([$type]);
        $departements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $departements;
    }
?>