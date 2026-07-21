<?php
// Original code by Fawn Barisic
// faq page

require_once __DIR__ . '/includes/db.php';

// page specific values
$page_title = "FAQ - Shamazon";
$page_desc = "Frequently asked questions about Shamazon.";
$help_page = 'index';
require_once __DIR__ . '/includes/header.php';

// source for FAQs and answers
$faqs = [
    [
        'q' => 'What is Shamazon?',
        'a' => 'Shamazon is an advanced e-commerce bookstore that offers over 20 curated titles with multiple format options (Hardcover, Paperback, E-book). It features dynamic themes, a responsive design, and a full admin panel.'
    ],
    [
        'q' => 'How do I create an account?',
        'a' => 'Click the "Register" link in the top navigation. Fill in your name, email, and password, then submit the form. You\'ll be able to log in immediately.'
    ],
    [
        'q' => 'How do I add items to my cart?',
        'a' => 'Browse our catalog, click on any book to view its details, select your preferred format and quantity, then click "Add to Cart". You can view your cart at any time by clicking the cart icon in the navigation.'
    ],
    [
        'q' => 'What payment methods do you accept?',
        'a' => 'For this demo, we simulate the checkout process. In a production environment, we would integrate with payment gateways like Stripe or PayPal.'
    ],
    [
        'q' => 'How do I track my order?',
        'a' => 'Log in to your account, go to "My Orders", and click "Track" next to the order you want to check. You\'ll see the current status (Pending, Processing, Shipped, Delivered).'
    ],
    [
        'q' => 'How do I change my password?',
        'a' => 'Log in and go to your "Profile" page. Scroll to the "Change Password" section, enter your current password and your new password, then click "Change Password".'
    ],
    [
        'q' => 'How do I switch the site theme?',
        'a' => 'The site theme can be changed by administrators via the Admin → Switch Themes page. Users can also set their own theme preference (if implemented).'
    ],
    [
        'q' => 'How do I rate a product?',
        'a' => 'Log in and navigate to the product page. Scroll down to the "Rate this product" section, select your rating, and optionally add a review. Click "Submit Rating" to save it.'
    ],
    [
        'q' => 'Is my data secure?',
        'a' => 'Yes! We use password hashing (bcrypt) to protect your credentials. Your session is encrypted and we follow best practices for web security.'
    ],
    [
        'q' => 'What if I have more questions?',
        'a' => 'Check our <a href="/shamazon/help/index.php">Help Wiki</a> for detailed guides, or <a href="/shamazon/contact.php">contact us</a> directly.'
    ]
];
?>

<section class="faq-page">
    <h1>Frequently Asked Questions</h1>
    <p>Find answers to the most common questions about Shamazon.</p>
    
    <!--iterates over faqs and makes a dropdown box to answer-->
    <div class="faq-grid">
        <?php foreach ($faqs as $index => $faq): ?>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFAQ(this)">
                    <span class="faq-icon">▼</span>
                    <span><?php echo htmlspecialchars($faq['q']); ?></span>
                </button>
                <div class="faq-answer" style="display: none;">
                    <p><?php echo $faq['a']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="faq-footer">
        <p>Still have questions? <a href="/shamazon/contact.php">Contact us</a> and we'll be happy to help!</p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>