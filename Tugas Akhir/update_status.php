<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = trim($_POST['status']); // Pastikan input bersih

    // Validasi status yang diizinkan
    $allowed_statuses = ['Pending', 'Completed', 'Cancelled'];
    if (!in_array($status, $allowed_statuses)) {
        die("Status tidak valid.");
    }

    // Gunakan prepared statement
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("si", $status, $order_id);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    // Redirect ke halaman admin_dashboard setelah berhasil
    header("Location: manage_order.php");
    exit();
}
?>
