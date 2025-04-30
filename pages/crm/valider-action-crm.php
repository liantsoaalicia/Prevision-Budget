<section id="valider-action-crm">
    <div class="actions-container">
        <h1 class="main-title">Liste des Actions CRM non validees</h1>

        <div class="actions-table">
            <table>
                <thead>
                    <tr>
                        <th>Departement</th>
                        <th>Type d'action</th>
                        <th>Date de l'action</th>
                        <th>Cout previsionnel</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once '../inc/fonctions.php'; 

                    $actions = getAllInvalidActionsCrm();

                    foreach ($actions as $action) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($action['nom_departement']) . '</td>';
                        echo '<td>' . htmlspecialchars($action['typeAction']) . '</td>';
                        echo '<td>' . htmlspecialchars($action['dateAction']) . '</td>';
                        echo '<td>' . htmlspecialchars($action['coutsPrevision']) . '</td>';
                        echo '<td class="actions">';
                        echo '<a href="crm/traitement-valider-action-crm.php?id=' . $action['idAction'] . '" class="icones">';
                        echo '    <img src="../assets/icon/valid.jpg" alt="Valider">';
                        echo '</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php if(isset($_GET['success'])) { ?>
            <h3 style="color: green;"><?= htmlspecialchars($_GET['success']) ?></h3>
        <?php } ?>

        <?php if(isset($_GET['erreur'])) { ?>
            <h3 style="color: red;"><?= htmlspecialchars($_GET['erreur']) ?></h3>
        <?php } ?>
    </div>
</section>
