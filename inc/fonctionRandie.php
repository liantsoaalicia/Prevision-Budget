<?php
    include("connection.php");

    function ajoutPrevision($idDepartement, $idPeriode, $idCategorie, $prevision, $realisation, $valide){
        $con = dbConnect();
        $query = "INSERT INTO prevision (idDepartement, idPeriode, idCategorie, prevision, realisation, valide) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);

        if ($stmt->execute([$idDepartement, $idPeriode, $idCategorie, $prevision, $realisation, $valide])) {
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

    function getPrevisionById($idprevision) {
        $con = dbConnect();
        $query = "SELECT * FROM prevision WHERE idPrevision=?";
        $stmt = $con->prepare($query);
        $stmt->execute([$idprevision]);
        $prevs = $stmt->fetch(PDO::FETCH_ASSOC);
        return $prevs;
    }

    function getTotalDepensePrevue($departementId, $periodeId) {
        $con = dbConnect();
        $sql = "
            SELECT COALESCE(SUM(p.prevision), 0) as total
            FROM prevision p
            JOIN categorie c ON p.idCategorie = c.idCategorie
            WHERE p.idDepartement = ?
            AND p.idPeriode = ?
            AND c.categorie = 'Depense'
            AND p.valide=1
        ";
        
        $stmt = $con->prepare($sql);
        $stmt->execute([$departementId, $periodeId]);
        return floatval($stmt->fetchColumn());
    }

    function getTotalDepenseRealisee($departementId, $periodeId) {
        $con = dbConnect();
        $sql = "
            SELECT COALESCE(SUM(p.realisation), 0) as total
            FROM prevision p
            JOIN categorie c ON p.idCategorie = c.idCategorie
            WHERE p.idDepartement = ?
            AND p.idPeriode = ?
            AND c.categorie = 'Depense'
            AND p.valide=1
        ";
        
        $stmt = $con->prepare($sql);
        $stmt->execute([$departementId, $periodeId]);
        return floatval($stmt->fetchColumn());
    }

    function getTotalRecettePrevue($departementId, $periodeId) {
        $con = dbConnect();
        $sql = "
            SELECT COALESCE(SUM(p.prevision), 0) as total
            FROM prevision p
            JOIN categorie c ON p.idCategorie = c.idCategorie
            WHERE p.idDepartement = ?
            AND p.idPeriode = ?
            AND c.categorie = 'Recette'
            AND p.valide=1
        ";
        
        $stmt = $con->prepare($sql);
        $stmt->execute([$departementId, $periodeId]);
        return floatval($stmt->fetchColumn());
    }

    function getTotalRecetteRealisee($departementId, $periodeId) {
        $con = dbConnect();
        $sql = "
            SELECT COALESCE(SUM(p.realisation), 0) as total
            FROM prevision p
            JOIN categorie c ON p.idCategorie = c.idCategorie
            WHERE p.idDepartement = ?
            AND p.idPeriode = ?
            AND c.categorie = 'Recette'
            AND p.valide=1
        ";
        
        $stmt = $con->prepare($sql);
        $stmt->execute([$departementId, $periodeId]);
        return floatval($stmt->fetchColumn());
    }

    function getSoldeDebut($departementId) {
        $con = dbConnect();
        $sql = "
            SELECT COALESCE(soldeDebut, 0) as solde
            FROM solde
            WHERE idDepartement = ?
            ORDER BY idSolde DESC
            LIMIT 1
        ";
        
        $stmt = $con->prepare($sql);
        $stmt->execute([$departementId]);
        return floatval($stmt->fetchColumn());
    }

    function getSoldeDebutPrevision($departementId, $periodeId) {
        if ($periodeId == 1) {
            return getSoldeDebut($departementId);
        } else {
            $periodes = getAllPeriodes();
            $debut = getSoldeDebut($departementId);
            $solde = $debut;

            foreach ($periodes as $periode) {
                if($periode['idPeriode']>$periodeId){
                    break;
                }
                $solde += getTotalRecettePrevue($departementId, $periode['idPeriode']);
                $solde -= getTotalDepensePrevue($departementId, $periode['idPeriode']);
            }
            
            if($solde==$debut){
                return 0;
            }
            
            return $solde;
        }
    }

    function getSoldeDebutRealise($departementId, $periodeId) {
        if ($periodeId == 1) {
            return getSoldeDebut($departementId);
        } else {
            $periodes = getAllPeriodes();
            $debut = getSoldeDebut($departementId);
            $solde = $debut;
            
            foreach ($periodes as $periode) {
                if($periode['idPeriode']>$periodeId){
                    break;
                }
                $solde += getTotalRecetteRealisee($departementId, $periode['idPeriode']);
                $solde -= getTotalDepenseRealisee($departementId, $periode['idPeriode']);
            }
            
            if($solde==$debut){
                return 0;
            }

            return $solde;
        }
    }

    function getSoldeFinPrevision($departementId, $periodeId) {
        $soldeDebut = getSoldeDebutPrevision($departementId, $periodeId);
        
        $totalRecette = getTotalRecettePrevue($departementId, $periodeId);
        $totalDepense = getTotalDepensePrevue($departementId, $periodeId);
        
        return $soldeDebut + $totalRecette - $totalDepense;
    }

    function getSoldeFinRealise($departementId, $periodeId) {
        $soldeDebut = getSoldeDebutRealise($departementId, $periodeId);
        
        $totalRecette = getTotalRecetteRealisee($departementId, $periodeId);
        $totalDepense = getTotalDepenseRealisee($departementId, $periodeId);
        
        return $soldeDebut + $totalRecette - $totalDepense;
    }
?>