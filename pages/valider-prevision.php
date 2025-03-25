<section id="valider-prevision">
    <div class="prevision-container">
        <h1 class="main-title">Liste des prévisions non validés</h1>

        <!-- Affichage des Dépenses -->
        <h2>Dépenses</h2>
        <div class="categories-table">
            <table>
                <thead>
                    <tr>
                        <th>Departement</th>
                        <th>Types</th>
                        <th>Nature</th>
                        <th>Periode</th>
                        <th>Prevision</th>
                        <th>Realisation</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Récupérer les catégories séparées
                    $categories = getAllInvalidPrevision();
                    $depenses = $categories['depenses'];

                    foreach ($depenses as $depense) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($depense['nom_departement']) . '</td>';
                        echo '<td>' . htmlspecialchars($depense['types']) . '</td>';
                        echo '<td>' . htmlspecialchars($depense['nature']) . '</td>';
                        echo '<td>' . htmlspecialchars($depense['nom_periode']) . '</td>';
                        echo '<td>' . htmlspecialchars($depense['prevision']) . '</td>';
                        echo '<td>' . htmlspecialchars($depense['realisation']) . '</td>';
                        echo '<td class="actions">';
                        echo '<a href="traitement-valider-prevision.php?id=' . $depense['idPrevision'] . '" class="icones">';
                        echo '    <img src="../assets/icon/valid.jpg" alt="Valider">';
                        echo '</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <br>

        <!-- Affichage des Recettes -->
        <h2>Recettes</h2>
        <div class="categories-table">
            <table>
                <thead>
                    <tr>
                        <th>Departement</th>
                        <th>Types</th>
                        <th>Nature</th>
                        <th>Periode</th>
                        <th>Prevision</th>
                        <th>Realisation</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $recettes = $categories['recettes'];

                    foreach ($recettes as $recette) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($recette['nom_departement']) . '</td>';
                        echo '<td>' . htmlspecialchars($recette['types']) . '</td>';
                        echo '<td>' . htmlspecialchars($recette['nature']) . '</td>';
                        echo '<td>' . htmlspecialchars($recette['nom_periode']) . '</td>';
                        echo '<td>' . htmlspecialchars($recette['prevision']) . '</td>';
                        echo '<td>' . htmlspecialchars($recette['realisation']) . '</td>';
                        echo '<td class="actions">';
                        echo '<a href="traitement-valider-prevision.php?id=' . $recette['idPrevision'] . '" class="icones">';
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
            <h3 style="color: green;"><?= $_GET['success'] ?></h3>
        <?php } ?>

        <?php if(isset($_GET['erreur'])) { ?>
            <h3 style="color: red;"><?= $_GET['erreur'] ?></h3>
        <?php } ?>
    </div>
</section>