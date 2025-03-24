<?php 
    $isItFinance = verifyIfFinance($_SESSION['id']);
    $allDepartements = getAllDepartements();
    $depart = getDepartementById($_SESSION['id']);
    $budgetCeDepart = getPrevisionDepartement($_SESSION['id']);
?>

<section id="departements-budget">
    <div class="departements-container">
    <h1 class="main-title">Budget des départements</h1>

    <?php if($isItFinance) { 
        foreach($allDepartements as $d) { ?>
            <h2><?= $d['nom'] ?></h2>
            <?php $previsions = getPrevisionDepartement($d['idDepartement']); 
            $data = [];

            foreach($previsions as $p) {
                $periode = $p['nom_periode'];
                $type = $p['types'];

                if(!isset($data[$periode])) {
                    $data[$periode] = ['Recette' => [], 'Depense' => []];
                }
                $data[$periode][$type][] = $p;
            }
            
            ?>

        <div class="budget-table">
            <table border="1">
                <thead>
                    <tr>
                        <th rowspan="2">Rubrique</th>
                        <?php foreach ($data as $periode => $values) { ?>
                            <th colspan="3"><?= $periode ?></th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <?php foreach ($data as $periode => $values) { ?>
                            <th>Prévision</th>
                            <th>Réalisation</th>
                            <th>Écart</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Affichage des RECETTES -->
                    <tr><td colspan="<?= 2 + count($data) * 3 ?>"><strong>RECETTES</strong></td></tr>
                    <?php
                    $rubriques = [];
                    foreach ($data as $periode => $values) {
                        foreach ($values['Recette'] as $p) {
                            $rubriques[$p['categorie']][$periode] = $p;
                        }
                    }
                    foreach ($rubriques as $categorie => $values) { ?>
                        <tr>
                            <td><?= $categorie ?></td>
                            <?php foreach ($data as $periode => $per) { 
                                $p = $values[$periode] ?? ['prevision' => 0, 'realisation' => 0];
                                $ecart = $p['realisation'] - $p['prevision']; ?>
                                <td><?= number_format($p['prevision'], 0, ',', ' ') ?></td>
                                <td><?= number_format($p['realisation'], 0, ',', ' ') ?></td>
                                <td><?= number_format($ecart, 0, ',', ' ') ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>

                    <!-- Affichage des DÉPENSES -->
                    <tr><td colspan="<?= 2 + count($data) * 3 ?>"><strong>DÉPENSES</strong></td></tr>
                    <?php
                    $rubriques = [];
                    foreach ($data as $periode => $values) {
                        foreach ($values['Dépense'] as $p) {
                            $rubriques[$p['categorie']][$periode] = $p;
                        }
                    }
                    foreach ($rubriques as $categorie => $values) { ?>
                        <tr>
                            <td><?= $categorie ?></td>
                            <?php foreach ($data as $periode => $per) { 
                                $p = $values[$periode] ?? ['prevision' => 0, 'realisation' => 0];
                                $ecart = $p['realisation'] - $p['prevision']; ?>
                                <td><?= number_format($p['prevision'], 0, ',', ' ') ?></td>
                                <td><?= number_format($p['realisation'], 0, ',', ' ') ?></td>
                                <td><?= number_format($ecart, 0, ',', ' ') ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>


    <?php } 
    } 
    
    else { ?>
        <h2><?= $depart['nom'] ?></h2>
    <?php } ?>

    </div>
</section>