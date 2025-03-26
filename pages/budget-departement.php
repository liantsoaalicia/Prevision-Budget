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
    <h1 class="main-title">Budget des départements</h1>

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
            <?php if(count($previsions)>0) { ?>
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
                    <th>Prévision</th><th>Réalisation</th><th>Ecart</th>
                <?php endforeach; ?>
            </tr>

            <tr class="solde-debut">
                <td> Solde debut : </td>
                <?php foreach ($periodes as $periode): ?>
                    <td> <?= number_format(getSoldeDebutPrevision($d['idDepartement'], $periode['idPeriode']), 2); ?>
                    <td> <?= number_format(getSoldeDebutRealise($d['idDepartement'], $periode['idPeriode']), 2); ?>
                    <td> - </td>
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
                            $prevision = $prev['prevision'] ?? 0;
                            $realisation = $prev['realisation'] ?? 0;
                            $ecart = $prevision - $realisation;
                        ?>
                        <td><?= number_format($prevision, 2) ?></td>
                        <td><?= number_format($realisation, 2) ?></td>
                        <td><?= number_format($ecart, 2) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            
            <tr class="total-depense">
                <td> Total D&eacute;pense : </td>
                <?php foreach ($periodes as $periode): ?>
                        <td><?= number_format(getTotalDepensePrevue($d['idDepartement'], $periode['idPeriode']),2); ?></td>
                        <td><?= number_format(getTotalDepenseRealisee($d['idDepartement'], $periode['idPeriode']), 2); ?></td>
                        <td> - </td>
                    <?php endforeach; ?>
            </tr>
            

            <?php foreach ($categories['recettes'] as $cat): ?>
                <tr class="recette">
                    <td><?= htmlspecialchars($cat['types'] . ' - ' . $cat['nature']) ?></td>
                    <?php foreach ($periodes as $periode): ?>
                        <?php
                            $prev = array_filter($previsions, function($p) use ($cat, $periode) {
                                return $p['idCategorie'] == $cat['idCategorie'] && $p['idPeriode'] == $periode['idPeriode'];
                            });
                            $prev = reset($prev);
                            $prevision = $prev['prevision'] ?? 0;
                            $realisation = $prev['realisation'] ?? 0;
                            $ecart = $prevision - $realisation;
                        ?>
                        <td><?= number_format($prevision, 2) ?></td>
                        <td><?= number_format($realisation, 2) ?></td>
                        <td><?= number_format($ecart, 2) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>

            <tr class="total-recette">
                <td> Total Recette : </td>
                <?php foreach ($periodes as $periode): ?>
                        <td><?= number_format(getTotalRecettePrevue($d['idDepartement'], $periode['idPeriode']), 2); ?></td>
                        <td><?= number_format(getTotalRecetteRealisee($d['idDepartement'], $periode['idPeriode']), 2); ?></td>
                        <td> - </td>
                    <?php endforeach; ?>
            </tr>
            
            <tr class="solde-fin">
                <td> Solde Fin : </td>
                <?php foreach ($periodes as $periode): ?>
                    <td> <?= number_format(getSoldeFinPrevision($d['idDepartement'], $periode['idPeriode']),2); ?>
                    <td> <?= number_format(getSoldeFinRealise($d['idDepartement'], $periode['idPeriode']), 2); ?>
                    <td> - </td>
                <?php endforeach; ?>
            </tr>
        </table>
        <?php } ?>
        </div>
    <?php } ?>
    </div>
</section>