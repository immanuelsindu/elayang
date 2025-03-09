<?php
session_start(); // Mulai session

// Koneksi ke database
include('db_connection.php');

// Periksa jika form login disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil username dan password yang dimasukkan
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mengambil data berdasarkan username
    $sql = "SELECT * FROM users WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Jika user ditemukan
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Periksa password langsung (tanpa hash)
            if ($password == $row['password']) {
                // Set session login
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['nama'] = $row['nama']; // Menyimpan nama pengguna dalam session
                $_SESSION['role'] = $row['role']; // Menyimpan nama pengguna dalam session

                // Login sukses, redirect ke dashboard
                header("Location: dashboard.php");
                exit;
            } else {
                // Password salah
                $message = "Password salah!";
            }
        } else {
            // Username tidak ditemukan
            $message = "Username tidak ditemukan!";
        }

        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Sistem Informasi E-Layang</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-info d-flex align-items-center justify-content-center min-vh-100">
    <div class="position-absolute top-0 left-0 w-100 h-100">
        <img alt="Background" class="w-100 h-100 object-cover" height="1080" src="https://storage.googleapis.com/a1aa/image/Q5fSDoJJZwQWOSKNTgTEO7bAujv94Ct8svaz4oXLpzqbR4eTA.jpg" width="1920" />
    </div>
    <div class="position-relative z-index-10 text-center">
        <div class="d-flex flex-column align-items-center">
            <img alt="School logo" class="mb-4" src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi7rtoRw8nA-XuqtQ5Wfpyy3xMh5g-Vv4iYZozeZQ_eUHpmA4nLGHHEJ3xQbIAFNwxeVzXA0Zys5A4Tsw74dPRXD7cyQ5PayEuMZFsNj7Kgpd5tuHkUhKV_iP1JiMLgTAYAP9y3rfuUdC0/s1600/Logo+SMK.jpg" width="100" />
            <h1 class="display-4 font-weight-bold text-danger">SISTEM INFORMASI E-LAYANG</h1>
            <h2 class="h4 font-weight-semibold text-dark mt-2">SMK GAJAH MADA 01 MARGOYOSO</h2>
        </div>

        <div class="mt-5 bg-white p-5 rounded-lg shadow-lg w-100 max-w-md mx-auto">
            <h3 class="h4 font-weight-bold text-primary">BUAT AKUN</h3>
            <h5 class="mb-5">Admin, Staff, atau Kasek</h5>
            <!-- <p class="text-muted mb-4">Silahkan masuk untuk mulai menggunakan aplikasi</p> -->

            <form action="" method="POST">
                <div class="form-group mb-4">
                    <input class="form-control" placeholder="Username" type="text" name="username" required />
                </div>
                <div class="form-group mb-4">
                    <input class="form-control" placeholder="Password" type="password" name="password" required />
                </div>
                <button class="btn btn-primary w-100 py-2 font-weight-bold" type="submit">LOGIN</button>
            </form>
            <?php if (isset($message)) { echo '<p class="text-danger mt-3">'.$message.'</p>'; } ?>
          

            <div class="mt-5 text-center">
                <a class="text-primary" href="login.php">Masuk Sebagai Admin, Staff, atau Kasek.</a>
            </div>

            <div class="mt-3 text-center">
                <p class="text-muted">Tidak memiliki akun ? <a class="text-primary" href="#">Buat akun baru!</a></p>
            </div>

        </div>
    </div>
</body>
</html>
