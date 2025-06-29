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
    <style>
        body {
            display: flex;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background-color: #8D6E63;
            color: white;
            height: 100vh;
            position: fixed;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 20px;
            background-color: #573d34;
            text-align: center;
        }
        
        .sidebar-menu {
            padding: 0;
            list-style: none;
        }
        
        .sidebar-menu li {
            border-bottom: 1px solid #8D6E63;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 15px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover {
            background-color: #D7CCC8;
            padding-left: 25px;
            color: #333;
        }
        
        .dropdown-btn {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            color: white;
            padding: 15px 20px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .dropdown-btn:hover {
            background-color: #A1887F;
            padding-left: 25px;
        }
        
        .dropdown-container {
            display: none;
            background-color: #A1887F;
            padding-left: 20px;
        }
        
        .active {
            background-color: #6D4C41;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }
        
        .dept-info {
            padding: 15px;
            background-color: #8D6E63;
            text-align: center;
            font-weight: bold;
        }
        
        .point-vert {
            height: 10px;
            width: 10px;
            background-color: #2ecc71;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Gestion CRM</h3>
        </div>
        
        <div class="dept-info">
            <span class="point-vert"></span>
            <span class="nom-dept"><?= htmlspecialchars($nomDeptConnecte) ?></span>
        </div>
        
        <ul class="sidebar-menu">
            <li class="dropdown">
                <button class="dropdown-btn">Client ▼</button>
                <div class="dropdown-container">
                    <a href="CRM-page.php?page=crm/ajout-client">Ajouter Client</a>
                    <a href="CRM-page.php?page=crm/ajout-retour-client">Retour Client</a>
                </div>
            </li>
            
            <li class="dropdown">
                <button class="dropdown-btn">Produit ▼</button>
                <div class="dropdown-container">
                    <a href="CRM-page.php?page=crm/ajout-categorie-produit">Ajouter Catégorie Produit</a>
                    <a href="CRM-page.php?page=crm/ajout-produit">Ajouter Produit</a>
                </div>
            </li>
            
            <li class="dropdown">
                <button class="dropdown-btn">Commande ▼</button>
                <div class="dropdown-container">
                    <a href="CRM-page.php?page=crm/ajout-commande">Ajouter Commande</a>
                    <a href="CRM-page.php?page=crm/liste-commande">Liste des commandes</a>
                </div>
            </li>
            
            <li class="dropdown">
                <button class="dropdown-btn">Statistiques ▼</button>
                <div class="dropdown-container">
                    <a href="CRM-page.php?page=crm/statistiques/statistique-produits-vendus">Produits vendus</a>
                    <a href="CRM-page.php?page=crm/statistiques/statistique-nouveaux-clients">Clients par mois</a>
                    <a href="CRM-page.php?page=crm/statistiques/statistique-retours-clients">Retours client</a>
                    <a href="CRM-page.php?page=crm/statistiques/statistique-total-clients">Clients par segment</a>
                    <a href="CRM-page.php?page=crm/statistiques/statistique-cout-crm">Coût actions CRM</a>
                </div>
            </li>
            
            <li class="dropdown">
                <button class="dropdown-btn">Actions CRM ▼</button>
                <div class="dropdown-container">
                    <a href="CRM-page.php?page=crm/ajout-action-crm">Ajouter action</a>
                    <a href="CRM-page.php?page=crm/ajout-evenement">Ajouter événement</a>
                    <?php if($isItFinance){ ?>
                        <a href="CRM-page.php?page=crm/valider-action-crm">Valider des actions</a>
                    <?php } ?>
                </div>
            </li>
            
            <li>
                <a href="template.php">Budget</a>
            </li>
            
            <li>
                <a href="traitement-deconnection.php">Déconnexion</a>
            </li>
        </ul>
    </div>

    <main class="main-content">
        <?php include("$page"); ?>
    </main>

    <script src="../assets/script.js"></script>
    <script>
        var dropdown = document.getElementsByClassName("dropdown-btn");
        var i;
        
        for (i = 0; i < dropdown.length; i++) {
            dropdown[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var dropdownContent = this.nextElementSibling;
                if (dropdownContent.style.display === "block") {
                    dropdownContent.style.display = "none";
                } else {
                    dropdownContent.style.display = "block";
                }
            });
        }
        
        document.addEventListener("DOMContentLoaded", function() {
            var currentPage = "<?= $page ?>";
            var links = document.querySelectorAll('.sidebar-menu a');
            
            links.forEach(function(link) {
                if (link.getAttribute('href').includes(currentPage.replace('.php', ''))) {
                    link.classList.add('active');
                    var dropdown = link.closest('.dropdown-container');
                    if (dropdown) {
                        dropdown.style.display = 'block';
                        dropdown.previousElementSibling.classList.add('active');
                    }
                }
            });
        });
    </script>
</body>
</html>