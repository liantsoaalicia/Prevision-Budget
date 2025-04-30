<section id="ajout-client">
    <h1 class="main-title">Ajout de client</h1>
    <form action="crm/traitement-ajout-client.php" method="post">
        <div>
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" required>
        </div>
        <div>
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" required>
        </div>
        <div>
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="age">Age :</label>
            <input type="number" name="age" id="age" required>
        </div>
        <div>
            <label for="sexe">Sexe :</label>
            <select name="sexe" id="sexe">
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
            </select>
        </div>
        <div>
            <label for="classe">Classe :</label>
            <select name="classe" id="classe">
                <option value="eleve">Élevé</option>
                <option value="moyen">Moyen</option>
                <option value="bas">Bas</option>
            </select>
        </div>
        <div>
            <label for="dateInscription">Date d'inscription :</label>
            <input type="date" name="dateInscription" id="dateInscription" required>
        </div>
        <button type="submit">Ajouter</button>
    </form>
</section>

<?php if(isset($_GET['success'])) { ?>
    <h3 style="color: green;"><?= $_GET['success'] ?></h3>
<?php } ?>

<?php if(isset($_GET['erreur'])) { ?>
    <h3 style="color: red;"><?= $_GET['erreur'] ?></h3>
<?php } ?>
