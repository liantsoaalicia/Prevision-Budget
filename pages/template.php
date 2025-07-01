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
            <h3>Gestion Budgétaire</h3>
        </div>
        
        <div class="dept-info">
            <span class="point-vert"></span>
            <span class="nom-dept"><?= htmlspecialchars($nomDeptConnecte) ?></span>
        </div>
        
        <ul class="sidebar-menu">
            <li class="dropdown">
                <button class="dropdown-btn">Départements ▼</button>
                <div class="dropdown-container">
                    <a href="template.php?page=listeDepartement">Liste des départements</a>
                    <a href="template.php?page=ajout-departement">Ajouter département</a>
                    <a href="template.php?page=budget-departement">Voir budget département</a>
                </div>
            </li>
            
            <li class="dropdown">
                <button class="dropdown-btn">Catégories ▼</button>
                <div class="dropdown-container">
                    <a href="template.php?page=listeCategorie">Liste des catégories</a>
                    <a href="template.php?page=ajout-categorie">Ajouter catégorie</a>
                </div>
            </li>
            
            <li class="dropdown">
                <button class="dropdown-btn">Budget ▼</button>
                <div class="dropdown-container">
                    <a href="template.php?page=ajout-prevision">Ajouter prévision</a>
                    <a href="template.php?page=soldes">Voir soldes</a>
                    <a href="#budget-total">Voir budget global</a>
                    <?php if($isItFinance) { ?>
                        <a href="template.php?page=valider-prevision">Valider les previsions</a>
                    <?php } ?>
                </div>
            </li>
            
            <li>
                <a href="CRM-page.php">CRM</a>
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
        // Script pour gérer les dropdowns de la sidebar
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