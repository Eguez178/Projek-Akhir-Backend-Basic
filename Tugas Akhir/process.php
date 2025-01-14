<?php
session_start();
include 'config.php';

// Ambil data pesanan dari tabel 'orders' dan 'order_items'
$sql = "SELECT o.customer_name, oi.product_id, oi.quantity, oi.total_price, o.status, p.name AS product_name 
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id";
$result = $conn->query($sql);

// Simpan data ke array
$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Pesanan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        .header-custom {
            background-image: url('Image/Nike — Pattern - Forma & Co.jpg'); /* Ganti dengan path gambar Anda */
            background-size: cover; /* Mengatur gambar agar menutupi seluruh area */
            background-position: center; /* Mengatur posisi gambar di tengah */
            background-repeat: no-repeat; /* Menghindari pengulangan gambar */
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="header-custom text-white p-4 shadow">
        <div class="container mx-auto">
            <h1 class="text-xl font-bold">Proses Pesanan</h1>
        </div>
    </header>

    <main class="container mx-auto mt-8 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4">Daftar Pesanan</h2>
        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 p-2">Nama Pelanggan</th>
                    <th class="border border-gray-300 p-2">Nama Produk</th>
                    <th class="border border-gray-300 p-2">Jumlah</th>
                    <th class="border border-gray-300 p-2">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($orders) > 0): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="border border-gray-300 p-2"><?php echo $order['customer_name']; ?></td>
                            <td class="border border-gray-300 p-2"><?php echo $order['product_name']; ?></td>
                            <td class="border border-gray-300 p-2"><?php echo $order['quantity']; ?></td>
                            <td class="border border-gray-300 p-2">Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="border border-gray-300 p-2 text-center">Tidak ada pesanan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
