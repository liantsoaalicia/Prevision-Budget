<?php 
    $clients = getAllClients();

?>

<section id="creation-ticket">
    <h1 class="main-title">Création d'un ticket</h1>

    <form action="ticket/traitement-creation-ticket.php" method="post" enctype="multipart/form-data">
        <div>
            <label for="titre">Sujet / titre du probleme :</label>
            <input type="text" id="titre" name="titre" required>
        </div>
        <div>
            <label for="description">Description détaillée : 
                <textarea name="description" id="description"></textarea>
            </label>
        </div>
        <div>
            <label for="priorite">Priorite
                <select name="priorite" id="priorite">
                    <option value="basse">Basse</option>
                    <option value="normale">Normale</option>
                    <option value="haute">Haute</option>
                </select>
            </label>
        </div>
        <div>
            <label for="fichier">Fichier a joindre</label>
            <input type="file" name="file" id="file">
        </div>

        <div>
            <label for="client">Client</label>
            <select name="id_client" id="client">
                <?php foreach($clients as $c) {  ?>
                    <option value="<?= $c['idClient'] ?>"><?= $c['nom'] . $c['prenom'] ?></option>
                <?php } ?>
            </select>
        </div>

        <button type="submit">Creer</button>
    </form>

    <?php if(isset($_GET['success'])) { ?>
        <h3 style="color: green;"><?= $_GET['success'] ?></h3>
    <?php } ?>

    <?php if(isset($_GET['erreur'])) { ?>
        <h3 style="color: red;"><?= $_GET['erreur'] ?></h3>
    <?php } ?>
</section>