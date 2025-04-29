<?php 
    // include("connection.php");

    // Produits vendus par mois par sexe par classe 
    function getStatistique1($mois, $sexe, $classe) {
        $con = dbConnect();
        $query = "SELECT MONTH(c.dateCommande) AS mois, cl.sexe,
        cl.classe, p.nom AS produit, SUM(lc.quantite) AS quantite_vendue, 
        SUM(lc.quantite * lc.prixUnitaire) AS chiffre_affaire
        FROM ligneCommandes lc JOIN commandes c ON lc.idCommande = c.idCommande
        JOIN clients cl ON c.idClient = cl.idClient JOIN 
        produits p ON lc.idProduit = p.idProduit WHERE (MONTH(c.dateCommande) = :mois OR :mois IS NULL)
        AND (cl.sexe = :sexe OR :sexe IS NULL) AND (cl.classe = :classe OR :classe IS NULL) GROUP BY 
        MONTH(c.dateCommande), cl.sexe, cl.classe, p.nom ORDER BY 
        mois, cl.sexe, cl.classe, quantite_vendue DESC";

        $stmt = $con->prepare($query);
        $stmt->bindParam(':mois', $mois, PDO::PARAM_INT);
        $stmt->bindParam(':sexe', $sexe, PDO::PARAM_STR);
        $stmt->bindParam(':classe', $classe, PDO::PARAM_STR);

        $stmt->execute();
        $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $stats;
    }

?>