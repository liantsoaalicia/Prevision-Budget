<?php
    $events = getAllEvenement();
?>
<section id="ajout-action-crm">
    <h1 class="main-title">Ajout d'une action CRM</h1>
    <form action="crm/traitement-ajout-action-crm.php" method="post">
        <div>
            <label for="typeAction">Type d'action :</label>
            <input type="text" name="typeAction" id="typeAction" required>
        </div>
        <div>
            <label for="etapeAction">Etape :</label>
            <select name="etapeAction" id="etapeAction">
                <option value="Avant">Avant</option>
                <option value="Pendant">Pendant</option>
                <option value="Apres">Apres</option>
            </select>
        </div>

        <div>
            <label for="idEvenement">Evenement :</label>
            <select id="idEvenement" name="idEvenement" required>
                <option value="">-- Sélectionnez un evenement --</option>
                <?php foreach ($events as $event): ?>
                    <option value="<?= $event['idEvenement'] ?>">
                        <?= $event['nomEvenement'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="dateAction">Date de l'action :</label>
            <input type="date" name="dateAction" id="dateAction" required>
        </div>
        <div>
            <label for="coutsPrevision">Couts prevus :</label>
            <input type="number" step="0.01" name="coutsPrevision" id="coutsPrevision" required>
        </div>
        <div>
            <label for="coutsRealisation">Couts realises :</label>
            <input type="number" step="0.01" name="coutsRealisation" id="coutsRealisation" required>
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
