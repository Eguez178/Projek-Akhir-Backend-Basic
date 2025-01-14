<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
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
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Keranjang Belanja</h1>
            <a href="index.php" class="text-sm underline">Kembali ke Produk</a>
        </div>
    </header>

    <main class="container mx-auto mt-8">
        <!-- Form untuk Checkout -->
        <form method="POST" action="checkout.php">
            <label for="customer_name" class="block text-lg font-semibold mb-2">Nama Pelanggan:</label>
            <input type="text" id="customer_name" name="customer_name" required class="border p-2 rounded w-full mb-4">
            
            <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-4 text-left">Nama Produk</th>
                        <th class="p-4 text-left">Harga Satuan</th>
                        <th class="p-4 text-left">Jumlah</th>
                        <th class="p-4 text-left">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                        foreach ($_SESSION['cart'] as $id => $item) {
                            $total += $item['total_price'];
                            echo "<tr>
                                    <td class='p-4'>{$item['name']}</td>
                                    <td class='p-4'>Rp " . number_format($item['price'], 0, ',', '.') . "</td>
                                    <td class='p-4'>{$item['quantity']}</td>
                                    <td class='p-4'>Rp " . number_format($item['total_price'], 0, ',', '.') . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='p-4 text-center'>Keranjang Kosong</td></tr>";
                    }
                    ?>
                </tbody>
                <tfoot class="bg-gray-100">
                    <tr>
                        <td colspan="3" class="p-4 font-bold">Total Bayar</td>
                        <td class="p-4 font-bold">Rp <?php echo number_format($total, 0, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>

            <button type="submit" class="mt-4 bg-green-600 text-white py-2 px-4 rounded">Checkout</button>
        </form>
    </main>
</body>
</html>
