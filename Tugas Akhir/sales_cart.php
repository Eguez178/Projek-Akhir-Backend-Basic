<?php
session_start();
include 'config.php';

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Ambil data penjualan dari database
$sql = "SELECT 
            DATE_FORMAT(o.order_date, '%Y-%m') AS month, 
            SUM(o.total_price) AS total_sales, 
            SUM(oi.quantity) AS total_quantity, 
            COUNT(*) AS total_transactions 
        FROM 
            orders o
        JOIN 
            order_items oi ON o.id = oi.order_id
        WHERE 
            o.status = 'Completed' 
        GROUP BY 
            month 
        ORDER BY 
            month ASC";

$result = $conn->query($sql);

$sales_data = [];
$total_sales_all = 0;
$total_quantity_all = 0;
$total_transactions_all = 0;

while ($row = $result->fetch_assoc()) {
    $sales_data[] = [
        'month' => $row['month'],
        'sales' => $row['total_sales'],
        'quantity' => $row['total_quantity'],
        'transactions' => $row['total_transactions']
    ];
    
    // Hitung total keseluruhan
    $total_sales_all += $row['total_sales'];
    $total_quantity_all += $row['total_quantity'];
    $total_transactions_all += $row['total_transactions'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik Penjualan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto p-6">
        <section class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Grafik Penjualan</h2>
            <a href="admin_dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block">Kembali ke Dashboard</a>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-100 p-4 rounded-lg shadow">
                    <p class="text-lg font-semibold text-center">Total Penjualan</p>
                    <p class="text-2xl font-bold text-center">Rp <?php echo number_format($total_sales_all, 2, ',', '.'); ?></p>
                </div>
                <div class="bg-green-100 p-4 rounded-lg shadow">
                    <p class="text-lg font-semibold text-center">Total Barang Terjual</p>
                    <p class="text-2xl font-bold text-center"><?php echo $total_quantity_all; ?></p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-lg shadow">
                    <p class="text-lg font-semibold text-center">Total Transaksi</p>
                    <p class="text-2xl font-bold text-center"><?php echo $total_transactions_all; ?></p>
                </div>
            </div>
            
            <canvas id="salesChart" class="w-full h-96"></canvas>
            <button id="downloadChart" class="mt-4 bg-blue-500 text-white py-2 px-4 rounded shadow hover:bg-blue-600">
                Unduh Grafik
            </button>
        </section>
    </div>

    <script>
        const salesData = <?php echo json_encode($sales_data); ?>;

        // Format data untuk grafik
        const labels = salesData.map(data => data.month);
        const data = salesData.map(data => data.sales);

        // Konfigurasi Chart.js
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Penjualan',
                    data: data,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total Penjualan (Rp)'
                        }
                    }
                }
            }
        });

        // Fungsi untuk mengunduh grafik dan informasi sebagai gambar
        document.getElementById('downloadChart').addEventListener('click', function () {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const chartCanvas = document.getElementById('salesChart');

            // Set ukuran canvas
            canvas.width = chartCanvas.width;
            canvas.height = chartCanvas.height + 200; // Tambahkan ruang untuk teks

            // Gambar grafik ke canvas
            ctx.drawImage(chartCanvas, 0, 0);

            // Tambahkan latar belakang
            ctx.fillStyle = '#f9f9f9'; // Warna latar belakang
            ctx.fillRect(0, chartCanvas.height, canvas.width, 200); // Gambar latar belakang untuk teks

            // Tambahkan garis pemisah
            ctx.strokeStyle = '#ccc';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(0, chartCanvas.height);
            ctx.lineTo(canvas.width, chartCanvas.height);
            ctx.stroke();

            // Tambahkan teks informasi
            ctx.fillStyle = 'black';
            ctx.font = '20px Arial';
            ctx.fillText('Total Penjualan: Rp <?php echo number_format($total_sales_all, 2, ',', '.'); ?>', 10, chartCanvas.height + 30);
            ctx.fillText('Total Barang Terjual: <?php echo $total_quantity_all; ?>', 10, chartCanvas.height + 60);
            ctx.fillText('Total Transaksi: <?php echo $total_transactions_all; ?>', 10, chartCanvas.height + 90);

            // Tambahkan judul
            ctx.font = 'bold 24px Arial';
            ctx.fillText('Grafik Penjualan', 10, chartCanvas.height + 120);

            // Mengunduh gambar
            const link = document.createElement('a');
            link.download = 'grafik_penjualan.png';
            link.href = canvas.toDataURL();
            link.click();
        });
    </script>
</body>
</html>