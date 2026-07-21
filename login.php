<?php
// Original code by Fawn Barisic
// user Login

require_once __DIR__ . '/includes/db.php';

// page specific values
$page_title = "Login - Shamazon";
$page_desc = "Login to your Shamazon account";
$help_page = 'account_help';
$error = '';
require_once __DIR__ . '/includes/header.php';

// sends login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter your email and password.';
    } else {
        // find user by email
        $stmt = $pdo->prepare("SELECT id, full_name, email, password_hash, role, status FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // check if account is disabled
            if ($user['status'] == 0) {
                $error = 'Your account has been disabled. Please contact support.';
            } else {
                // store session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // check for redirect after login and redirect there
                if (isset($_SESSION['redirect_after_login'])) {
                    $redirect = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                    header("Location: $redirect");
                } else {
                    header("Location: /shamazon/index.php");
                }
                exit();
            }
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>

<section class="auth-form">
    <h2>Login to Shamazon</h2>
    
    <!--error output-->
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <!--form to put login info-->
    <form method="POST">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn-primary">Login</button>
    </form>
    <p>Don't have an account? <a href="/shamazon/register.php">Register here</a></p>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>