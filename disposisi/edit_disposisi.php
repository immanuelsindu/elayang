<?php
// Mulai session
session_start();

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

// Periksa apakah ada ID yang diteruskan di URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data disposisi berdasarkan ID
    $sql = "SELECT * FROM disposisi WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameter
        $stmt->bind_param("i", $id);

        // Eksekusi query
        $stmt->execute();
        $result = $stmt->get_result();

        // Jika data ditemukan, masukkan ke dalam form
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "Data tidak ditemukan.";
            exit;
        }

        // Tutup statement
        $stmt->close();
    }
} else {
    // Jika tidak ada ID yang diteruskan, alihkan ke halaman disposisi
    header("Location: index.php"); // Ganti dengan halaman disposisi Anda
    exit;
}

// Proses update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Ambil data dari form
  $kode_surat = $_POST['kode_surat'];
  $tanggal = $_POST['tanggal'];
  $perihal = $_POST['perihal'];
  $nomor_surat = $_POST['nomor_surat'];
  $kepada = $_POST['kepada'];
  $upload_pdf = $_FILES['upload_pdf']['name'];
  $target_file = $row['upload_surat']; // Default ke file lama

  // Jika ada file baru yang di-upload
  if ($upload_pdf) {
      $target_dir = "../uploads/";
      $target_file = $target_dir . basename($upload_pdf);

      // Validasi ukuran dan jenis file
      $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
      if ($file_type != "pdf") {
          echo "Hanya file PDF yang diperbolehkan.";
          exit;
      }

      if ($_FILES["upload_pdf"]["size"] > 5000000) { // Batas ukuran 5MB
          echo "File terlalu besar.";
          exit;
      }

      // Pindahkan file baru ke folder tujuan
      if (move_uploaded_file($_FILES["upload_pdf"]["tmp_name"], $target_file)) {
          // Hapus file lama jika file baru berhasil diunggah
          if ($row['upload_surat'] && file_exists($row['upload_surat'])) {
              unlink($row['upload_surat']);
          }
      } else {
          echo "Terjadi kesalahan saat mengunggah file.";
          exit;
      }
  }

  // Query untuk update data disposisi
  $update_sql = "UPDATE disposisi SET kode_surat = ?, tanggal = ?, perihal = ?, nomor_surat = ?, kepada = ?, upload_surat = ? WHERE id = ?";
  if ($stmt = $conn->prepare($update_sql)) {
      $stmt->bind_param("ssssssi", $kode_surat, $tanggal, $perihal, $nomor_surat, $kepada, $target_file, $id);
      if ($stmt->execute()) {
          header("Location: read_disposisi.php");
          exit;
      } else {
          echo "Error: " . $stmt->error;
      }
      $stmt->close();
  }
}


// Tutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
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
</head>

<body class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="bg-white p-5 rounded-lg shadow-lg w-100" style="max-width: 900px;">
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
            <a href="read_disposisi.php" class="h6 mb-5">Lihat Daftar Disposisi</a>
        </div>

        <div class="mb-4">
            <p class="h6 fw-bold">Edit Disposisi</p>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="kode-surat">Kode Surat</label>
                    <input type="text" class="form-control" id="kode-surat" name="kode_surat" value="<?php echo $row['kode_surat']; ?>" required />
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tanggal-surat">Tanggal Surat</label>
                    <input type="date" class="form-control" id="tanggal-surat" name="tanggal" value="<?php echo $row['tanggal']; ?>" required />
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nomor-surat">Nomor Surat</label>
                    <input type="text" class="form-control" id="nomor-surat" name="nomor_surat" value="<?php echo $row['nomor_surat']; ?>" required />
                </div>
                <div class="col-md-6 mb-3">
                    <label for="perihal">Perihal</label>
                    <input type="text" class="form-control" id="perihal" name="perihal" value="<?php echo $row['perihal']; ?>" required />
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kepada">Kepada</label>
                    <input type="text" class="form-control" id="kepada" name="kepada" value="<?php echo $row['kepada']; ?>" required />
                </div>
                <div class="col-md-6 mb-3">
                    <label for="upload-pdf">Upload PDF (Kosongkan jika tidak ada perubahan)</label>
                    <input type="file" class="form-control" id="upload-pdf" name="upload_pdf"   />
                    <?php if ($row['upload_surat']): ?>
                        <p><a href="../uploads/<?php echo $row['upload_surat']; ?>" target="_blank">Lihat PDF</a></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="read_disposisi.php" class="btn btn-danger" type="reset">Batal</a>
                <button class="btn btn-success" type="submit">Simpan</button>
            </div>
        </form>

    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
