<?php
    session_start();
    include('../inc/fonctions.php');
    include('../inc/fonctionCommande.php');
    include('../inc/fonctionEvenement.php');
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
                        <a href="CRM-page.php?page=crm/ajout-client">Ajouter Client</a>
                        <a href="CRM-page.php?page=crm/ajout-reaction-crm">Reaction CRM</a>
                        <a href="CRM-page.php?page=crm/ajout-retour-client">Retour Client</a>
                    </div>
                </li>
                <li class="dropdown">
                    <button class="dropbtn">Produit</button>
                    <div class="dropdown-content">
                        <a href="CRM-page.php?page=crm/ajout-categorie-produit">Ajouter Catégorie Produit</a>
                        <a href="CRM-page.php?page=crm/ajout-produit">Ajouter Produit</a>
                    </div>
                </li>
                <li class="dropdown">
                    <button class="dropbtn">Commande</button>
                    <div class="dropdown-content">
                        <a href="CRM-page.php?page=crm/ajout-commande">Ajouter Commande</a>
                        <a href="CRM-page.php?page=crm/liste-commande">Liste des commandes</a>
                    </div>
                </li>
                <li class="dropdown">
                    <button class="dropbtn">Statistiques</button>
                    <div class="dropdown-content">
                        <a href="CRM-page.php?page=crm/statistiques/statistique-produits-vendus">Produits vendus</a>
                        <a href="CRM-page.php?page=crm/statistiques/statistique-nouveaux-clients">La liste des clients par mois</a>
                        <a href="CRM-page.php?page=crm/statistiques/statistique-retours-clients">Retours client</a>
                        <a href="CRM-page.php?page=crm/statistiques/statistique-total-clients">Nombre total de clients par segment</a>
                        <a href="CRM-page.php?page=crm/statistiques/statistique-cout-crm">Cout total des actions CRM</a>
                    </div>
                </li>
                <li class="dropdown">
                    <button class="dropbtn">ActionsCRM</button>
                    <div class="dropdown-content">
                        <a href="CRM-page.php?page=crm/ajout-action-crm">Ajouter action</a>
                        <a href="CRM-page.php?page=crm/ajout-evenement">Ajouter événement</a>
                        <?php if($isItFinance){ ?>
                            <a href="CRM-page.php?page=crm/valider-action-crm">Valider des actions</a>
                        <?php } ?>
                    </div>
                </li>
                <li class="dropdown">
                    <form action="template.php">
                        <button class="dropbtn" type="submit">Budget</button>
                    </form>
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