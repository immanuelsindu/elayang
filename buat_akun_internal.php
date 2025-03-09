<?php
session_start();
include('db_connection.php');



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama     = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role     = trim($_POST['role']);

    // Validasi input
    if (empty($nama) || empty($username) || empty($password) || empty($confirm_password) || empty($role)) {
        echo "<script>alert('Semua kolom harus diisi!'); window.location.href='buat_akun_internal.php';</script>";
    } elseif ($password !== $confirm_password) {
        echo "<script>alert('Konfirmasi password tidak cocok!'); window.location.href='buat_akun_internal.php';</script>";
    } else {
        // Cek apakah username sudah digunakan
        $sql_check = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql_check);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('Username sudah terdaftar! Gunakan username lain.'); window.location.href='buat_akun_internal.php';</script>";
        } else {

            // Simpan data ke database
            $sql_insert = "INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("ssss", $nama, $username, $password, $role);

            if ($stmt->execute()) {
                echo "<script>alert('Akun berhasil dibuat! Silakan login.'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat membuat akun.'); window.location.href='buat_akun_internal.php';</script>";
            }
        }
        $stmt->close();
    }
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Sistem Informasi E-Layang</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>

<style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        background-image: url('https://storage.googleapis.com/a1aa/image/Q5fSDoJJZwQWOSKNTgTEO7bAujv94Ct8svaz4oXLpzqbR4eTA.jpg');
        background-size: cover;
        background-position: center;
    }
</style>


<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Masukkan Password</h5>
                </div>
                <div class="modal-body">
                    <input type="password" id="authPassword" class="form-control" placeholder="Masukkan password" />
                    <small id="errorText" class="text-danger d-none">Password salah!</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submitPassword">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div id="mainContent" class="d-none pb-5">
        <div class="position-relative z-index-10 text-center">
            <div class="d-flex flex-column align-items-center">
                <img alt="School logo" class="mb-4" src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi7rtoRw8nA-XuqtQ5Wfpyy3xMh5g-Vv4iYZozeZQ_eUHpmA4nLGHHEJ3xQbIAFNwxeVzXA0Zys5A4Tsw74dPRXD7cyQ5PayEuMZFsNj7Kgpd5tuHkUhKV_iP1JiMLgTAYAP9y3rfuUdC0/s1600/Logo+SMK.jpg" width="100" />
                <h1 class="display-4 font-weight-bold text-danger">SISTEM INFORMASI E-LAYANG</h1>
                <h2 class="h4 font-weight-semibold text-dark mt-2">SMK GAJAH MADA 01 MARGOYOSO</h2>
            </div>

            <div class="mt-5 bg-white p-5 rounded-lg shadow-lg w-100 max-w-md mx-auto">
                <h3 class="h4 font-weight-bold text-primary">BUAT AKUN</h3>
                <h5 class="mb-5">Sebagai Admin, Staff, atau Kepala Sekolah</h5>

                <form action="" method="POST">
                    <div class="form-group mb-4">
                        <input class="form-control" placeholder="Masukkan nama lengkap" type="text" name="nama" required />
                    </div>
                    <div class="form-group mb-4">
                        <input class="form-control" placeholder="Username" type="text" name="username" required />
                    </div>
                    <div class="form-group mb-4">
                        <input class="form-control" placeholder="Password" type="password" name="password" required />
                    </div>
                    <div class="form-group mb-4">
                        <input class="form-control" placeholder="Konfirmasi Password" type="password" name="confirm_password" required />
                    </div>
                    <div class="form-group mb-4">
                        <select class="form-control" name="role" required>
                            <option value="" disabled selected>Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="kasek">Kepala Sekolah</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>

                    <button class="btn btn-primary w-100 py-2 font-weight-bold" type="submit">BUAT AKUN</button>
                </form>
                <div class="mt-3 text-center">
                    <p class="text-muted">Sudah memiliki akun ? <a class="text-primary" href="login.php">Masuk sekarang!</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#passwordModal').modal({ backdrop: 'static', keyboard: false });
            $('#passwordModal').modal('show');

            var correctPassword = "admin123"; // Ganti dengan password yang diinginkan

            $('#submitPassword').click(function() {
                var enteredPassword = $('#authPassword').val();
                if (enteredPassword === correctPassword) {
                    $('#passwordModal').modal('hide');
                    $('#mainContent').removeClass('d-none');
                } else {
                    $('#errorText').removeClass('d-none');
                }
            });
        });
    </script>
</body>
</html>



<!-- <!DOCTYPE html>
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
            <h5 class="mb-5">Sebagai Admin, Staff, atau Kepala Sekolah</h5>

            <form action="" method="POST">
                <div class="form-group mb-4">
                    <input class="form-control" placeholder="Masukkan nama lengkap" type="text" name="nama" required />
                </div>
                <div class="form-group mb-4">
                    <input class="form-control" placeholder="Username" type="text" name="username" required />
                </div>
                <div class="form-group mb-4">
                    <input class="form-control" placeholder="Password" type="password" name="password" required />
                </div>
                <div class="form-group mb-4">
                    <input class="form-control" placeholder="Konfirmasi Password" type="password" name="confirm_password" required />
                </div>
                <div class="form-group mb-4">
                    <select class="form-control" name="role" required>
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="kasek">Kepala Sekolah</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>

                <button class="btn btn-primary w-100 py-2 font-weight-bold" type="submit">BUAT AKUN</button>
            </form>
            
            <?php if (isset($message)) { echo '<p class="text-danger mt-3">'.$message.'</p>'; } ?>
          

            <div class="mt-3 text-center">
                <p class="text-muted">Sudah memiliki akun ? <a class="text-primary" href="login.php">Masuk sekarang!</a></p>
            </div>
        </div>
    </div>
</body>
</html> -->

