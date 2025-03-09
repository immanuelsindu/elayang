<?php
// Memulai session dan koneksi ke database
session_start();

// Periksa apakah user sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit;
}

if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
    header("Location: ../dashboard.php");
    exit;
  }

// Cek jika tombol logout ditekan
if (isset($_POST['logout'])) {
    session_destroy();
        header("Location: ../login.php");
    exit;
}

include('../db_connection.php');

// Pesan untuk feedback pengguna
$message = "";

// Memproses form jika tombol submit ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_surat = $_POST['kode_surat'];
    $tanggal = $_POST['tanggal'];
    $nomor_surat = $_POST['nomor_surat'];
    $perihal = $_POST['perihal'];
    $kepada = $_POST['kepada'];

    // Validasi dan proses file PDF
    $upload_dir = "../uploads/";
    $upload_file = $upload_dir . basename($_FILES['upload_surat']['name']);
    $file_type = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));

    if ($file_type !== "pdf") {
        $message = "Hanya file PDF yang diperbolehkan.";
    } elseif (move_uploaded_file($_FILES["upload_surat"]["tmp_name"], $upload_file)) {
        // Simpan path file di database
        $sql = "INSERT INTO surat_edaran (kode_surat, tanggal, nomor_surat, perihal, kepada, upload_surat)
                VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssss", $kode_surat, $tanggal, $nomor_surat, $perihal, $kepada, $upload_file);
            if ($stmt->execute()) {
                $message = "Data berhasil disimpan.";
            } else {
                $message = "Error: " . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        }
    } else {
        $message = "Gagal mengupload file.";
    }
    $conn->close();
}
?>

<html lang="en">
<head>
    <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Admin Dashboard</title>
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
<body class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="p-4 rounded-lg shadow-lg w-100" style="max-width: 1200px;">
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
            <a href="read_surat_edaran.php" class="h6 mb-5">Lihat Daftar Surat Edaran</a>
        </div>

        <div class="mb-4">
            <p class="h6 fw-bold">Tambah Surat Edaran</p>
        </div>

        <!-- Form Inputan -->
        <form action="" method="POST" enctype="multipart/form-data">
          <div class="form-group row mb-3">
              <label for="kodeSurat" class="col-sm-3 col-form-label">Kode Surat</label>
              <div class="col-sm-9">
                  <input type="text" class="form-control" id="kodeSurat" name="kode_surat" placeholder="Masukkan Kode Surat" required />
              </div>
          </div>
          <div class="form-group row mb-3">
              <label for="tanggalSurat" class="col-sm-3 col-form-label">Tanggal Surat</label>
              <div class="col-sm-9">
                  <input type="date" class="form-control" id="tanggalSurat" name="tanggal" placeholder="Pilih Tanggal Surat" required />
              </div>
          </div>
          <div class="form-group row mb-3">
              <label for="nomorSurat" class="col-sm-3 col-form-label">Nomor Surat</label>
              <div class="col-sm-9">
                  <input type="text" class="form-control" id="nomorSurat" name="nomor_surat" placeholder="Masukkan Nomor Surat" required />
              </div>
          </div>
          <div class="form-group row mb-3">
              <label for="perihal" class="col-sm-3 col-form-label">Perihal</label>
              <div class="col-sm-9">
                  <input type="text" class="form-control" id="perihal" name="perihal" placeholder="Masukkan Perihal Surat" required />
              </div>
          </div>
          <div class="form-group row mb-3">
              <label for="kepada" class="col-sm-3 col-form-label">Kepada</label>
              <div class="col-sm-9">
                  <input type="text" class="form-control" id="kepada" name="kepada" placeholder="Masukkan Nama Penerima Surat" required />
              </div>
          </div>
          <div class="form-group row mb-3">
                <label for="uploadPdf" class="col-sm-3 col-form-label">Upload PDF</label>
                <div class="col-sm-9">
                    <input type="file" class="form-control" id="uploadPdf" name="upload_surat" required />
                </div>
            </div>

          <div class="d-flex justify-content-between">
              <a href="read_disposisi.php" class="btn btn-danger">Batal</a>
              <button type="submit" class="btn btn-success">Simpan</button>
          </div>
      </form>

    </div>

    <!-- Add Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
