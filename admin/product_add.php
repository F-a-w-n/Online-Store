<?php
// Original code by Fawn Barisic
// add product

require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../includes/db.php';

// page specific values
$help_page = 'admin_docs';
$page_title = "Add Product - Shamazon";
$error = '';
$success = '';
require_once __DIR__ . '/../includes/header.php';

// attempt to add product and details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $base_price = (float)($_POST['base_price'] ?? 0);
    $stock_quantity = (int)($_POST['stock_quantity'] ?? 0);
    $image_url = trim($_POST['image_url'] ?? '');
    
    // validate
    if (empty($title) || empty($author) || empty($description) || empty($category) || $base_price <= 0) {
        $error = 'Please fill in all required fields.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (title, author, description, category, base_price, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $author, $description, $category, $base_price, $stock_quantity, $image_url]);
        $product_id = $pdo->lastInsertId();
        
        // handle options
        for ($i = 1; $i <= 3; $i++) {
            $opt_name = trim($_POST["option_name_$i"] ?? '');
            $opt_value = trim($_POST["option_value_$i"] ?? '');
            $opt_adj = (float)($_POST["price_adjustment_$i"] ?? 0);
            $opt_stock = (int)($_POST["option_stock_$i"] ?? 0);
            if (!empty($opt_name) && !empty($opt_value)) {
                $stmt = $pdo->prepare("INSERT INTO product_options (product_id, option_name, option_value, price_adjustment, stock_quantity) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$product_id, $opt_name, $opt_value, $opt_adj, $opt_stock]);
            }
        }
        
        $_SESSION['admin_msg'] = "Product added successfully!";
        header("Location: /shamazon/admin/products.php");
        exit();
    }
}

?>

<section class="admin-form">
    <h1>Add New Product</h1>
    <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
    
    <!--form to fill out new product details-->
    <form method="POST">
        <div class="form-group"><label>Title *</label><input type="text" name="title" required></div>
        <div class="form-group"><label>Author *</label><input type="text" name="author" required></div>
        <div class="form-group"><label>Description *</label><textarea name="description" rows="4" required></textarea></div>
        <div class="form-group"><label>Category *</label><input type="text" name="category" required></div>
        <div class="form-group"><label>Base Price ($) *</label><input type="number" step="0.01" name="base_price" required></div>
        <div class="form-group"><label>Stock Quantity</label><input type="number" name="stock_quantity" value="0"></div>
        <div class="form-group"><label>Image URL (filename)</label><input type="text" name="image_url" placeholder="book.jpg"></div>
        
        <h3>Product Options (min 2)</h3>
        <?php for ($i = 1; $i <= 3; $i++): ?>
            <div class="option-row">
                <input type="text" name="option_name_<?php echo $i; ?>" placeholder="e.g. Format">
                <input type="text" name="option_value_<?php echo $i; ?>" placeholder="e.g. Hardcover">
                <input type="number" step="0.01" name="price_adjustment_<?php echo $i; ?>" placeholder="Adjustment">
                <input type="number" name="option_stock_<?php echo $i; ?>" placeholder="Stock">
            </div>
        <?php endfor; ?>
        
        <button type="submit" class="btn-primary">Add Product</button>
    </form>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>