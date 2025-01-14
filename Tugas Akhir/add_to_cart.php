<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $customer_name = $_POST['customer_name']; // Ambil nama pelanggan

    // Ambil data produk dari database
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();

    // Periksa apakah produk sudah ada di keranjang
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Jika produk sudah ada, tambahkan kuantitas
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        $_SESSION['cart'][$product_id]['total_price'] = $_SESSION['cart'][$product_id]['quantity'] * $_SESSION['cart'][$product_id]['price'];
    } else {
        // Jika produk belum ada, tambahkan produk baru
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'total_price' => $product['price'] * $quantity,
            'customer_name' => $customer_name // Simpan nama pelanggan
        ];
    }

    // Redirect ke halaman keranjang belanja
    header('Location: cart.php');
    exit();
}
?>
