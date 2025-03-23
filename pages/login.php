<?php 
    require('../inc/fonction.php');

    $departements = getAllDepartements();
?>

<section>
    <?php if(isset($_GET['error'])) { ?>
        <h3 style="color: red;"><?= $_GET['error'] ?></h3>
    <?php } ?>

    <form action="traitement-login.php" method="post">
        <label for="nom">Nom <select name="idDepartement" id="nom">
        <option value="#">Selectionnez un departement</option>
            <?php foreach($departements as $d) { ?>
                <option value="<?= $d['idDepartement'] ?>"><?= $d['nom'] ?></option>
            <?php } ?>
        </select></label>
        <label for="mdp">Mot de passe <input type="password" name="mdp" id="mdp"></label>
        <button type="submit">Se connecter</button>
    </form>
</section>