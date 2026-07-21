<?php
// Original code by Fawn Barisic
// account issues help page

// page-specific values
$page_title = "Account Help - Shamazon";
$page_desc = "Manage your account.";
$help_page = 'index';
require_once __DIR__ . '/../includes/header.php';

?>
<section class="help-page">
    <!--basic details for account management-->
    <h1>Account Management</h1>
    <h2>Registering</h2>
    <p>Click <strong>"Register"</strong> in the top menu, fill in your name, email, and password, then submit.</p>
    <h2>Logging In</h2>
    <p>Click <strong>"Login"</strong>, enter your email and password, and you're in.</p>
    <h2>Logging Out</h2>
    <p>Click <strong>"Logout"</strong> from any page.</p>
    <h2>Updating Your Profile</h2>
    <p>After login, click <strong>"Profile"</strong> to update your shipping address (used during checkout).</p>
    <p><a href="/shamazon/help/index.php">← Back to Help Center</a></p>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>