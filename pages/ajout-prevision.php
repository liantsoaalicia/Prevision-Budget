<?php
    $departements = getAllDepartements();
    $periodes = getAllPeriodes();
    $depenses = getTypeNature("Depense");
    $recettes = getTypeNature("Recette");
    $categories = listerCategories();
?>
<section id="ajout-prevision">
<h1 class="main-title">Ajout Prevision pour ce département</h1>
    <form action="traitement-ajout-prevision.php" method="POST">
        <div>

            <label for="periode">Période :</label>
            <select name="idPeriode" id="periode">
                <option value="#">Sélectionnez une periode</option>
                <?php foreach($periodes as $d) { ?>
                    <option value="<?= $d['idPeriode'] ?>"><?= afficherPeriode($d['nom'], $d['dateDebut'], $d['dateFin']); ?></option>
                <?php } ?>
            </select>

            <label for="categorie">Catégorie :</label>
            <select name="idCategorie">
                <optgroup label="Depenses">
                    <?php foreach($categories['depenses'] as $depense) { ?>
                        <option value="<?= $depense['idCategorie'] ?>"><?= $depense['types']?> - <?= $depense['nature'] ?></option>
                    <?php } ?>
                </optgroup>
                <optgroup label="Recette">
                    <?php foreach($categories['recettes'] as $recette) { ?>
                        <option value="<?= $recette['idCategorie'] ?>"><?= $recette['types']?> - <?= $recette['nature'] ?></option>
                    <?php } ?>
                </optgroup>
            </select>

            <label for="prevision">Prévision :</label>
            <input type="number" id="prevision" name="prevision">
            
            <label for="realisation">Réalisation :</label>
            <input type="number" id="realisation" name="realisation">
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

<section id="import-prevision">
    <h2>Importer un fichier CSV</h2>
    <form action="traitement-importcsv-prevision.php" method="post" enctype="multipart/form-data">
        <input type="file" name="csvFile" id="csv">
        <p><button type="submit">Importer</button></p>
    </form>
</section>