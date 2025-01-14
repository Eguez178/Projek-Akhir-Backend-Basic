<?php
include 'config.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Sepatu</title>
    <style>
        .body {
            font-family: 'Arial', sans-serif;
        }

        .product img {
            object-fit: cover;
            border-radius: 8px;
        }

        .product form input[type="number"] {
            width: 60px;
            text-align: center;
            margin-right: 5px;
        }

        .button {
            transition: all 0.3s ease-in-out;
        }

        .button:hover {
             background-color: #218838; /* Warna lebih gelap saat hover */
        }

        .header-custom {
            background-image: url('Image/Nike — Pattern - Forma & Co.jpg'); /* Ganti dengan path gambar Anda */
            background-size: cover; /* Mengatur gambar agar menutupi seluruh area */
            background-position: center; /* Mengatur posisi gambar di tengah */
            background-repeat: no-repeat; /* Menghindari pengulangan gambar */
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <header class="header-custom text-white p-4 shadow flex justify-between items-center">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Toko Sepatu</h1>
        </div>
        <div class="flex space-x-10">
            <a href="cart.php" class="mx-2 hover:underline">Keranjang Belanja</a>
            <a href="process.php" class="mx-2 hover:underline">Proses</a>
            <a href="login.php" class="mx-2 hover:underline">Dashboard</a>
        </div>
    </header>

    <main class="container mx-auto mt-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="product bg-white shadow-md rounded-lg overflow-hidden">
                <img src="<?php echo $row['photo']; ?>" alt="<?php echo $row['name']; ?>" class="w-full h-48">
                    <div class="p-4">
                        <h3 class="text-lg font-bold"><?php echo $row['name']; ?></h3>
                        <p class="text-gray-700">Harga: Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                        <p class="text-gray-500">Stok: <?php echo $row['stock']; ?></p>
                        <form method="POST" action="add_to_cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <input type="number" name="quantity" value="1" min="1" max="<?php echo $row['stock']; ?>" class="border p-2 rounded w-full mb-2" required>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                Tambah ke Keranjang
                            </button>
                        </form>

                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
</body>
</html>
