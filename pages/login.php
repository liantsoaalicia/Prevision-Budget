<?php
    require('../inc/fonctions.php');
    $departements = getAllDepartements();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/login.css"> 
</head>
<body>
    <div class="formulaire-login"> 
        <?php if(isset($_GET['error'])) { ?>
            <h3 style="color: red;"><?= $_GET['error'] ?></h3>
        <?php } ?>
        
        <form action="traitement-login.php" method="post">
            <label for="nom">
                Nom d'utilisateur <br>
                <input type="text" name="nom" id="nom">
            </label>
            
            <label for="mdp">
                Mot de passe 
                <input type="password" name="mdp" id="mdp">
            </label>
            
            <button type="submit">Se connecter</button>
        </form>

    <div class="importcsv-departement">
        <h2>Importer un fichier CSV</h2>
        <form action="traitement-importcsv-dpt.php" method="post" enctype="multipart/form-data">
            <input type="file" name="csvFile" id="csv">
            <p><button type="submit">Importer</button></p>
        </form>
        <?php if(isset($_GET['success'])) { ?>
            <h3 style="color: green;"><?= $_GET['success'] ?></h3>
        <?php } ?>

        <?php if(isset($_GET['erreur'])) { ?>
            <h3 style="color: red;"><?= $_GET['erreur'] ?></h3>
        <?php } ?>
    </div>

    </div>
</body>
</html>