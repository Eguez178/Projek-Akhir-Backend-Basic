<?php
include 'config.php';
session_start();

// Pastikan ada item di keranjang
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    die('Keranjang Kosong');
}

// Ambil data nama pelanggan dari POST
$customer_name = $_POST['customer_name'] ?? 'Unknown';
$total_price = 0;

// Hitung total harga
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['total_price'];
}

// Simpan pesanan ke tabel 'orders'
$sql = "INSERT INTO orders (customer_name, total_price, payment, change_amount, status)
        VALUES ('$customer_name', $total_price, 0, 0, 'Pending')";
if (!$conn->query($sql)) {
    die("Error saat menyimpan pesanan: " . $conn->error);
}
$order_id = $conn->insert_id; // ID pesanan baru

// Simpan detail ke tabel 'order_items'
foreach ($_SESSION['cart'] as $id => $item) {
    $product_id = $id;
    $quantity = $item['quantity'];
    $total_price = $item['total_price'];

    // Masukkan data item pesanan
    $sql = "INSERT INTO order_items (order_id, product_id, quantity, total_price)
            VALUES ($order_id, $product_id, $quantity, $total_price)";
    if (!$conn->query($sql)) {
        die("Error saat menyimpan detail pesanan: " . $conn->error);
    }

    // Kurangi stok produk
    $sql = "UPDATE products SET stock = stock - $quantity WHERE id = $product_id";
    if (!$conn->query($sql)) {
        die("Error saat mengurangi stok: " . $conn->error);
    }
}

// Kosongkan keranjang
unset($_SESSION['cart']);

// Redirect ke halaman index
header('Location: index.php');
exit;
?>
