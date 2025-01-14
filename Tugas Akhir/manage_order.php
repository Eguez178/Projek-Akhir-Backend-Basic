<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Ambil data pesanan dari database
$sql = "SELECT * FROM orders ORDER BY id DESC";
$result = $conn->query($sql);

// Kelompokkan pesanan berdasarkan status
$orders = [
    'Pending' => [],
    'Completed' => [],
    'Cancelled' => [],
];

while ($row = $result->fetch_assoc()) {
    $orders[$row['status']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 10px;
        }
        .status-section {
            margin-bottom: 40px;
        }
        .status-section h2 {
            color: #444;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f0f0f0;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        select {
            padding: 5px;
            font-size: 14px;
        }
        button {
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .pending {
            border-left: 5px solid #ffc107;
        }
        .completed {
            border-left: 5px solid #28a745;
        }
        .cancelled {
            border-left: 5px solid #dc3545;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <div class="container">
    <a href="admin_dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block">Kembali ke Dashboard</a>
        <!-- Pending Orders Section -->
        <div class="status-section">
            <h2>Pesanan Pending</h2>
            <table class="pending">
                <thead>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders['Pending'])): ?>
                        <?php foreach ($orders['Pending'] as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                <td><?= number_format($order['total_price'], 2) ?></td>
                                <td><?= htmlspecialchars($order['status']) ?></td>
                                <td>
                                    <form action="update_status.php" method="POST">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <select name="status" required>
                                            <option value="Pending" <?= $order['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Completed" <?= $order['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                                            <option value="Cancelled" <?= $order['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                        <button type="submit">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Tidak ada pesanan pending.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Completed Orders Section -->
        <div class="status-section">
            <h2>Pesanan Selesai</h2>
            <table class="completed">
                <thead>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders['Completed'])): ?>
                        <?php foreach ($orders['Completed'] as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                <td><?= number_format($order['total_price'], 2) ?></td>
                                <td><?= htmlspecialchars($order['status']) ?></td>
                                <td>
                                    <form action="update_status.php" method="POST">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <select name="status" required>
                                            <option value="Pending" <?= $order['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Completed" <?= $order['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                                            <option value="Cancelled" <?= $order['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                        <button type="submit">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Tidak ada pesanan selesai.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Cancelled Orders Section -->
        <div class="status-section">
            <h2>Pesanan Dibatalkan</h2>
            <table class="cancelled">
                <thead>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders['Cancelled'])): ?>
                        <?php foreach ($orders['Cancelled'] as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                <td><?= number_format($order['total_price'], 2) ?></td>
                                <td><?= htmlspecialchars($order['status']) ?></td>
                                <td>
                                    <form action="update_status.php" method="POST">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <select name="status" required>
                                            <option value="Pending" <?= $order['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Completed" <?= $order['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                                            <option value="Cancelled" <?= $order['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                        <button type="submit">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Tidak ada pesanan dibatalkan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
