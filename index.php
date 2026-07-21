<?php
// Original code by Fawn Barisic
// Shamazon Homepage

require_once __DIR__ . '/includes/db.php';

// page specific values
$page_title = "Shamazon - Your Online Bookstore";
$page_desc = "Discover 20+ books with dynamic themes, ratings, and seamless checkout.";
$help_page = 'index';
require_once __DIR__ . '/includes/header.php';

// fetch all products from database
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id LIMIT 20");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    $products = [];
    echo "<p>Error loading products.</p>";
}
?>

<section class="product-grid">
    <h1>Welcome to Shamazon!</h1>
    <p>Browse our collection of 20+ curated titles.</p>
    
    <!--lists all books as cards-->
    <div class="grid-container">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $book): ?>
                <div class="product-card">
                    <a href="/shamazon/product.php?id=<?php echo $book['id']; ?>">
                        <img src="/shamazon/assets/images/products/<?php echo $book['image_url']; ?>" 
                             alt="<?php echo htmlspecialchars($book['title']); ?>" 
                             onerror="this.src='/shamazon/assets/images/ui/placeholder.jpg'">
                        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="author">by <?php echo htmlspecialchars($book['author']); ?></p>
                        <p class="price">$<?php echo number_format($book['base_price'], 2); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!--no books found-->
            <p>No books found in the database yet. Please add some!</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>