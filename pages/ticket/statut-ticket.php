<?php
    
    closeOldEnCoursTickets();
    $tickets = getAllTickets(); // Déjà filtré pour tickets validés
    $allStatus = getAllStatusTicket();
?>

<section id="statut-ticket">
    <h1 class="main-title">Gestion des Statuts de Tickets</h1>

    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>ID Ticket</th>
                <th>Discussion</th>
                <th>Description</th>
                <th>Date Création</th>
                <th>Statut Actuel</th>
                <th>Changer Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tickets)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Aucun ticket trouvé</td>
                </tr>
            <?php else: ?>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?= $ticket['idTicket'] ?></td>
                        <td>
                            <button onclick="window.location.href='ticket/discussion-ticket.php?idTicket=<?= $ticket['idTicket'] ?>&idAgent=<?= $ticket['idAgent'] ?>&idClient=<?= $ticket['idClient'] ?>'" 
                                    style="background-color:rgb(122, 167, 216); color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">
                                💬 Discussion
                            </button>
                        </td>
                        <td><?= htmlspecialchars(substr($ticket['description'] ?? '', 0, 100)) ?><?= strlen($ticket['description'] ?? '') > 100 ? '...' : '' ?></td>
                        <td><?= isset($ticket['dateCreation']) ? date('d/m/Y H:i', strtotime($ticket['dateCreation'])) : 'N/A' ?></td>
                        <td>
                            <?php
                                $statusLabel = '';
                                foreach ($allStatus as $status) {
                                    if ($status['idStatus'] == ($ticket['idStatus'] ?? '1')) {
                                        $statusLabel = $status['libelle'];
                                        break;
                                    }
                                }
                                echo htmlspecialchars($statusLabel);
                            ?>
                        </td>
                        <td>
                            <?php 
                                $currentStatus = $ticket['idStatus'] ?? '1';
                                if ($currentStatus == 4 || $currentStatus == 5): // Résolu ou Fermé
                            ?>
                                <span style="color: #666; font-style: italic;">Statut final - Non modifiable</span>
                            <?php else: ?>
                                <form action="ticket/traitement-statut-ticket.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="idTicket" value="<?= $ticket['idTicket'] ?>">
                                    <select name="newStatus" required>
                                        <?php foreach ($allStatus as $status): ?>
                                            <option value="<?= $status['idStatus'] ?>" 
                                                    <?= ($status['idStatus'] == $currentStatus) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($status['libelle']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="commentaire" placeholder="Commentaire (optionnel)" style="margin-left: 10px;">
                                    <button type="submit">Mettre à jour</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if (isset($_GET['success'])) { ?>
        <h3 style="color: green;"><?= $_GET['success'] ?></h3>
    <?php } ?>

    <?php if (isset($_GET['erreur'])) { ?>
        <h3 style="color: red;"><?= $_GET['erreur'] ?></h3>
    <?php } ?>
</section>