<?php
// Original code by Fawn Barisic
// quick script to verify everything works
require_once __DIR__ . '/includes/db.php';

// fetch one product to test
$stmt = $pdo->query("SELECT * FROM products LIMIT 1");
$product = $stmt->fetch();

if ($product) {
    echo "Database is working! First product: " . $product['title'];
} else {
    echo "No products found, but the connection works.";
}
?>