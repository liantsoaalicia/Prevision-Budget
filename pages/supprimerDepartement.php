<?php
// Configuration de la page
include("../inc/fonctions.php");

if(isset($_GET['id'])) {
    try {
        $resultat = supprimerDepartement($_GET['id']);
        if($resultat === true) {
            header('Location:template.php?page=listeDepartement');
            exit;
        }
    } catch(Exception $e) {
        $erreur = $e->getMessage();
    }
} else {
    $erreur = "Paramètre manquant";
}
?>

<main class="content">
    <section id="supprimer-departement">
        <div class="supprimer-container">
            <h1 class="main-title">Supprimer D&eacute;partement</h1>
            
            <?php if(isset($erreur)): ?>
                <div class="erreur-message">
                    <p><?php echo htmlspecialchars($erreur); ?></p>
                    <a href="template.php?page=departements" class="btn-retour">Retour &agrave; la liste</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>