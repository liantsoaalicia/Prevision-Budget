<?php 
    $agents = getAllAgents();
    $tickets = getAllTickets(); // Déjà filtré pour tickets validés
?>

<section id="assignation-ticket">
    <h1 class="main-title">Assignation d'un ticket a un agent</h1>
    
    <form action="ticket/traitement-assignation-ticket.php" method="post">
        <div>
            <label for="ticket">Ticket : </label>
            <select name="id_ticket" id="ticket">
                <?php foreach($tickets as $t) { ?>
                    <option value="<?= $t['idTicket'] ?>"><?= $t['sujet'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div>
            <label for="agent">Agent : </label>
            <select name="id_agent" id="agent">
                <?php foreach($agents as $a) { ?>
                    <option value="<?= $a['idAgent'] ?>"><?= $a['nom'] ?></option>
                <?php } ?>
            </select>
        </div>

        <button type="submit">Assigner</button>
    </form>
    
    <?php if(isset($_GET['success'])) { ?>
        <h3 style="color: green;"><?= $_GET['success'] ?></h3>
    <?php } ?>

    <?php if(isset($_GET['erreur'])) { ?>
        <h3 style="color: red;"><?= $_GET['erreur'] ?></h3>
    <?php } ?>
</section>