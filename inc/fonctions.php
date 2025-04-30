<?php
    //include("connection.php");
    include("fonctionRandie.php");

    function getAllDepartements() {
        $con = dbConnect();
        $query = "SELECT * FROM departement";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $departements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $departements;
    }

    function login($idDepartement, $mdp) {
        $con = dbConnect();
        $query = "SELECT * FROM departement WHERE idDepartement = :idDepartement";
        $stmt = $con->prepare($query);
        $stmt->bindParam('idDepartement', $idDepartement, PDO::PARAM_INT);
        $stmt->execute();
        $departement = $stmt->fetch(PDO::FETCH_ASSOC);

        if($departement) {
            if($mdp == $departement['mdp']) {
                return 1;
            } else {
                return "Mot de passe incorrect";
            }
        }

        return "Identifiant incorrect";
    }

    function getDepartementById($id) {
        $con = dbConnect();
        $query = "SELECT * FROM departement WHERE idDepartement = :id";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $departement = $stmt->fetch(PDO::FETCH_ASSOC);
        return $departement;
    }
    
    function listerDepartements() {
        try {
            $pdo = dbConnect();
            
            $sql = "SELECT idDepartement, nom FROM departement ORDER BY nom";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    function supprimerDepartement($idDepartement) {
        try {
            $pdo = dbConnect();
            
            $sql = "SELECT COUNT(*) as count FROM departement WHERE idDepartement = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$idDepartement]);
            $result = $stmt->fetch();
            
            if ($result['count'] == 0) {
                throw new Exception("Le département n'existe pas");
            }
            
            $sql = "SELECT COUNT(*) as count FROM prevision WHERE idDepartement = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$idDepartement]);
            $result = $stmt->fetch();
            
            if ($result['count'] > 0) {
                throw new Exception("Impossible de supprimer ce département car il contient des prévisions");
            }
            
            $sql = "DELETE FROM departement WHERE idDepartement = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$idDepartement]);
            
            return true;
        } catch(PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    function listerCategories() {
        $con = dbConnect();
        $query = "SELECT * FROM categorie";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $depenses = [];
        $recettes = [];

        foreach ($categories as $categorie) {
            if ($categorie['categorie'] == 'Depense') {
                $depenses[] = $categorie;
            } elseif ($categorie['categorie'] == 'Recette') {
                $recettes[] = $categorie;
            }
        }

        return [
            'depenses' => $depenses,
            'recettes' => $recettes
        ];
    }
    
    function insertCategorie($categorie, $types, $nature) {
        $con = dbConnect();
        $query = "INSERT INTO categorie (categorie, types, nature) VALUES (:categorie, :types, :nature)";
        $stmt = $con->prepare($query);

        $stmt->bindParam(':categorie', $categorie, PDO::PARAM_STR);
        $stmt->bindParam(':types', $types, PDO::PARAM_STR);
        $stmt->bindParam(':nature', $nature, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return true; 
        } else {
            return false; 
        }
    }

    function insertDepartement($nom) {
        $con = dbConnect();
        $query = "INSERT INTO departement (nom) VALUES (:nom)";
        $stmt = $con->prepare($query);

        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return true; 
        } else {
            return false; 
        }
    }

    function verifyIfFinance($sessionId) {
        $departement = getDepartementById($sessionId);
        $nom = $departement['nom'];
        if($nom == 'Departement Finance') {
            return true;
        } return false;
    }

    function verifyIfMarketing($sessionId) {
        $departement = getDepartementById($sessionId);
        $nom = $departement['nom'];
        if($nom == 'Departement Marketing') {
            return true;
        } return false;
    }

    // ceux qui ont ete valide 
    function getPrevisionDepartement($idDepartement) {
        $con = dbConnect();

        $query = "SELECT p.idPrevision, p.idCategorie, p.idPeriode, p.prevision, p.realisation, p.valide,
        d.nom AS nom_departement, per.nom AS nom_periode, per.dateDebut, per.dateFin,
        c.categorie, c.types, c.nature FROM prevision p JOIN 
        departement d ON p.idDepartement = d.idDepartement JOIN 
        periode per ON p.idPeriode = per.idPeriode JOIN 
        categorie c ON p.idCategorie = c.idCategorie WHERE p.idDepartement = :idDepartement 
        AND p.valide = 1";

        $stmt = $con->prepare($query);
        $stmt->bindParam(':idDepartement', $idDepartement, PDO::PARAM_INT);
        $stmt->execute();
        $departements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $departements;
    }

    function validerPrevision($idPrevision) {
        $con = dbConnect();
        $query = "UPDATE prevision SET valide = 1 WHERE idPrevision = :id";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':id', $idPrevision, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true; 
        } else {
            return false; 
        }
    }

    function getAllInvalidPrevision() {
        $con = dbConnect();
        $query = "SELECT p.*, d.nom AS nom_departement, per.nom AS nom_periode,
        c.categorie, c.types, c.nature FROM prevision p JOIN 
        departement d ON p.idDepartement = d.idDepartement JOIN 
        periode per ON p.idPeriode = per.idPeriode JOIN 
        categorie c ON p.idCategorie = c.idCategorie WHERE p.valide = 0";
        
        $stmt = $con->prepare($query);
        $stmt->execute();
        $allPrevisions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
        $depenses = [];
        $recettes = [];
        
        foreach ($allPrevisions as $prev) {
            if ($prev['categorie'] === 'Depense') {
                $depenses[] = $prev;
            } else {
                $recettes[] = $prev;
            }
        }
        
        return [
            'depenses' => $depenses,
            'recettes' => $recettes
        ];  
    }

    function insertSoldes($idDepartement, $soldeDebut) {
        $con = dbConnect();
        $query = "INSERT INTO solde (idDepartement, soldeDebut) VALUES (:id, :debut)";
        $stmt = $con->prepare($query);

        $stmt->bindParam(':id', $idDepartement, PDO::PARAM_INT);
        $stmt->bindParam(':debut', $soldeDebut, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return true; 
        } else {
            return false; 
        }
    }

    function getAllCategorieProduit() {
        $con = dbConnect();
        $query = "SELECT * FROM categorieProduit";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $categories;
    }

    function validerAction($idAction) {
        session_start();
        $idDepartement = $_SESSION['id'];
    
        $con = dbConnect();
    
        // Étape 1 : récupérer dateAction, coutsPrevision et typeAction
        $stmt = $con->prepare("SELECT dateAction, coutsPrevision, typeAction FROM actionsCrm WHERE idAction = :idAction");
        $stmt->execute([':idAction' => $idAction]);
        $action = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$action) return false;
    
        $dateAction = $action['dateAction'];
        $coutsPrevision = $action['coutsPrevision'];
        $typeAction = $action['typeAction'];
    
        // Étape 2 : trouver la période correspondant à la dateAction
        $stmt = $con->prepare("SELECT idPeriode FROM periode WHERE :dateAction BETWEEN dateDebut AND dateFin");
        $stmt->execute([':dateAction' => $dateAction]);
        $periode = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$periode) return false;
    
        $idPeriode = $periode['idPeriode'];
    
        // Étape 3 : mettre à jour validationFinance à true
        $stmt = $con->prepare("UPDATE actionsCrm SET validationFinance = true WHERE idAction = :idAction");
        $stmt->execute([':idAction' => $idAction]);
    
        // Étape 4 : chercher ou créer la catégorie correspondante
        $stmt = $con->prepare("SELECT idCategorie FROM categorie WHERE categorie = 'Depense' AND types = 'ActionCRM' AND nature = :nature");
        $stmt->execute([':nature' => $typeAction]);
        $categorie = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$categorie) {
            $stmt = $con->prepare("INSERT INTO categorie (categorie, types, nature) VALUES ('Depense', 'ActionCRM', :nature)");
            $stmt->execute([':nature' => $typeAction]);
            $idCategorie = $con->lastInsertId();
        } else {
            $idCategorie = $categorie['idCategorie'];
        }
    
        // Étape 5 : insérer dans la table prevision
        $stmt = $con->prepare("INSERT INTO prevision (idDepartement, idPeriode, idCategorie, prevision, realisation, valide)
                               VALUES (:idDepartement, :idPeriode, :idCategorie, :prevision, 0, 0)");
        return $stmt->execute([
            ':idDepartement' => $idDepartement,
            ':idPeriode' => $idPeriode,
            ':idCategorie' => $idCategorie,
            ':prevision' => $coutsPrevision
        ]);
    }
    
    function getAllInvalidActionsCrm() {
        $con = dbConnect();
        $stmt = $con->query("
            SELECT 
                ac.idAction,
                d.nom AS nom_departement,
                ac.typeAction,
                ac.dateAction,
                ac.coutsPrevision
            FROM actionsCrm ac
            JOIN departement d ON ac.idDepartement = d.idDepartement
            WHERE ac.validationFinance = 0
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
?>