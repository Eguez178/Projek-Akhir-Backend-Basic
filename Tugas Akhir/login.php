<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin['username'];

            // Update last_login
            $now = date("Y-m-d H:i:s");
            $updateStmt = $conn->prepare("UPDATE admins SET last_login = ? WHERE username = ?");
            $updateStmt->bind_param("ss", $now, $username);
            $updateStmt->execute();

            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('Image/Nike (1).jpg'); /* Ganti dengan path gambar Anda */
            background-size: cover; /* Mengatur gambar agar menutupi seluruh area */
            background-position: center; /* Mengatur posisi gambar di tengah */
            background-repeat: no-repeat; /* Menghindari pengulangan gambar */
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #ffffff;
        }

        .login-container {
            background: rgba(25, 30, 32, 0.8); /* Mengatur latar belakang dengan transparansi */
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #0DB8DE;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            outline: none;
            font-size: 14px;
            color: #333;
        }

        .input[type="text"]:focus, input[type="password"]:focus {
            border: 2px solid #0DB8DE;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: #0DB8DE;
            border: none;
            border-radius: 5px;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #1A87A5;
        }

        .error {
            color: #FF6B6B;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1 class="login-title">Admin Panel</h1>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Login</button>
            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
        </form>
    </div>
</body>
</html>
