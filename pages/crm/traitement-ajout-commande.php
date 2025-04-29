<?php
  include("../../inc/connection.php");

function ajoutCommandeAvecLignes($idClient, $lignesCommande, $montantTotal, $statut = 'EnAttente') {
    $con = dbConnect();

    try {
        $con->beginTransaction();

        // Insérer la commande
        $queryCommande = "INSERT INTO commandes (idClient, montantTotal, statut) VALUES (?, ?, ?)";
        $stmtCommande = $con->prepare($queryCommande);
        $stmtCommande->execute([$idClient, $montantTotal, $statut]);

        $idCommande = $con->lastInsertId();

        // Insérer les lignes de commande
        $queryLigne = "INSERT INTO ligneCommandes (idCommande, idProduit, quantite, prixUnitaire) VALUES (?, ?, ?, ?)";
        $stmtLigne = $con->prepare($queryLigne);

        foreach ($lignesCommande as $ligne) {
            $stmtLigne->execute([
                $idCommande,
                $ligne['idProduit'],
                $ligne['quantite'],
                $ligne['prixUnitaire']
            ]);
        }

        $con->commit();
        return true;

    } catch (PDOException $e) {
        $con->rollBack();
        return false;
    }
}

// Récupérer les données POST
$idClient = $_POST['idClient'];
$produits = $_POST['produits'];
$quantites = $_POST['quantites'];
$statut = $_POST['statut'] ?? 'EnAttente';

if (!isset($idClient) || empty($produits) || empty($quantites)) {
    header("Location: ../CRM-page.php?page=crm/ajout-commande&erreur=Données manquantes");
    exit;
}

// Charger les prix des produits depuis la base
$con = dbConnect();
$placeholders = implode(',', array_fill(0, count($produits), '?'));
$query = "SELECT idProduit, prix FROM produits WHERE idProduit IN ($placeholders)";
$stmt = $con->prepare($query);
$stmt->execute($produits);
$prixProduits = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // [idProduit => prix]

$lignesCommande = [];
$montantTotal = 0;

foreach ($produits as $index => $idProduit) {
    $quantite = (int)$quantites[$index];
    $prix = $prixProduits[$idProduit] ?? 0;
    $ligne = [
        'idProduit' => $idProduit,
        'quantite' => $quantite,
        'prixUnitaire' => $prix
    ];
    $lignesCommande[] = $ligne;
    $montantTotal += $prix * $quantite;
}

// Appeler la fonction d'insertion
$success = ajoutCommandeAvecLignes($idClient, $lignesCommande, $montantTotal, $statut);

if ($success) {
    header("Location: ../CRM-page.php?page=crm/ajout-commande&success=Commande ajoutée avec succès");
} else {
    header("Location: ../CRM-page.php?page=crm/ajout-commande&erreur=Erreur lors de l'ajout");
}
exit;

?>