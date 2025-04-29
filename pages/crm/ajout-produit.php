<?php 
    $categories = getAllCategorieProduit();
?>

<section id="ajout-produit">
    <h1 class="main-title">Ajout de produits</h1>
    <form action="traitement-ajout-produit.php" method="post">
        <div>
            <label for="nom">Nom : </label>
            <input type="text" name="nom" id="nom" required>
        </div>
        <div>
            <label for="desc">Description : </label>
            <textarea name="desc" id="desc" rows="4" required></textarea>
        </div>
        <div>
            <label for="categorie">Categorie : </label>
            <select name="categorie" id="categorie">
                <?php foreach($categories as $c) { ?>
                    <option value="<?= $c['idCategorie'] ?>"><?= $c['nom'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div>
            <label for="prix">Prix</label>
            <input type="number" name="prix" id="prix">
        </div>
        <div>
            <label for="quantite">Quantite</label>
            <input type="number" name="quantite" id="quantite">
        </div>
        <button type="submit">Ajouter</button>
    </form>
</section>

<?php if(isset($_GET['success'])) { ?>
    <h3 style="color: green;"><?= $_GET['success'] ?></h3>
<?php } ?>

<?php if(isset($_GET['erreur'])) { ?>
    <h3 style="color: red;"><?= $_GET['erreur'] ?></h3>
<?php } ?>