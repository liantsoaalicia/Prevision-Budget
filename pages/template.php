<?php
    session_start();
    include('../inc/fonctions.php');
    $page = "accueil";
    if(isset($_GET['page'])){
        $page = $_GET['page'];
    }
    $page = $page.".php";

    $deptConnecte = getDepartementById($_SESSION['id']);
    $nomDeptConnecte = $deptConnecte['nom'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Budgétaire</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header>
        <nav class="main-nav">
            <ul>
            <div class="dept-connecte">
                <span class="point-vert"></span>
                <span class="nom-dept"><?= htmlspecialchars($nomDeptConnecte) ?></span>
            </div>
                <li class="dropdown">
                    <button class="dropbtn">D&eacute;partements</button>
                    <div class="dropdown-content">
                        <a href="template.php?page=listeDepartement">Liste des départements</a>
                        <a href="template.php?page=ajout-departement">Ajouter département</a>
                        <a href="#budget-departement">Voir budget département</a>
                    </div>
                </li>
                <li class="dropdown">
                    <button class="dropbtn">Catégories</button>
                    <div class="dropdown-content">
                        <a href="template.php?page=listeCategorie">Liste des catégories</a>
                        <a href="template.php?page=ajout-categorie">Ajouter catégorie</a>
                    </div>
                </li>
                <li class="dropdown">
                    <button class="dropbtn">Budget</button>
                    <div class="dropdown-content">
                        <a href="template.php?page=ajout-prevision">Ajouter pr&eacute;vision</a>
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