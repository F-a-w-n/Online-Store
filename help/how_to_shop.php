<?php
// Original code by Fawn Barisic
// help page for buying books

// page specific values
$page_title = "How to Shop - Shamazon";
$page_desc = "Step-by-step shopping guide.";
$help_page = 'index';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="help-page">
    <!--text instructions-->
    <h1>How to Shop on Shamazon</h1>
    <ol>
        <li>Browse the <a href="/shamazon/index.php">homepage</a> or <a href="/shamazon/shop.php">shop page</a> to see all books.</li>
        <li>Click on any book cover to view details, options (Hardcover/Paperback/E-book), and price.</li>
        <li>Select your preferred format and quantity, then click <strong>"Add to Cart"</strong>.</li>
        <li>View your cart by clicking the Cart link in the navigation.</li>
        <li>Adjust quantities or remove items as needed.</li>
        <li>When ready, click <strong>"Proceed to Checkout"</strong>.</li>
        <li>Log in (or register) if you haven't already.</li>
        <li>Enter your shipping address and confirm your order.</li>
    </ol>
    <!-- video tutorial -->
    <div class="help-video">
        <h3>Watch the Tutorial</h3>
        <video controls width="100%" style="max-width: 600px; border-radius: 8px;" poster="/shamazon/assets/images/ui/video-poster.jpg">
            <source src="/shamazon/assets/media/how_to_shop.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <p class="video-caption">Learn how to rate and review books on Shamazon.</p>
    </div>
    <p><a href="/shamazon/help/index.php">← Back to Help Center</a></p>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>