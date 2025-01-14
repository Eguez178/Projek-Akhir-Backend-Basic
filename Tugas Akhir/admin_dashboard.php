<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['admin']; // Nama pengguna dari session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-image: url('Image/Nike.jpg'); /* Ganti dengan URL gambar latar belakang Anda */
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: #343a40;
        }
        .header {
            background-color: rgba(0, 0, 0, 0.7);
            color: #ffffff;
            padding: 10px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header a {
            color: #ffffff;
            text-decoration: none;
        }
        .header a:hover {
            text-decoration: underline;
        }
        .dashboard-container {
            text-align: center;
            margin-top: 50px;
        }
        .dashboard-title {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        .card-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .dashboard-card {
            width: 150px;
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            flex-direction: column;
            color: white;
        }
        .dashboard-card:hover {
            filter: brightness(1.1);
            cursor: pointer;
        }
        .dashboard-card i {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .dashboard-card:nth-child(1) {
            background-color: rgba(71, 78, 147, 1);
        }
        .dashboard-card:nth-child(2) {
            background-color: rgba(114, 186, 169, 1);
        }
        .dashboard-card:nth-child(3) {
            background-color: rgba(164, 211, 79, 1);
        }
        .dashboard-card:nth-child(4) {
            background-color: rgba(126, 92, 173, 1);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header d-flex justify-content-between align-items-center">
        <h1>Admin Dashboard</h1>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="dashboard-container">
    <div class="dashboard-title">WELCOME <?php echo htmlspecialchars($username); ?></div>
        <div class="card-container">
        <div class="dashboard-card" onclick="location.href='manage_admin.php'">
                    <div>
                        <i class="fas fa-user-cog"></i>
                        <div>Admin</div>
                    </div>
            </div>
            <div class="dashboard-card" onclick="location.href='manage_product.php'">
                <div>
                    <i class="fas fa-box"></i>
                    <div>Produk</div>
                </div>
            </div>
            <div class="dashboard-card" onclick="location.href='manage_order.php'">
                <div>
                    <i class="fas fa-clipboard-list"></i>
                    <div>Pesanan</div>
                </div>
            </div>
            <div class="dashboard-card" onclick="location.href='sales_cart.php'">
                <div>
                    <i class="fas fa-chart-line"></i>
                    <div>Laporan</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
