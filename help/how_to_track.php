<?php
// Original code by Fawn Barisic
// help page for tracking orders

// page specific values
$page_title = "How to Track - Shamazon";
$page_desc = "Track your orders.";
$help_page = 'index';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="help-page">
    <!--text instructions-->
    <h1>How to Track Your Order</h1>
    <ol>
        <li>Log in to your Shamazon account.</li>
        <li>Click <strong>"My Orders"</strong> in the main navigation.</li>
        <li>You'll see a list of all your past orders.</li>
        <li>Click the <strong>"Track"</strong> link next to the order you want to check.</li>
        <li>The tracking page shows the current status (Pending, Processing, Shipped, Delivered).</li>
        <li>If a tracking number has been assigned, it will be displayed there.</li>
    </ol>
    <!-- video tutorial -->
    <div class="help-video">
        <h3>Watch the Tutorial</h3>
        <video controls width="100%" style="max-width: 600px; border-radius: 8px;" poster="/shamazon/assets/images/ui/video-poster.jpg">
            <source src="/shamazon/assets/media/how_to_track.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <p class="video-caption">Learn how to rate and review books on Shamazon.</p>
    </div>
    <p><a href="/shamazon/help/index.php">← Back to Help Center</a></p>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>