<section id="ajout-categorie-produit">
    <h1 class="main-title">Ajout de categorie pour les produits</h1>
    <form action="crm/traitement-ajout-ctg.php" method="post">
        <div>
            <label for="nom">Nom : </label>
            <input type="text" name="nom" id="nom" required>
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