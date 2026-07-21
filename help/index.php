<?php
// Original code by Fawn Barisic
// help wiki home

// page specific values
$page_title = "Help Center - Shamazon";
$page_desc = "Step-by-step guides to using Shamazon.";
$help_page = 'index';
require_once __DIR__ . '/../includes/header.php';

?>
<section class="help-wiki">
    <!--links to each help page-->
    <h1>Shamazon Help Center</h1>
    <p>Welcome to our comprehensive help wiki. Choose a topic below:</p>
    <ul>
        <li><a href="/shamazon/help/how_to_shop.php">How to Shop</a></li>
        <li><a href="/shamazon/help/how_to_track.php">How to Track Your Order</a></li>
        <li><a href="/shamazon/help/how_to_rate.php">How to Rate a Product</a></li>
        <li><a href="/shamazon/help/account_help.php">Account Management</a></li>
        <li><a href="/shamazon/help/admin_docs.php">Admin Guide</a></li>
    </ul>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>