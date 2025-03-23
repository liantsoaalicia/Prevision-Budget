<section id="ajout-categorie">
<h1 class="main-title">Ajout Département</h1>
    <form action="traitement-ajout-departement.php" method="POST">
        <div>
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>
        </div>

        <button type="submit">Ajouter</button>
    </form>

    <?php if(isset($_GET['success'])) { ?>
        <h3 style="color: green;"><?= $_GET['success'] ?></h3>
    <?php } ?>

    <?php if(isset($_GET['erreur'])) { ?>
        <h3 style="color: red;"><?= $_GET['erreur'] ?></h3>
    <?php } ?>
</section>