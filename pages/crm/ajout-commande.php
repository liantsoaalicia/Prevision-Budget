<?php
$clients = getAllClients();     
$produits = getAllProduits();
?>

<section id="ajout-commande">
    <h1 class="main-title">Ajout Commande</h1>

    <form action="crm/traitement-ajout-commande.php" method="POST">
        <div>
            <label for="idClient">Client :</label>
            <select id="idClient" name="idClient" required>
                <option value="">-- Sélectionnez un client --</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['idClient'] ?>">
                        <?= $client['prenom'] . ' ' . $client['nom'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="produits-container">
            <div class="produit-row">
                <label>Produit :</label>
                <select name="produits[]" required>
                    <option value="">-- Sélectionnez un produit --</option>
                    <?php foreach ($produits as $produit): ?>
                        <option value="<?= $produit['idProduit'] ?>">
                            <?= $produit['nom'] ?> (<?= $produit['prix'] ?> Ariary)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Quantité :</label>
                <input type="number" name="quantites[]" min="1" required>
            </div>
        </div>

        <button type="button" onclick="ajouterLigneProduit()">Ajouter un produit</button>

        <div>
            <label for="dateCommande">Date de la commande :</label>
            <input type="date" id="dateCommande" name="dateCommande" required>
        </div>

        <div>
            <label for="statut">Statut :</label>
            <select name="statut" id="statut" required>
                <option value="EnAttente">En Attente</option>
                <option value="Terminee">Terminée</option>
                <option value="Annulee">Annulée</option>
            </select>
        </div>

        <button type="submit">Enregistrer la commande</button>
    </form>

    <?php if (isset($_GET['success'])) { ?>
        <h3 style="color: green;"><?= $_GET['success'] ?></h3>
    <?php } ?>

    <?php if (isset($_GET['erreur'])) { ?>
        <h3 style="color: red;"><?= $_GET['erreur'] ?></h3>
    <?php } ?>
</section>

<script>
function ajouterLigneProduit() {
    const container = document.getElementById('produits-container');
    const ligne = document.querySelector('.produit-row').cloneNode(true);
    ligne.querySelector('select').value = '';
    ligne.querySelector('input').value = '';
    container.appendChild(ligne);
}
</script>
