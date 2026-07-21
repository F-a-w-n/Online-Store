<?php
// Original code by Fawn Barisic
// full product catalog with search and filter

require_once __DIR__ . '/includes/db.php';

// page specific values
$page_title = "Shop - Shamazon";
$page_desc = "Browse our complete collection of 20+ books with search and filter options.";
$help_page = 'how_to_shop';
require_once __DIR__ . '/includes/header.php';


// Build Search Query
$where_conditions = [];
$params = [];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if (!empty($search)) {
    $where_conditions[] = "(title LIKE ? OR author LIKE ? OR description LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

// check category
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
if (!empty($category)) {
    $where_conditions[] = "category = ?";
    $params[] = $category;
}

// check clause
$where_clause = '';
if (count($where_conditions) > 0) {
    $where_clause = "WHERE " . implode(" AND ", $where_conditions);
}

// product count
try {
    $count_sql = "SELECT COUNT(*) FROM products $where_clause";
    $stmt = $pdo->prepare($count_sql);
    $stmt->execute($params);
    $total_products = (int)$stmt->fetchColumn();
} catch (PDOException $e) {
    $total_products = 0;
    echo '<div class="error">Error counting products: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

// pagination
$per_page = 12;
$total_pages = ($total_products > 0) ? ceil($total_products / $per_page) : 1;
$current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$current_page = min($current_page, $total_pages);
$offset = ($current_page - 1) * $per_page;

// fetch products
$products = [];
try {
    $per_page = (int)$per_page;
    $offset = (int)$offset;
    
    $sql = "SELECT * FROM products $where_clause ORDER BY id LIMIT ? OFFSET ?";
    $stmt = $pdo->prepare($sql);
    
    $param_index = 1;
    foreach ($params as $value) {
        $stmt->bindValue($param_index++, $value, PDO::PARAM_STR);
    }
    $stmt->bindValue($param_index++, $per_page, PDO::PARAM_INT);
    $stmt->bindValue($param_index++, $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    echo '<div class="error">Error fetching products: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

// fetch categories
$categories = [];
try {
    $stmt = $pdo->query("SHOW COLUMNS FROM products LIKE 'category'");
    if ($stmt->rowCount() > 0) {
        $categories = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != '' ORDER BY category")->fetchAll();
    }
} catch (PDOException $e) {
    $categories = [];
}
?>

<section class="shop-page">
    <h1>Our Collection</h1>
    <p>Browse our curated selection of <?php echo $total_products; ?> books.</p>
    
    <!-- search form -->
    <div class="shop-filters">
        <form method="GET" action="/shamazon/shop.php" class="search-form">
            <div class="search-row">
                <!--text search-->
                <input type="text" name="search" placeholder="Search by title, author, or description..." 
                       value="<?php echo htmlspecialchars($search); ?>" class="search-input">
                <!--category filter (has to check what categories are there)-->        
                <select name="category" class="category-select">
                    <option value="">All Categories</option>
                    <?php if (count($categories) > 0): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat['category']); ?>" 
                                <?php echo $category === $cat['category'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['category']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No categories available</option>
                    <?php endif; ?>
                </select>
                <!--search and clear buttons-->
                <button type="submit" class="btn-primary">Search</button>
                <?php if (!empty($search) || !empty($category)): ?>
                    <a href="/shamazon/shop.php" class="btn-secondary">Clear Filters</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    
    <!-- results count -->
    <p class="results-count">
        Showing <?php echo count($products); ?> of <?php echo $total_products; ?> products
        <?php if (!empty($search)): ?> matching "<strong><?php echo htmlspecialchars($search); ?></strong>"<?php endif; ?>
        <?php if (!empty($category)): ?> in <strong><?php echo htmlspecialchars($category); ?></strong><?php endif; ?>
    </p>
    
    <!-- product grid -->
    <?php if (count($products) > 0): ?>
        <div class="grid-container">
            <?php foreach ($products as $book): ?>
                <div class="product-card">
                    <a href="/shamazon/product.php?id=<?php echo $book['id']; ?>">
                        <img src="/shamazon/assets/images/products/<?php echo $book['image_url']; ?>" 
                             alt="<?php echo htmlspecialchars($book['title']); ?>" 
                             onerror="this.src='/shamazon/assets/images/ui/placeholder.jpg'">
                        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="author">by <?php echo htmlspecialchars($book['author']); ?></p>
                        <?php if (!empty($book['category'])): ?>
                            <p class="category-tag"><?php echo htmlspecialchars($book['category']); ?></p>
                        <?php endif; ?>
                        <p class="price">$<?php echo number_format($book['base_price'], 2); ?></p>
                        <?php if ($book['stock_quantity'] > 0): ?>
                            <span class="in-stock">In Stock</span>
                        <?php else: ?>
                            <span class="out-of-stock">Out of Stock</span>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?php echo $current_page - 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">← Previous</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>" 
                       class="<?php echo $i === $current_page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?php echo $current_page + 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">Next →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <!--no results case-->
        <div class="no-results">
            <p>No products found matching your criteria.</p>
            <?php if (empty($search) && empty($category)): ?>
                <p><strong>No products in the database yet.</strong> Add some using the <a href="/shamazon/admin/product_add.php">Admin Panel</a>.</p>
            <?php else: ?>
                <p><a href="/shamazon/shop.php" class="btn-primary">View All Products</a></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>