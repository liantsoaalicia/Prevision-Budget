<?php
    $departements = getAllDepartements();
    $periodes = getAllPeriodes();
    $depenses = getTypeNature("Depense");
    $recettes = getTypeNature("Recette");
?>
<section id="ajout-categorie">
<h1 class="main-title">Ajout Prevision</h1>
    <form action="traitement-ajout-prevision.php" method="POST">
        <div>
            <label for="dept">Departement :</label>
            <select name="idDepartement" id="dept">
                <option value="#">Selectionnez un departement</option>
                <?php foreach($departements as $d) { ?>
                    <option value="<?= $d['idDepartement'] ?>"><?= $d['nom'] ?></option>
                <?php } ?>
            </select>

            <label for="periode">Periode :</label>
            <select name="idPeriode" id="periode">
                <option value="#">Selectionnez une periode</option>
                <?php foreach($periodes as $d) { ?>
                    <option value="<?= $d['idPeriode'] ?>"><?= afficherPeriode($d['nom'], $d['dateDebut'], $d['dateFin']); ?></option>
                <?php } ?>
            </select>

            <label for="categorie">Categorie :</label>
            <select>
            </select>

            <label for="montant">Montant :</label>
            <input type="number" id="montant" name="montant">
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