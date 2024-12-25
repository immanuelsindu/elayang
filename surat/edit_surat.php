<?php

session_start(); // Mulai session

// Periksa apakah user sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Jika belum login, redirect ke halaman login
    header("Location: login.php");
    exit;
}

// Cek jika tombol logout ditekan
if (isset($_POST['logout'])) {
  // Hapus session dan logout
  session_destroy();
  header("Location: login.php");
  exit;
}

// Koneksi ke database
include('../db_connection.php');

// Pesan untuk form setelah submit
$message = '';

// Cek apakah parameter 'id' ada di URL
if (isset($_GET['id'])) {
    // Ambil ID surat dari URL
    $id = $_GET['id'];

    // Query untuk mengambil data surat berdasarkan ID
    $sql = "SELECT * FROM surat_masuk WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Jika data ditemukan
        if ($result->num_rows > 0) {
            // Ambil data surat
            $row = $result->fetch_assoc();
        } else {
            echo "<script>alert('Data tidak ditemukan.'); window.location.href='read_surat.php';</script>";
        }
    } else {
        echo "<script>alert('Terjadi kesalahan pada server.'); window.location.href='read_surat.php';</script>";
    }

    // Proses ketika form disubmit (POST)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Ambil data dari form
        $kodeSurat = $_POST['kodeSurat'];
        $tanggalSurat = $_POST['tanggalSurat'];
        $nomorSurat = $_POST['nomorSurat'];
        $asalSurat = $_POST['asalSurat'];
        $perihal = $_POST['perihal'];
        $uploadSurat = $_FILES['uploadSurat'];

        // Menangani upload file PDF jika ada
        $filePath = null;
        if ($uploadSurat['error'] == 0) {
            $uploadDir = 'uploads/'; // Sesuaikan dengan direktori upload
            $fileName = time() . "_" . basename($uploadSurat['name']);
            $filePath = $uploadDir . $fileName;
            move_uploaded_file($uploadSurat['tmp_name'], $filePath);
        } else {
            // Jika tidak ada file yang diupload, biarkan filePath tetap null
            $filePath = $row['file_path']; // Tetap menggunakan file yang lama
        }

        // Query untuk memperbarui data surat
        $updateSql = "UPDATE surat_masuk SET kode_surat = ?, tanggal_surat = ?, nomor_surat = ?, asal_surat = ?, perihal = ?, upload_surat = ? WHERE id = ?";
        
        if ($updateStmt = $conn->prepare($updateSql)) {
            $updateStmt->bind_param("ssssssi", $kodeSurat, $tanggalSurat, $nomorSurat, $asalSurat, $perihal, $filePath, $id);
            if ($updateStmt->execute()) {
                $message = "Data surat berhasil diperbarui.";
                echo "<script>alert('$message'); window.location.href='read_surat.php';</script>";
            } else {
                $message = "Terjadi kesalahan saat menyimpan perubahan.";
            }
        } else {
            $message = "Terjadi kesalahan pada server.";
        }
    }
} else {
    echo "<script>alert('ID surat tidak ditemukan.'); window.location.href='read_surat.php';</script>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>SMK Gajah Mada 01 Margoyoso</title>
  <!-- Add Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script>
        // Menampilkan alert jika ada pesan dari PHP
        <?php if ($message != ''): ?>
            alert('<?php echo $message; ?>');
        <?php endif; ?>
    </script>
</head>

<body class="bg-light d-flex justify-content-center align-items-center min-vh-100">
  <div class="bg-white p-4 rounded shadow w-100" style="max-width: 768px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div class="d-flex align-items-center">
        <img alt="School Logo" class="me-3" height="50"
          src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi7rtoRw8nA-XuqtQ5Wfpyy3xMh5g-Vv4iYZozeZQ_eUHpmA4nLGHHEJ3xQbIAFNwxeVzXA0Zys5A4Tsw74dPRXD7cyQ5PayEuMZFsNj7Kgpd5tuHkUhKV_iP1JiMLgTAYAP9y3rfuUdC0/s1600/Logo+SMK.jpg"
          width="50" />
        <div>
          <h1 class="h4 fw-bold">
            SMK Gajah Mada 01 Margoyoso
          </h1>
          <p>
            Jalan Pasar Bulumanis Margoyoso Pati, Kode Pos 59154
          </p>
        </div>
      </div>
      <div class="d-flex justify-content-center align-items-center">
          <!-- User Avatar -->
          <img alt="User Avatar" class="rounded-circle me-2" height="50"
              src="https://static.vecteezy.com/system/resources/previews/000/439/863/original/vector-users-icon.jpg"
              width="50" />
          
          <!-- Username -->
          <span class="me-3">
              <?php echo $_SESSION['nama']; ?>
          </span>
          
         <!-- Logout Button dengan Tooltip -->
        <form method="POST" class="mb-0">
            <button type="submit" name="logout" class="btn btn-danger" data-bs-toggle="tooltip" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </form>
      </div>
    </div>  

    <div class="mb-4">
      <a href="read_surat.php" class="h6 mb-5">Lihat Daftar Surat</a>
    </div>

    <div class="mb-4">
      <p class="h6 fw-bold">Edit Surat Masuk</p>
    </div>

    <div class="d-flex gap-2 mb-4">
    </div>
    <div class="mb-4">
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3 row">
            <label for="kodeSurat" class="col-sm-3 col-form-label">Kode Surat</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="kodeSurat" name="kodeSurat" placeholder="Masukkan kode surat (contoh: SM001)" 
                value="<?php echo isset($row['kode_surat']) ? $row['kode_surat'] : ''; ?>" required />
            </div>
        </div>
        <div class="mb-3 row">
            <label for="tanggalSurat" class="col-sm-3 col-form-label">Tanggal Surat</label>
            <div class="col-sm-9">
                <input type="date" class="form-control" id="tanggalSurat" name="tanggalSurat" 
                value="<?php echo isset($row['tanggal_surat']) ? $row['tanggal_surat'] : ''; ?>" required />
            </div>
        </div>
        <div class="mb-3 row">
            <label for="nomorSurat" class="col-sm-3 col-form-label">Nomor Surat</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="nomorSurat" name="nomorSurat" placeholder="Masukkan nomor surat (contoh: 123/SM/2023)" 
                value="<?php echo isset($row['nomor_surat']) ? $row['nomor_surat'] : ''; ?>" required />
            </div>
        </div>
        <div class="mb-3 row">
            <label for="asalSurat" class="col-sm-3 col-form-label">Asal Surat</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="asalSurat" name="asalSurat" placeholder="Masukkan asal surat" 
                value="<?php echo isset($row['asal_surat']) ? $row['asal_surat'] : ''; ?>" required />
            </div>
        </div>
        <div class="mb-3 row">
            <label for="perihal" class="col-sm-3 col-form-label">Perihal</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="perihal" name="perihal" placeholder="Masukkan perihal surat" 
                value="<?php echo isset($row['perihal']) ? $row['perihal'] : ''; ?>" required />
            </div>
        </div>
        <div class="mb-3 row">
            <label for="uploadSurat" class="col-sm-3 col-form-label">Upload PDF</label>
            <div class="col-sm-9">
                <input type="file" class="form-control" id="uploadSurat" name="uploadSurat" accept="application/pdf" />
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <a href="read_surat.php" class="btn btn-danger" type="reset">Batal</a>
            <button class="btn btn-success" type="submit">Simpan Perubahan</button>
        </div>
    </form>
    </div>
  </div>
</body>

</html>
