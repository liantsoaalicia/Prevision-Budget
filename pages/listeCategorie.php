<?php include("../inc/fonctions.php"); ?>

<section id="categories-list">
    <div class="categories-container">
        <h1 class="main-title">Liste des Catégories</h1>

        <!-- Affichage des Dépenses -->
        <h2>Dépenses</h2>
        <div class="categories-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Types</th>
                        <th>Nature</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Récupérer les catégories séparées
                    $categories = listerCategories();
                    $depenses = $categories['depenses'];

                    foreach ($depenses as $depense) {
                        echo '<tr>';
                        echo '<td>' . $depense['idCategorie'] . '</td>';
                        echo '<td>' . htmlspecialchars($depense['types']) . '</td>';
                        echo '<td>' . htmlspecialchars($depense['nature']) . '</td>';
                        echo '<td class="actions">';
                        echo '<a href="template.php?page=modifierCategorie&id=' . $depense['idCategorie'] . '" class="icones">';
                        echo '    <img src="../assets/icon/modif.png" alt="Modifier">';
                        echo '</a>';
                        echo '<a href="traitement-suppression-categorie.php?id=' . $depense['idCategorie'] . '" class="icones">';
                        echo '    <img src="../assets/icon/delete.png" alt="Supprimer">';
                        echo '</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Affichage des Recettes -->
        <h2>Recettes</h2>
        <div class="categories-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Types</th>
                        <th>Nature</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $recettes = $categories['recettes'];

                    foreach ($recettes as $recette) {
                        echo '<tr>';
                        echo '<td>' . $recette['idCategorie'] . '</td>';
                        echo '<td>' . htmlspecialchars($recette['types']) . '</td>';
                        echo '<td>' . htmlspecialchars($recette['nature']) . '</td>';
                        echo '<td class="actions">';
                        echo '<a href="template.php?page=modifierCategorie&id=' . $recette['idCategorie'] . '" class="icones">';
                        echo '    <img src="../assets/icon/modif.png" alt="Modifier">';
                        echo '</a>';
                        echo '<a href="traitement-suppression-categorie.php?id=' . $recette['idCategorie'] . '" class="icones">';
                        echo '    <img src="../assets/icon/delete.png" alt="Supprimer">';
                        echo '</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>