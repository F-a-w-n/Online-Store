<?php
// Original code by Fawn Barisic
// manage users
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../includes/db.php';

// page specific values
$page_title = "Manage Users - Shamazon";
$help_page = 'admin_docs';
require_once __DIR__ . '/../includes/header.php';

// toggle user status
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $stmt = $pdo->prepare("SELECT status FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    if ($user) {
        $new_status = $user['status'] == 1 ? 0 : 1;
        $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $id]);
        $_SESSION['admin_msg'] = "User status updated.";
    }
    header("Location: /shamazon/admin/users.php");
    exit();
}

// list users
$users = $pdo->query("SELECT * FROM users ORDER BY id")->fetchAll();

// error msg
if (isset($_SESSION['admin_msg'])) {
    echo '<div class="success">' . $_SESSION['admin_msg'] . '</div>';
    unset($_SESSION['admin_msg']);
}
?>

<section class="admin-users">
    <h1>Manage Users</h1>
    <!--table of all user details and enabled/disabled state-->
    <table>
        <thead>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td><?php echo htmlspecialchars($u['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td><?php echo ucfirst($u['role']); ?></td>
                    <td><?php echo $u['status'] == 1 ? 'Active' : 'Disabled'; ?></td>
                    <td>
                        <a href="/shamazon/admin/users.php?toggle=<?php echo $u['id']; ?>" onclick="return confirm('Toggle this user?')">
                            <?php echo $u['status'] == 1 ? 'Disable' : 'Enable'; ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>