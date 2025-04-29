<section id="statistique1">
    <h1 class="main-title">Les produits vendus</h1>
    <form action="" method="post"> 
        <select name="mois" id="mois">
            <option value="#">Mois</option>
            <option value="1" <?= isset($_POST['mois']) && $_POST['mois'] == '1' ? 'selected' : '' ?>>Janvier</option>
            <option value="2" <?= isset($_POST['mois']) && $_POST['mois'] == '2' ? 'selected' : '' ?>>Fevrier</option>
            <option value="3" <?= isset($_POST['mois']) && $_POST['mois'] == '3' ? 'selected' : '' ?>>Mars</option>
            <option value="4" <?= isset($_POST['mois']) && $_POST['mois'] == '4' ? 'selected' : '' ?>>Avril</option>
            <option value="5" <?= isset($_POST['mois']) && $_POST['mois'] == '5' ? 'selected' : '' ?>>Mai</option>
            <option value="6" <?= isset($_POST['mois']) && $_POST['mois'] == '6' ? 'selected' : '' ?>>Juin</option>
            <option value="7" <?= isset($_POST['mois']) && $_POST['mois'] == '7' ? 'selected' : '' ?>>Juillet</option>
            <option value="8" <?= isset($_POST['mois']) && $_POST['mois'] == '8' ? 'selected' : '' ?>>Aout</option>
            <option value="9" <?= isset($_POST['mois']) && $_POST['mois'] == '9' ? 'selected' : '' ?>>Septembre</option>
            <option value="10" <?= isset($_POST['mois']) && $_POST['mois'] == '10' ? 'selected' : '' ?>>Octobre</option>
            <option value="11" <?= isset($_POST['mois']) && $_POST['mois'] == '11' ? 'selected' : '' ?>>Novembre</option>
            <option value="12" <?= isset($_POST['mois']) && $_POST['mois'] == '12' ? 'selected' : '' ?>>Decembre</option>            
        </select>
        
        <select name="sexe" id="sexe">
            <option value="">Sexe</option>
            <option value="Homme" <?= isset($_POST['sexe']) && $_POST['sexe'] == 'Homme' ? 'selected' : '' ?>>Homme</option>
            <option value="Femme" <?= isset($_POST['sexe']) && $_POST['sexe'] == 'Femme' ? 'selected' : '' ?>>Femme</option>
        </select>
        
        <select name="classe" id="classe">
            <option value="">Classe</option>
            <option value="eleve" <?= isset($_POST['classe']) && $_POST['classe'] == 'eleve' ? 'selected' : '' ?>>Elevée</option>
            <option value="moyen" <?= isset($_POST['classe']) && $_POST['classe'] == 'moyen' ? 'selected' : '' ?>>Moyenne</option>
            <option value="bas" <?= isset($_POST['classe']) && $_POST['classe'] == 'bas' ? 'selected' : '' ?>>Basse</option>
        </select>
        
        <button type="submit">Voir les statistiques</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rootPath = dirname(__DIR__, 3); 
        require $rootPath . '/inc/fonctionStatistique.php';
        $mois = !empty($_POST['mois']) ? $_POST['mois'] : null;
        $sexe = !empty($_POST['sexe']) ? $_POST['sexe'] : null;
        $classe = !empty($_POST['classe']) ? $_POST['classe'] : null;

        $stat1 = getStatistique1($mois, $sexe, $classe);
        
        if (!empty($stat1)) {
            echo '<div class="resultats-tableau">';
            echo '<h2>Résultats</h2>';
            echo '<table border="1">';
            echo '<thead><tr>
                    <th>Mois</th>
                    <th>Sexe</th>
                    <th>Classe</th>
                    <th>Produit</th>
                    <th>Quantité vendue</th>
                  </tr></thead>';
            echo '<tbody>';
            
            foreach ($stat1 as $row) {
                echo '<tr>';
                echo '<td>' . $row['mois'] . '</td>';
                echo '<td>' . $row['sexe'] . '</td>';
                echo '<td>' . $row['classe'] . '</td>';
                echo '<td>' . $row['produit'] . '</td>';
                echo '<td>' . $row['quantite_vendue'] . '</td>';
                echo '</tr>';
            }
            
            echo '</tbody></table></div>';
        } else {
            echo '<p>Aucun résultat trouvé pour ces critères.</p>';
        }
    }
    ?>
</section>