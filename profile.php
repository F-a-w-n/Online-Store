<?php
// Original code by Fawn Barisic
// user profile management

require_once __DIR__ . '/includes/db.php';

// page specific values
$page_title = "My Profile - Shamazon";
$page_desc = "Manage your account settings and shipping address.";
$help_page = 'account_help';
require_once __DIR__ . '/includes/header.php';


// redirect if not logged in
if (!is_logged_in()) {
    $_SESSION['redirect_after_login'] = '/shamazon/profile.php';
    header("Location: /shamazon/login.php");
    exit();
}

// grab user session
$user_id = $_SESSION['user_id'];
$success = '';
$error = '';
    
// fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// user does not exist error
if (!$user) {
    header("Location: /shamazon/logout.php");
    exit();
}

// handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $shipping_address = trim($_POST['shipping_address'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    // validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (empty($full_name)) {
        $error = 'Full name is required.';
    } else {
        // check if email is already used
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            $error = 'This email is already registered to another account.';
        } else {
            // update user
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, shipping_address = ? WHERE id = ?");
            if ($stmt->execute([$full_name, $email, $shipping_address, $user_id])) {
                // Update session
                $_SESSION['full_name'] = $full_name;
                $_SESSION['email'] = $email;
                $success = 'Profile updated successfully!';
                
                // refresh user data
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch();
            } else {
                $error = 'Failed to update profile. Please try again.';
            }
        }
    }
}

// handle password change
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = 'All password fields are required.';
    } elseif (strlen($new_password) < 6) {
        $error = 'New password must be at least 6 characters.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // verify current password
        if (password_verify($current_password, $user['password_hash'])) {
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            if ($stmt->execute([$new_hash, $user_id])) {
                $success = 'Password changed successfully!';
            } else {
                $error = 'Failed to change password.';
            }
        } else {
            $error = 'Current password is incorrect.';
        }
    }
}
?>

<section class="profile-page">
    <h1>My Profile</h1>
    
    <!--success/error messages-->
    <?php if ($success): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="profile-grid">
        <!-- profile info -->
        <div class="profile-section">
            <h2>Account Information</h2>
            <!--form to update account info-->
            <form method="POST" class="profile-form">
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" 
                           value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="shipping_address">Shipping Address</label>
                    <textarea id="shipping_address" name="shipping_address" rows="3"><?php 
                        echo htmlspecialchars($user['shipping_address'] ?? ''); 
                    ?></textarea>
                    <small>This address will be pre-filled during checkout.</small>
                </div>
                
                <button type="submit" class="btn-primary">Update Profile</button>
            </form>
        </div>
        
        <!-- change password -->
        <div class="profile-section">
            <h2>Change Password</h2>
            <form method="POST" class="profile-form">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password (min 6 characters)</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" name="change_password" class="btn-secondary">Change Password</button>
            </form>
        </div>
    </div>
    
    <div class="profile-links">
        <a href="/shamazon/order-history.php">View My Orders</a>
        <a href="/shamazon/cart.php">View My Cart</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>