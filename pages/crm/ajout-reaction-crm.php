<?php 
    include('../inc/fonctionClient.php');
    include('../inc/fonctionReactionRetour.php');

    $clients = C_getAllClients();
    $reactions = getAllReactionsCRM();
?>

<section id="ajout-reaction-crm">
    <h1 class="main-title">Ajouter une reaction CRM</h1>

    <form action="crm/traitement-ajout-reaction-crm.php" method="post">
        <div>
            <label for="idClient">Client : </label>
            <select name="idClient" id="idClient" required>
                <?php foreach($clients as $client) { ?>
                    <option value="<?= $client['idClient'] ?>">
                        <?= $client['prenom'] . ' ' . $client['nom'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div>
            <label for="reaction">Reaction :</label>
            <select name="reaction" id="reaction" required>
                <option value="tsara">Tsara</option>
                <option value="ratsy">Ratsy</option>
            </select>
        </div>

        <button type="submit">Ajouter la réaction</button>
    </form>

    <?php if(isset($_GET['success'])) { ?>
        <p style="color: green"><?= $_GET['success'] ?></p>
    <?php } ?>
    <?php if(isset($_GET['erreur'])) { ?>
        <p style="color: red"><?= $_GET['erreur'] ?></p>
    <?php } ?>

    <h2>Liste des reactions</h2>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Client</th>
                <th>Reaction</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($reactions as $r) { ?>
                <tr>
                    <td><?= $r['prenom'] . ' ' . $r['nom'] ?></td>
                    <td><?= $r['reaction'] ?></td>
                    <td>
                        <a href="crm/supprimer-reaction-crm.php?id=<?= $r['idReaction'] ?>" 
                           onclick="return confirm('Supprimer cette réaction ?')">
                            🗑️
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>
