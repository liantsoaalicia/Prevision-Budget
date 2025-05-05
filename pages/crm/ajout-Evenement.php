<section id="ajout-evenement">
    <h1 class="main-title">Ajout d'événement</h1>
    <form action="crm/traitement-ajout-evenement.php" method="post">
        <div>
            <label for="nomEvenement">Nom de l'événement :</label>
            <input type="text" name="nomEvenement" id="nomEvenement" required>
        </div>
        <div>
            <label for="dateDebut">Date de début :</label>
            <input type="date" name="dateDebut" id="dateDebut" required>
        </div>
        <div>
            <label for="dateFin">Date de fin :</label>
            <input type="date" name="dateFin" id="dateFin" required>
        </div>
        <button type="submit">Ajouter</button>
    </form>
</section>

<?php if(isset($_GET['success'])) { ?>
    <h3 style="color: green;"><?= htmlspecialchars($_GET['success']) ?></h3>
<?php } ?>

<?php if(isset($_GET['erreur'])) { ?>
    <h3 style="color: red;"><?= htmlspecialchars($_GET['erreur']) ?></h3>
<?php } ?>
