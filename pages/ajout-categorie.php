<section id="ajout-categorie">
<h1 class="main-title">Ajout catégorie</h1>
    <form action="traitement-ajout-categorie.php" method="POST">
        <div>
            <label for="categorie">Catégorie :</label>
            <select id="categorie" name="categorie" required>
                <option value="Depense">Dépense</option>
                <option value="Recette">Recette</option>
            </select>
        </div>

        <div>
            <label for="type">Type :</label>
            <input type="text" id="type" name="type" required>
        </div>

        <div>
            <label for="nature">Nature :</label>
            <input type="text" id="nature" name="nature" required>
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

<section id="import-categorie">
    <h2>Importer un fichier CSV</h2>
    <form action="traitement-importcsv-categorie.php" method="post" enctype="multipart/form-data">
        <input type="file" name="csvFile" id="csv">
        <p><button type="submit">Importer</button></p>
    </form>
</section>