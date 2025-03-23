<?php
    $page = "accueil";
    if(isset($_GET['page'])){
        $page = $_GET['page'];
    }
    $page = $page.".php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Budg&eacute;taire</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header>
        <nav class="main-nav">
            <ul>
                <li class="dropdown">
                    <button class="dropbtn">D&eacute;partements</button>
                    <div class="dropdown-content">
                        <a href="template.php?page=listeDepartement">Liste des d&eacute;partements</a>
                        <a href="#ajout-departement">Ajouter d&eacute;partement</a>
                        <a href="#budget-departement">Voir budget d&eacute;partement</a>
                    </div>
                </li>
                <li class="dropdown">
                    <button class="dropbtn">Cat&eacute;gories</button>
                    <div class="dropdown-content">
                        <a href="template.php?page=listeCategorie">Liste des cat&eacute;gories</a>
                        <a href="#ajout-categorie">Ajouter cat&eacute;gorie</a>
                    </div>
                </li>
                <li class="dropdown">
                    <button class="dropbtn">Budget</button>
                    <div class="dropdown-content">
                        <a href="#previsions">Ajouter pr&eacute;vision</a>
                        <a href="#soldes">Voir soldes</a>
                        <a href="#budget-total">Voir budget total</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main class="content">
        <?php include("$page"); ?>
    </main>

    <script src="../assets/script.js"></script>
</body>
</html>