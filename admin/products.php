<?php
// Original code by Fawn Barisic
// manage products

require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../includes/db.php';

// page specific values
$page_title = "Manage Products - Shamazon";
$help_page = 'admin_docs';
require_once __DIR__ . '/../includes/header.php';

// handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['admin_msg'] = "Product deleted successfully.";
    header("Location: /shamazon/admin/products.php");
    exit();
}


// fetch all products
$products = $pdo->query("SELECT * FROM products ORDER BY id")->fetchAll();

if (isset($_SESSION['admin_msg'])) {
    echo '<div class="success">' . $_SESSION['admin_msg'] . '</div>';
    unset($_SESSION['admin_msg']);
}
?>

<section class="admin-products">
    <h1>Manage Products</h1>
    <a href="/shamazon/admin/product_add.php" class="btn-primary">Add New Product</a>
    
    <!--table of all existing products-->
    <table>
        <thead>
            <tr><th>ID</th><th>Title</th><th>Author</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo htmlspecialchars($p['title']); ?></td>
                    <td><?php echo htmlspecialchars($p['author']); ?></td>
                    <td>$<?php echo number_format($p['base_price'], 2); ?></td>
                    <td><?php echo $p['stock_quantity']; ?></td>
                    <td>
                        <a href="/shamazon/admin/product_edit.php?id=<?php echo $p['id']; ?>">Edit</a>
                        <a href="/shamazon/admin/products.php?delete=<?php echo $p['id']; ?>" onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>