<?php
session_start();
include 'config.php';

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Tambahkan Stok Produk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_stock'])) {
    $product_id = $_POST['product_id'];
    $additional_stock = $_POST['additional_stock'];

    if (!empty($product_id) && !empty($additional_stock)) {
        $stmt = $conn->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
        $stmt->bind_param("ii", $additional_stock, $product_id);
        if ($stmt->execute()) {
            $success_message = "Stok berhasil ditambahkan.";
        } else {
            $error_message = "Gagal menambahkan stok.";
        }
    } else {
        $error_message = "Semua field harus diisi.";
    }
}

// Ambil data produk dari database
$products = $conn->query("SELECT id, name, price, stock FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function openAddStockModal() {
            document.getElementById('addStockModal').classList.remove('hidden');
        }

        function closeAddStockModal() {
            document.getElementById('addStockModal').classList.add('hidden');
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto p-6">
        <section class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Manajemen Produk</h2>
            <a href="admin_dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block">Kembali ke Dashboard</a>

            <?php if (isset($success_message)) : ?>
                <p class="text-green-600 font-medium"><?php echo $success_message; ?></p>
            <?php elseif (isset($error_message)) : ?>
                <p class="text-red-600 font-medium"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <div class="flex items-center mt-6">
                <button onclick="openAddStockModal()" class="flex items-center bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Stok Produk
                </button>
            </div>

            <div class="flex items-center mt-6">
                <button onclick="location.href='add_product.php'" class="flex items-center bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambahkan Produk Baru
                </button>
            </div>

            <h3 class="text-lg font-semibold mt-6">Daftar Produk</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg border border-gray-200 mt-4">
                    <thead>
                        <tr class="bg-gray-100 text-gray-800">
                            <th class="py-3 px-6 border-b text-left">Nama</th>
                            <th class="py-3 px-6 border-b text-left">Harga</th>
                            <th class="py-3 px-6 border-b text-left">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($products && $products->num_rows > 0): ?>
                            <?php while ($row = $products->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50 transition duration-200">
                                    <td class="py-3 px-6 border-b"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td class="py-3 px-6 border-b"><?php echo "Rp " . number_format($row['price']); ?></td>
                                    <td class="py-3 px-6 border-b"><?php echo htmlspecialchars($row['stock']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="py-3 px-6 text-center text-gray-500">Tidak ada data produk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Modal Tambah Stok -->
        <div id="addStockModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-md w-96">
                <h3 class="text-lg font-semibold mb-4">Tambah Stok Produk</h3>
                <form method="POST">
                    <input type="hidden" name="add_stock">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                        <select name="product_id" class="mt-1 p-2 border w-full rounded" required>
                            <option value="" disabled selected>Pilih Produk</option>
                            <?php
                            $product_list = $conn->query("SELECT id, name FROM products");
                            while ($product = $product_list->fetch_assoc()) {
                                echo "<option value='{$product['id']}'>{$product['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tambahkan Stok</label>
                        <input type="number" name="additional_stock" class="mt-1 p-2 border w-full rounded" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeAddStockModal()" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded shadow mr-2">Batal</button>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded shadow">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
