<?php 
    $isItFinance = verifyIfFinance($_SESSION['id']);
    $depart = getDepartementById($_SESSION['id']);
    $allDepartements = [$depart];
    $periodes = getAllPeriodes();
    $categories = listerCategories();
    $previsions = getPrevisionDepartement($_SESSION['id']);
?>

<section id="departements-budget">
    <div class="departements-container">
    <h1 class="main-title">Budget des d&eacute;partements</h1>

    <?php if($isItFinance) { 
        $allDepartements = getAllDepartements();
    } ?>
    <?php foreach($allDepartements as $d) { ?>
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
            <table border="1" class="table-budget">
            <tr>
                <th>Rubrique</th>
                <?php foreach ($periodes as $periode): ?>
                    <th colspan="3"> <?= afficherPeriode($periode['nom'], $periode['dateDebut'], $periode['dateFin']); ?> </th>
                <?php endforeach; ?>
            </tr>
            <tr>
                <th></th>
                <?php foreach ($periodes as $periode): ?>
                    <th>Pr&eacute;vision</th><th>R&eacute;alisation</th><th>Ecart</th>
                <?php endforeach; ?>
            </tr>
            
            <?php foreach ($categories['depenses'] as $cat): ?>
                <tr class="depense">
                    <td><?= htmlspecialchars($cat['types'] . ' - ' . $cat['nature']) ?></td>
                    <?php foreach ($periodes as $periode): ?>
                        <?php
                            $prev = array_filter($previsions, function($p) use ($cat, $periode) {
                                return $p['idCategorie'] == $cat['idCategorie'] && $p['idPeriode'] == $periode['idPeriode'];
                            });
                            $prev = reset($prev);
                            $prevision = $prev['montantPrevision'] ?? 0;
                            $realisation = $prev['montantRealisation'] ?? 0;
                            $ecart = $prevision - $realisation;
                        ?>
                        <td><?= number_format($prevision, 2) ?></td>
                        <td><?= number_format($realisation, 2) ?></td>
                        <td><?= number_format($ecart, 2) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>

            <?php foreach ($categories['recettes'] as $cat): ?>
                <tr class="recette">
                    <td><?= htmlspecialchars($cat['types'] . ' - ' . $cat['nature']) ?></td>
                    <?php foreach ($periodes as $periode): ?>
                        <?php
                            $prev = array_filter($previsions, function($p) use ($cat, $periode) {
                                return $p['idCategorie'] == $cat['idCategorie'] && $p['idPeriode'] == $periode['idPeriode'];
                            });
                            $prev = reset($prev);
                            $prevision = $prev['montantPrevision'] ?? 0;
                            $realisation = $prev['montantRealisation'] ?? 0;
                            $ecart = $prevision - $realisation;
                        ?>
                        <td><?= number_format($prevision, 2) ?></td>
                        <td><?= number_format($realisation, 2) ?></td>
                        <td><?= number_format($ecart, 2) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>

        </table>
        </div>
    <?php } ?>
    </div>
</section>