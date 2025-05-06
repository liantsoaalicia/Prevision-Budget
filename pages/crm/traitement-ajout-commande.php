<?php
  include("../../inc/connection.php");
  include("../../inc/fonctionCommande.php");

// Récupérer les données POST
$idClient = $_POST['idClient'];
$produits = $_POST['produits'];
$quantites = $_POST['quantites'];
$statut = $_POST['statut'] ?? 'EnAttente';
$dateCommande = $_POST['dateCommande'];

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
$success = ajoutCommandeAvecLignes($idClient, $lignesCommande, $montantTotal, $statut, $dateCommande);

if ($success) {
    insertCommandeInBudget($dateCommande, $montantTotal);
    header("Location: ../CRM-page.php?page=crm/ajout-commande&success=Commande ajoutée avec succès");
} else {
    header("Location: ../CRM-page.php?page=crm/ajout-commande&erreur=Erreur lors de l'ajout");
}
exit;

?>