<?php 
    include('../inc/fonctionClient.php');
    include('../inc/fonctionReactionRetour.php');

    $commandes = getCommandesClients();
    $retours = getAllRetoursClients();
?>

<section id="ajout-retour-client">
    <h1 class="main-title">Ajouter un retour client</h1>

    <form action="crm/traitement-ajout-retour-client.php" method="post">
        <div>
            <label for="idCommande">Commande :</label>
            <select name="idCommande" id="idCommande" required>
                <?php foreach($commandes as $cmd) { ?>
                    <option value="<?= $cmd['idCommande'] ?>">
                        <?= $cmd['idCommande'] . ' - ' . $cmd['prenom'] . ' ' . $cmd['nom'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div>
            <label for="avis">Avis :</label>
            <select name="avis" id="avis" required>
                <option value="tsara">tsara</option>
                <option value="ratsy">ratsy</option>
            </select>
        </div>

        <div>
            <label for="commentaire">Commentaire :</label>
            <textarea name="commentaire" id="commentaire" required></textarea>
        </div>

        <button type="submit">Ajouter le retour</button>
    </form>

    <?php if(isset($_GET['success'])) { ?>
        <p style="color: green"><?= $_GET['success'] ?></p>
    <?php } ?>
    <?php if(isset($_GET['erreur'])) { ?>
        <p style="color: red"><?= $_GET['erreur'] ?></p>
    <?php } ?>

    <h2>Liste des retours</h2>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Client</th>
                <th>Commande</th>
                <th>Date</th>
                <th>Avis</th>
                <th>Commentaire</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($retours as $r) { ?>
                <tr>
                    <td><?= $r['prenom'] . ' ' . $r['nom'] ?></td>
                    <td><?= $r['idCommande'] ?></td>
                    <td><?= $r['dateRetour'] ?></td>
                    <td><?= $r['avis'] ?></td>
                    <td><?= htmlspecialchars($r['commentaire']) ?></td>
                    <td>
                        <a href="crm/supprimer-retour-client.php?id=<?= $r['idRetour'] ?>" 
                           onclick="return confirm('Supprimer ce retour ?')">
                            🗑️
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>
