<?php
session_start();
include 'config.php'; // Koneksi ke database

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $photo = $_FILES['photo'];

    // Validasi sederhana
    if (!empty($product_name) && !empty($price) && !empty($stock) && !empty($photo['name'])) {
        // Upload foto
        $target_dir = "Image/";
        
        // Cek apakah direktori ada
        if (!is_dir($target_dir)) {
            die("Direktori uploads tidak ditemukan.");
        }

        $target_file = $target_dir . basename($photo['name']);
        if (move_uploaded_file($photo['tmp_name'], $target_file)) {
            $photo_url = $target_file;

            // Simpan data ke database
            $sql = "INSERT INTO products (name, price, stock, photo) VALUES ('$product_name', '$price', '$stock', '$photo_url')";
            if ($conn->query($sql) === TRUE) {
                $success_message = "Produk berhasil ditambahkan!";
            } else {
                $error_message = "Gagal menambahkan produk: " . $conn->error;
            }
        } else {
            $error_message = "Gagal mengupload foto.";
        }
    } else {
        $error_message = "Semua field harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-lg mx-auto bg-white p-6 shadow-md rounded-lg">
        <h1 class="text-3xl font-bold mb-6 text-blue-600">Tambah Produk Baru</h1>
        <a href="manage_product.php" class="text-blue-500 hover:underline mb-4 inline-block">Kembali</a>

        <?php if (isset($success_message)) : ?>
            <p class="text-green-500 mt-4"><?php echo $success_message; ?></p>
        <?php elseif (isset($error_message)) : ?>
            <p class="text-red-500 mt-4"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" name="product_name" class="mt-1 p-2 border w-full rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Harga</label>
                <input type="number" name="price" class="mt-1 p-2 border w-full rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Stok</label>
                <input type="number" name="stock" class="mt-1 p-2 border w-full rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Foto Produk</label>
                <input type="file" name="photo" class="mt-1 p-2 border w-full rounded" required>
            </div>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded shadow">Tambah Produk</button>
        </form>
    </div>
</body>
</html>
