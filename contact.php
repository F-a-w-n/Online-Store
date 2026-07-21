<?php
// Original code by Fawn Barisic
// contact us
require_once __DIR__ . '/includes/db.php';

// page specific values
$page_title = "Contact Us - Shamazon";
$page_desc = "Get in touch with the Shamazon team.";
$help_page = 'index';
$success = '';
$error = '';
require_once __DIR__ . '/includes/header.php';

// send fake contact to session info
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // validate
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // in reality, you'd send an email here, this just stores in the session
        $_SESSION['contact_message'] = [
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message
        ];
        $success = 'Thank you for your message, ' . htmlspecialchars($name) . '! We\'ll get back to you soon.';
    }
}
?>

<section class="contact-page">
    <h1>Contact Us</h1>
    <p>Have questions, feedback, or just want to say hello? We'd love to hear from you!</p>
    
    <!--success/error messages-->
    <?php if (isset($_GET['success']) || $success): ?>
        <div class="success">
            <?php echo $success ?: 'Thank you for your message! We\'ll get back to you soon.'; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <!-- contact form -->
    <div class="contact-grid">
        <form method="POST" class="contact-form">
            <div class="form-group">
                <label for="name">Your Name *</label>
                <input type="text" id="name" name="name" 
                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Your Email *</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="subject">Subject *</label>
                <input type="text" id="subject" name="subject" 
                       value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="message">Message *</label>
                <textarea id="message" name="message" rows="5" required><?php 
                    echo htmlspecialchars($_POST['message'] ?? ''); 
                ?></textarea>
            </div>
            
            <button type="submit" class="btn-primary">Send Message</button>
        </form>
        
        <!--other fake methods to contact-->
        <div class="contact-info">
            <h3>Other Ways to Reach Us</h3>
            <p><strong>Email:</strong> <a href="mailto:support@shamazon.com">support@shamazon.com</a></p>
            <p><strong>Phone:</strong> (555) 123-4567</p>
            <p><strong>Address:</strong><br>
                123 Bookworm Lane<br>
                Windsor, ON, N9A 1A1<br>
                Canada</p>
            <p><strong>Hours:</strong><br>
                Monday - Friday: 9:00 AM - 6:00 PM EST</p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>