<?php
session_start(); // Mulai session

// Periksa apakah user sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Jika belum login, redirect ke halaman login
        header("Location: ../login.php");
    exit;
}

// Cek jika tombol logout ditekan
if (isset($_POST['logout'])) {
  // Hapus session dan logout
  session_destroy();
      header("Location: ../login.php");
  exit;
}

include('../db_connection.php');

$message = ""; // Inisialisasi pesan

// Proses form jika tombol submit ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $kode_surat = $_POST['kode_surat'];
    $tanggal = $_POST['tanggal'];
    $nomor_surat = $_POST['nomor_surat'];
    $perihal = $_POST['perihal'];
    $kepada = $_POST['kepada'];

    // Proses upload file PDF
    if (isset($_FILES['upload_surat']) && $_FILES['upload_surat']['error'] == UPLOAD_ERR_OK) {
        $upload_surat = $_FILES['upload_surat'];

        // Validasi tipe file PDF
        $file_type = strtolower(pathinfo($upload_surat['name'], PATHINFO_EXTENSION));
        if ($file_type != "pdf") {
            $message = "Hanya file PDF yang diperbolehkan.";
        } elseif ($upload_surat['size'] > 5000000) { // Maksimal ukuran file 5MB
            $message = "File terlalu besar.";
        } else {
            // Tentukan nama file dan lokasi tujuan penyimpanan
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($upload_surat['name']);

            // Pindahkan file ke folder upload
            if (move_uploaded_file($upload_surat['tmp_name'], $target_file)) {
                // Query untuk memasukkan data ke tabel surat_keluar
                $sql = "INSERT INTO surat_keluar (kode_surat, tanggal, nomor_surat, perihal, kepada, upload_surat) 
                        VALUES ('$kode_surat', '$tanggal', '$nomor_surat', '$perihal', '$kepada', ?)";
                
                // Persiapkan statement
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $target_file); // Menyimpan path file sebagai string

                // Eksekusi query
                if ($stmt->execute()) {
                    $message = "Data berhasil disimpan."; // Pesan sukses
                } else {
                    $message = "Error: " . $stmt->error; // Pesan error
                }

                // Tutup statement
                $stmt->close();
            } else {
                $message = "Terjadi kesalahan saat mengunggah file.";
            }
        }
    }
}

$conn->close();
?>

<html lang="en">
<head>
<meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Admin Dashboard</title>
  <!-- Add Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f5e0c3;
    }
  </style>
  <script>
        // Menampilkan alert jika ada pesan dari PHP
        <?php if ($message != ''): ?>
            alert('<?php echo $message; ?>');
        <?php endif; ?>
    </script>
</head>

<body class="d-flex justify-content-center align-items-center min-vh-100">
  <div class="bg-white p-5 rounded-lg shadow-lg w-100" style="max-width: 1200px;">
  <div class="d-flex justify-content-between align-items-center mb-4">
  <a href="../dashboard.php" class="text-decoration-none text-dark">
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
      </a>
      <div class="d-flex justify-content-center align-items-center">
          <!-- User Avatar -->
          <img alt="User Avatar" class="rounded-circle me-2" height="50"
              src="https://static.vecteezy.com/system/resources/previews/000/439/863/original/vector-users-icon.jpg"
              width="50" />
          
          <!-- Username -->
          <div class="d-flex flex-column me-3">
            <div>
                <b>
                <?php echo $_SESSION['nama']; ?>
                </b>
            </div>

            <div>
            <span class="text-capitalize">
              <?php echo $_SESSION['role']; ?>
              </span>
            </div>
           </div>
          
         <!-- Logout Button dengan Tooltip -->
        <form method="POST" class="mb-0">
            <button type="submit" name="logout" class="btn btn-danger" data-bs-toggle="tooltip" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </form>
      </div>
    </div>

    <div class="mb-4">
        <a href="read_surat_keluar.php" class="h6 mb-5">Lihat Daftar Surat Keluar</a>
    </div>

    <div class="mb-4">
        <p class="h6 fw-bold">Tambah Surat Keluar</p>
    </div>

    <!-- Form Inputan -->
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <label for="kode-surat">Kode Surat</label>
                <input type="text" class="form-control" id="kode-surat" name="kode_surat"  placeholder="Masukkan Kode Surat" required />
            </div>
            <div class="col-md-6 mb-3">
                <label for="tanggal-surat">Tanggal Surat</label>
                <input type="date" class="form-control" id="tanggal-surat" name="tanggal" placeholder="Pilih Tanggal Surat" required />
            </div>
            <div class="col-md-6 mb-3">
                <label for="nomor-surat">Nomor Surat</label>
                <input type="text" class="form-control" id="nomor-surat" name="nomor_surat" placeholder="Masukkan Nomor Surat" required />
            </div>
            <div class="col-md-6 mb-3">
                <label for="perihal">Perihal</label>
                <input type="text" class="form-control" id="perihal" name="perihal" placeholder="Masukkan Perihal Surat" required />
            </div>
            <div class="col-md-6 mb-3">
                <label for="kepada">Kepada</label>
                <input type="text" class="form-control" id="kepada" name="kepada" placeholder="Masukkan Nama Penerima Surat" required />
            </div>
            <div class="col-md-6 mb-3">
                <label for="upload-pdf">Upload PDF</label>
                <input type="file" class="form-control" id="upload-pdf" name="upload_surat" required />
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="read_surat_keluar.php" class="btn btn-danger">Batal</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>

   
  </div>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
