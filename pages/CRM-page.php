<?php
    session_start();
    include('../inc/fonctions.php');
    include('../inc/fonctionCommande.php');
    include('../inc/fonctionEvenement.php');
    include('../inc/fonctionTicket.php');
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            display: flex;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Montserrat', 'Quicksand', Arial, sans-serif;
            background: var(--background-light);
        }
        .main-content {
            margin-left: 250px;
            padding: 24px 32px;
            width: calc(100% - 250px);
            background: var(--background-light);
            font-family: 'Quicksand', Arial, sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', Arial, sans-serif;
            letter-spacing: 1px;
        }
        .sidebar-header h3 {
            font-family: 'Montserrat', Arial, sans-serif;
            font-size: 2rem;
            letter-spacing: 2px;
        }
        .sidebar-menu a, .dropdown-btn {
            font-family: 'Quicksand', Arial, sans-serif;
            font-size: 1.08rem;
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

            <li class="dropdown">
                <button class="dropdown-btn">Ticket ▼</button>
                <div class="dropdown-container">
                    <a href="CRM-page.php?page=ticket/statut-ticket">Status Ticket</a>
                    <a href="CRM-page.php?page=ticket/creation-ticket">Creation ticket</a>
                    <a href="CRM-page.php?page=ticket/assignation-ticket">Assignation d'un ticket</a>
                    <a href="CRM-page.php?page=ticket/filtre-ticket">Filtre des tickets</a>
                    <a href="CRM-page.php?page=ticket/rapport-performance">Rapport de performance</a>
                    <a href="CRM-page.php?page=ticket/tickets-resolus"> Liste tickets resolus </a>
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