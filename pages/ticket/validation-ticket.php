<?php 
include_once '../inc/fonctions.php';
    $isItFinance = verifyIfFinance($_SESSION['id']);
    if (!$isItFinance) {
        echo '<div style="color:red;text-align:center;margin:2rem;font-size:1.2rem;">Accès réservé au département Finance.</div>';
        exit;
    }

$con = dbConnect();
$sql = "SELECT bt.*, t.sujet, t.description FROM budget_ticket bt JOIN tickets t ON bt.idTicket = t.idTicket WHERE bt.valideFinance = 0";
$tickets = $con->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Validation du ticket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idBudgetTicket'])) {
    $idBudgetTicket = (int)$_POST['idBudgetTicket'];
    $now = date('Y-m-d H:i:s');
    $stmt = $con->prepare("UPDATE budget_ticket SET valideFinance=1, dateValidation=? WHERE idBudgetTicket=?");
    $stmt->execute([$now, $idBudgetTicket]);
    header('Location: validation-ticket.php?success=1');
    exit;
}
?>
<section style="max-width:900px;margin:2rem auto;padding:2rem;background:#fff;border-radius:12px;box-shadow:0 2px 12px #8D6E6322;">
    <h2 style="color:#573d34;font-family:'Montserrat',Arial,sans-serif;text-align:center;margin-bottom:2rem;">Validation des budgets tickets</h2>
    <?php if(isset($_GET['success'])): ?>
        <div style="color:green;text-align:center;font-size:1.1rem;margin-bottom:1.5rem;">Ticket validé avec succès !</div>
    <?php endif; ?>
    <table style="width:100%;border-collapse:collapse;background:#f7f3f0;">
        <thead>
            <tr style="background:#8D6E63;color:#fff;">
                <th style="padding:12px;">ID</th>
                <th style="padding:12px;">Sujet</th>
                <th style="padding:12px;">Description</th>
                <th style="padding:12px;">Budget prévisionnel</th>
                <th style="padding:12px;">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($tickets)): ?>
            <tr><td colspan="5" style="text-align:center;padding:2rem;">Aucun ticket à valider</td></tr>
        <?php else: foreach ($tickets as $t): ?>
            <tr style="border-bottom:1px solid #D7CCC8;">
                <td style="padding:10px;">#<?= $t['idBudgetTicket'] ?></td>
                <td style="padding:10px;max-width:180px;word-break:break-word;"> <?= htmlspecialchars($t['sujet']) ?> </td>
                <td style="padding:10px;max-width:260px;word-break:break-word;"> <?= htmlspecialchars($t['description']) ?> </td>
                <td style="padding:10px;font-weight:600;color:#573d34;"> <?= number_format($t['budgetPrevisionnel'],2,',',' ') ?> Ar</td>
                <td style="padding:10px;">
                    <form method="post" style="margin:0;display:inline;">
                        <input type="hidden" name="idBudgetTicket" value="<?= $t['idBudgetTicket'] ?>">
                        <button type="submit" style="background:#573d34;color:#fff;padding:8px 18px;border:none;border-radius:6px;font-family:'Montserrat',Arial,sans-serif;font-size:1rem;cursor:pointer;transition:background 0.2s;">Valider</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</section>

