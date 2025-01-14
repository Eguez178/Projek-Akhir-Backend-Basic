<?php
session_start();
include 'config.php';

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Tambahkan Admin Baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (!empty($username) && !empty($password)) {
        $sql = "INSERT INTO admins (username, password, last_login) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            $success_message = "Admin berhasil ditambahkan.";
        } else {
            $error_message = "Gagal menambahkan admin.";
        }
    } else {
        $error_message = "Semua field harus diisi.";
    }
}

// Tampilkan Admin dan Login Terakhir
$sql = "SELECT username, DATE_FORMAT(last_login, '%d-%m-%Y %H:%i:%s') AS last_login_wib FROM admins";
$result = $conn->query($sql);

// Tampilkan Admin yang Login
$admin_logs_query = "SELECT username, DATE_FORMAT(last_login, '%d-%m-%Y %H:%i:%s') AS last_login 
                     FROM admins WHERE last_login IS NOT NULL ORDER BY last_login DESC";
$admin_logs = $conn->query($admin_logs_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function openAddAdminModal() {
            document.getElementById('addAdminModal').classList.remove('hidden');
        }

        function closeAddAdminModal() {
            document.getElementById('addAdminModal').classList.add('hidden');
        }

        function openEditAdminModal() {
            document.getElementById('editAdminModal').classList.remove('hidden');
        }

        function closeEditAdminModal() {
            document.getElementById('editAdminModal').classList.add('hidden');
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto p-6">
        <section class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Manajemen Admin</h2>
            <a href="admin_dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block">Kembali ke Dashboard</a>

            <?php if (isset($success_message)) : ?>
                <p class="text-green-600 font-medium"><?php echo $success_message; ?></p>
            <?php elseif (isset($error_message)) : ?>
                <p class="text-red-600 font-medium"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <div class="flex items-center mt-6">
                <button onclick="openAddAdminModal()" class="flex items-center bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Admin Baru
                </button>

                <button onclick="openEditAdminModal()" class="flex items-center bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded shadow ml-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9m-9-4h6m-6-4h4m-4-4h2m-2-4h6" />
                    </svg>
                    Ubah Admin
                </button>
            </div>

            <!-- Modal Tambah Admin -->
            <div id="addAdminModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white p-6 rounded-lg shadow-md w-96">
                    <h3 class="text-lg font-semibold mb-4">Tambah Admin Baru</h3>
                    <form method="POST">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Username</label>
                            <input type="text" name="username" class="mt-1 p-2 border w-full rounded" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" class="mt-1 p-2 border w-full rounded" required>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="closeAddAdminModal()" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded shadow mr-2">Batal</button>
                            <button type="submit" name="add_admin" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded shadow">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Ubah Admin -->
            <div id="editAdminModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white p-6 rounded-lg shadow-md w-96">
                    <h3 class="text-lg font-semibold mb-4">Ubah Admin</h3>
                    <form method="POST">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Username Baru</label>
                            <input type="text" name="new_username" class="mt-1 p-2 border w-full rounded" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                            <input type="password" name="new_password" class="mt-1 p-2 border w-full rounded" required>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="closeEditAdminModal()" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded shadow mr-2">Batal</button>
                            <button type="submit" name="edit_admin" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded shadow">Ubah</button>
                        </div>
                    </form>
                </div>
            </div>

            <h3 class="text-lg font-semibold mt-6">Daftar Admin</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg border border-gray-200 mt-4">
                    <thead>
                        <tr class="bg-gray-100 text-gray-800">
                            <th class="py-3 px-6 border-b text-left">Username</th>
                            <th class="py-3 px-6 border-b text-left">Login Terakhir (WIB)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr class="hover:bg-gray-50 transition duration-200">
                                <td class="py-3 px-6 border-b"><?php echo $row['username']; ?></td>
                                <td class="py-3 px-6 border-b"><?php echo $row['last_login_wib']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="mt-8 bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-2xl font-semibold mb-4 text-gray-800">Admin yang Login</h3>
            <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-800">
                        <th class="py-3 px-6 border-b text-left">Username</th>
                        <th class="py-3 px-6 border-b text-left">Login Terakhir (WIB)</th>
                    </tr>
                </thead>
            <tbody>
                <?php if ($admin_logs && $admin_logs->num_rows > 0): ?>
                    <?php while ($log = $admin_logs->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="py-3 px-6 border-b"><?php echo htmlspecialchars($log['username']); ?></td>
                            <td class="py-3 px-6 border-b"><?php echo htmlspecialchars($log['last_login']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td class="py-3 px-6 border-b text-center" colspan="2">Tidak ada admin yang login.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            </table>
            </div>
        </section>
</body>
</html>
