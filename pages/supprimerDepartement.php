<main class="content">
    <section id="supprimer-departement">
        <div class="supprimer-container">
            <h1 class="main-title">Supprimer Département</h1>
            
            <?php if(isset($_GET['erreur'])): ?>
                <div class="erreur-message">
                    <p><?php echo htmlspecialchars($erreur); ?></p>
                    <a href="template.php?page=listeDepartement" class="btn-retour">Retour &agrave; la liste</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>