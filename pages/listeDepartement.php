<?php
// Configuration de la page
    include("../inc/fonctions.php");
    $departements = listerDepartements();
?>

<section id="departements-list">
        <div class="departements-container">
            <h1 class="main-title">Liste des D&eacute;partements</h1>
            
            <div class="departements-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $departements = listerDepartements();
                        foreach ($departements as $departement) {
                            echo '<tr>';
                            echo '<td>' . $departement['idDepartement'] . '</td>';
                            echo '<td>' . $departement['nom'] . '</td>';
                            echo '<td class="actions">';
                            echo '<a href="template.php?page=modifierDepartement&id=' . $departement['idDepartement'] . '" class="icones">';
                            echo '    <img src="../assets/icon/modif.png" alt="Modifier">';
                            echo '</a>';
                            echo '<a href="traitement-suppression-dpt.php?id=' . $departement['idDepartement'] . '" class="icones">';
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