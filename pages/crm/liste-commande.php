<?php 

    $commandes = getAllCommandes();

    $commandeDetails = null;
    if (isset($_GET['details']) && is_numeric($_GET['details'])) {
        $commandeDetails = getCommandeDetails($_GET['details']);
    }

?>

<section id="liste-commandes">
    <h1 class="main-title">Liste des Commandes</h1>

    <?php if (isset($_GET['success'])) { ?>
        <div class="alert alert-success"><?= $_GET['success'] ?></div>
    <?php } ?>

    <?php if (isset($_GET['erreur'])) { ?>
        <div class="alert alert-danger"><?= $_GET['erreur'] ?></div>
    <?php } ?>

    <?php if (empty($commandes)): ?>
        <p class="no-data">Aucune commande enregistrée pour le moment.</p>
    <?php else: ?>
        <table class="table-data">
            <thead>
                <tr>
                    <th>N° Commande</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td><?= $commande['idCommande'] ?></td>
                        <td><?= $commande['prenom'] . ' ' . $commande['nom'] ?></td>
                        <td><?= formatDate($commande['dateCommande']) ?></td>
                        <td><?= number_format($commande['montantTotal'], 2, ',', ' ') ?> Ariary</td>
                        <td><?= formatStatut($commande['statut']) ?></td>
                        <td class="actions">
                            <a href="CRM-page.php?page=crm/liste-commande&details=<?= $commande['idCommande'] ?>" 
                               class="btn btn-info btn-sm">Détails</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <?php if ($commandeDetails !== null): ?>
        <div class="commande-details">
            <h2>Détails de la commande N°<?= $_GET['details'] ?></h2>
            <?php if (empty($commandeDetails)): ?>
                <p>Aucun détail disponible pour cette commande.</p>
            <?php else: ?>
                <table class="table-data">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Description</th>
                            <th>Prix unitaire</th>
                            <th>Quantité</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        <?php foreach ($commandeDetails as $ligne): ?>
                            <?php $sousTotal = $ligne['quantite'] * $ligne['prixUnitaire']; ?>
                            <?php $total += $sousTotal; ?>
                            <tr>
                                <td><?= $ligne['nomProduit'] ?></td>
                                <td><?= $ligne['description'] ?></td>
                                <td><?= number_format($ligne['prixUnitaire'], 2, ',', ' ') ?> Ariary</td>
                                <td><?= $ligne['quantite'] ?></td>
                                <td><?= number_format($sousTotal, 2, ',', ' ') ?> Ariary</td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="total-row">
                            <td colspan="4" class="text-right"><strong>Total</strong></td>
                            <td><strong><?= number_format($total, 2, ',', ' ') ?> Ariary</strong></td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<style>
    .table-data {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    .table-data th, .table-data td {
        padding: 10px;
        border: 1px solid #ddd;
    }
    
    .table-data th {
        background-color: #f5f5f5;
        font-weight: bold;
    }
    
    .statut-attente {
        color: #ff9800;
        font-weight: bold;
    }
    
    .statut-terminee {
        color: #4caf50;
        font-weight: bold;
    }
    
    .statut-annulee {
        color: #f44336;
        font-weight: bold;
    }
    
    .actions {
        margin-bottom: 20px;
    }
    
    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
        text-decoration: none;
    }
    
    .btn-primary {
        color: #fff;
        background-color: #337ab7;
        border-color: #2e6da4;
    }
    
    .btn-info {
        color: #fff;
        background-color: #5bc0de;
        border-color: #46b8da;
    }
    
    .btn-warning {
        color: #fff;
        background-color: #f0ad4e;
        border-color: #eea236;
    }
    
    .btn-danger {
        color: #fff;
        background-color: #d9534f;
        border-color: #d43f3a;
    }
    
    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }
    
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    
    .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }
    
    .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
    }
    
    .commande-details {
        margin-top: 30px;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 5px;
        border: 1px solid #ddd;
    }
    
    .commande-details h2 {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 20px;
    }
    
    .text-right {
        text-align: right;
    }
    
    .total-row {
        background-color: #f5f5f5;
    }
    
    .no-data {
        padding: 20px;
        text-align: center;
        background-color: #f9f9f9;
        border-radius: 5px;
        border: 1px solid #ddd;
    }
</style>