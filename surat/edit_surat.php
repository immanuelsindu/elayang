<?php
session_start(); // Mulai session

// Periksa apakah user sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit;
}

  if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
    header("Location: ../dashboard.php");
    exit;
  }


// Koneksi ke database
include('../db_connection.php');

// Pesan untuk form setelah submit
$message = '';

// Cek apakah parameter 'id' ada di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil data surat berdasarkan ID
    $sql = "SELECT * FROM surat_masuk WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Jika data ditemukan
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "<script>alert('Data tidak ditemukan.'); window.location.href='read_surat.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Terjadi kesalahan pada server.'); window.location.href='read_surat.php';</script>";
        exit;
    }

    // Proses ketika form disubmit (POST)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Ambil data dari form
        $kodeSurat = $_POST['kodeSurat'];
        $tanggalSurat = $_POST['tanggalSurat'];
        $nomorSurat = $_POST['nomorSurat'];
        $asalSurat = $_POST['asalSurat'];
        $perihal = $_POST['perihal'];

        // Menangani upload file baru
        $filePath = $row['upload_surat']; // File path lama
        if (isset($_FILES['uploadSurat']) && $_FILES['uploadSurat']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/'; // Folder tempat menyimpan file
            $fileName = time() . "_" . basename($_FILES['uploadSurat']['name']);
            $newFilePath = $uploadDir . $fileName;

            // Validasi file
            $fileType = mime_content_type($_FILES['uploadSurat']['tmp_name']);
            $fileSize = $_FILES['uploadSurat']['size'];

            if ($fileType === 'application/pdf' && $fileSize <= 2 * 1024 * 1024) { // Maksimal 2 MB
                // Hapus file lama jika ada
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                // Pindahkan file baru
                move_uploaded_file($_FILES['uploadSurat']['tmp_name'], $newFilePath);
                $filePath = $newFilePath;
            } else {
                $message = "File harus berupa PDF dengan ukuran maksimal 2 MB.";
            }
        }

        // Update database
        $updateSql = "UPDATE surat_masuk SET kode_surat = ?, tanggal_surat = ?, nomor_surat = ?, asal_surat = ?, perihal = ?, upload_surat = ? WHERE id = ?";
        if ($updateStmt = $conn->prepare($updateSql)) {
            $updateStmt->bind_param("ssssssi", $kodeSurat, $tanggalSurat, $nomorSurat, $asalSurat, $perihal, $filePath, $id);
            if ($updateStmt->execute()) {
                $message = "Data surat berhasil diperbarui.";
                echo "<script>alert('$message'); window.location.href='read_surat.php';</script>";
                exit;
            } else {
                $message = "Terjadi kesalahan saat menyimpan perubahan.";
            }
        } else {
            $message = "Terjadi kesalahan pada server.";
        }
    }
} else {
    echo "<script>alert('ID surat tidak ditemukan.'); window.location.href='read_surat.php';</script>";
    exit;
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
  <div class="bg-white p-4 rounded shadow w-100" style="max-width: 1200px;">
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
                <?php if ($row['upload_surat']): ?>
                    <p><a href="<?php echo $row['upload_surat']; ?>" target="_blank">Lihat PDF</a></p>
                <?php endif; ?>
                <!-- <small class="text-muted">Hanya file PDF dengan ukuran maksimal 2 MB.</small> -->
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
