<?php 
    $allDepartements = getAllDepartements();
?>

<section id="ajout-solde">
    <h1 class="main-title">Ajout solde</h1>
    <form action="traitement-ajout-solde.php" method="post">
        <label for="departements">Departement : 
            <select name="idDepartement" id="departements">
                <?php foreach($allDepartements as $d) { ?>
                    <option value="<?= $d['idDepartement'] ?>"><?= $d['nom'] ?></option>
                <?php } ?>
            </select>
        </label>

        <label for="debut">Solde debut
            <input type="number" name="debut" id="debut">
        </label>

        <button type="submit">Valider</button>
    </form>
    
    <?php if(isset($_GET['success'])) { ?>
        <h3 style="color: green;"><?= $_GET['success'] ?></h3>
    <?php } ?>

    <?php if(isset($_GET['erreur'])) { ?>
        <h3 style="color: red;"><?= $_GET['erreur'] ?></h3>
    <?php } ?>
</section>

<section id="import-solde">
    <h2>Importer un fichier CSV</h2>
    <form action="traitement-importcsv-solde.php" method="post" enctype="multipart/form-data">
        <input type="file" name="csvFile" id="csv">
        <p><button type="submit">Importer</button></p>
    </form>                
</section>