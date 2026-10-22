<?php
// includes/stock.php: stock and availability of the products in the shop.
// The product details (name, price, images) live in data_products.php; only the stock count
// and the "available" switch live in the database (product_stock), so an admin can change them.

const DEFAULT_STOCK = 10;   // used for a product that has no row yet

// product_id => ['stock' => int, 'available' => bool]
function fetch_stock_map(mysqli $db): array
{
    $map = [];
    $res = $db->query('SELECT product_id, stock, available FROM product_stock');
    while ($row = $res->fetch_assoc()) {
        $map[$row['product_id']] = ['stock' => (int)$row['stock'], 'available' => (bool)$row['available']];
    }
    return $map;
}

function stock_for(array $map, string $product_id): array
{
    return $map[$product_id] ?? ['stock' => DEFAULT_STOCK, 'available' => true];
}

// Can this product be bought right now?
function is_buyable(array $s): bool
{
    return $s['available'] && $s['stock'] > 0;
}

// Small label for product cards: "Sold out", "Unavailable", "Only 2 left" or ''.
function stock_label(array $s): string
{
    if (!$s['available']) {
        return 'Unavailable';
    }
    if ($s['stock'] <= 0) {
        return 'Sold out';
    }
    if ($s['stock'] <= 3) {
        return 'Only ' . $s['stock'] . ' left';
    }
    return '';
}
