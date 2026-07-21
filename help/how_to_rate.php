<?php
// Original code by Fawn Barisic
// help page for rating a book

// page specific values
$page_title = "How to Rate - Shamazon";
$page_desc = "Leave ratings and reviews.";
$help_page = 'index';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="help-page">
    <!--text instructions-->
    <h1>How to Rate a Product</h1>
    <ol>
        <li>Log in to your Shamazon account (you must be logged in to rate).</li>
        <li>Navigate to the product page of the book you want to review.</li>
        <li>Scroll down to the <strong>"Rate this product"</strong> section.</li>
        <li>Select a rating from 1 to 5 stars.</li>
        <li>Optionally, write a review in the text box.</li>
        <li>Click <strong>"Submit Rating"</strong>.</li>
        <li>Your rating and review will appear on the product page.</li>
    </ol>
    <!-- video tutorial -->
    <div class="help-video">
        <h3>Watch the Tutorial</h3>
        <video controls width="100%" style="max-width: 600px; border-radius: 8px;" poster="/shamazon/assets/images/ui/video-poster.jpg">
            <source src="/shamazon/assets/media/how_to_rate.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <p class="video-caption">Learn how to rate and review books on Shamazon.</p>
    </div>
    <p><a href="/shamazon/help/index.php">← Back to Help Center</a></p>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>