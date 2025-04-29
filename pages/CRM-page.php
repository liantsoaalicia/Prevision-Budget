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
    $isItFinance = verifyIfFinance($_SESSION['id']);
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
                <li class="dept-connecte">
                    <span class="point-vert"></span>
                    <span class="nom-dept"><?= htmlspecialchars($nomDeptConnecte) ?></span>
                </li>
                <li class="dropdown">
                    <button class="dropbtn">Client</button>
                    <div class="dropdown-content">
                        <a href="CRM-page.php?page=ajout-client">Ajouter Client</a>
                        <a href="CRM-page.php?page=ajout-reaction-crm">Reaction CRM</a>
                        <a href="CRM-page.php?page=ajout-retour-client">Retour Client</a>
                    </div>
                </li>
                <li class="dropdown">
                    <button class="dropbtn">Produit</button>
                    <div class="dropdown-content">
                        <a href="CRM-page.php?page=ajout-categorie-produit">Ajouter Catégorie Produit</a>
                        <a href="CRM-page.php?page=ajout-produit">Ajouter Produit</a>
                    </div>
                </li>
                <li class="dropdown">
                    <button class="dropbtn">Commande</button>
                    <div class="dropdown-content">
                        <a href="CRM-page.php?page=ajout-commande">Ajouter Commande</a>
                        <?php if($isItFinance) { ?>
                            <a href="CRM-page.php?page=valider-prevision">Valider les previsions</a>
                        <?php } ?>
                    </div>
                </li>
                <li class="dropdown">
                    <form action="traitement-deconnection.php">
                        <button class="dropbtn" type="submit">Deconnexion</button>
                    </form>
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