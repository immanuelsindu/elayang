<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

include('../db_connection.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM surat_edaran WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
        } else {
            $_SESSION['message'] = "Data tidak ditemukan.";
            $_SESSION['msg_type'] = "danger";
            header("Location: read_surat_edaran.php");
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_surat = $_POST['kode_surat'];
    $tanggal = $_POST['tanggal'];
    $nomor_surat = $_POST['nomor_surat'];
    $perihal = $_POST['perihal'];
    $kepada = $_POST['kepada'];

    $upload_surat = $_FILES['upload_surat'];
    $file_path = null;

    if ($upload_surat['error'] == UPLOAD_ERR_OK) {
      $target_dir = "../uploads/";
      $target_file = $target_dir . basename($upload_surat['name']);
      $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  
      if ($file_type != "pdf") {
          echo "Hanya file PDF yang diperbolehkan.";
          exit;
      }
  
      if ($upload_surat["size"] > 5000000) {
          echo "File terlalu besar.";
          exit;
      }
  
      if (move_uploaded_file($upload_surat["tmp_name"], $target_file)) {
          $file_path = $target_file;
      } else {
          echo "Terjadi kesalahan saat mengunggah file.";
          exit;
      }
  }
  
    $update_sql = "UPDATE surat_edaran SET kode_surat = ?, tanggal = ?, nomor_surat = ?, perihal = ?, kepada = ?";
    if ($file_path) {
        $update_sql .= ", upload_surat = ?";
    }
    $update_sql .= " WHERE id = ?";

    if ($stmt = $conn->prepare($update_sql)) {
        if ($file_path) {
            $stmt->bind_param("ssssssi", $kode_surat, $tanggal, $nomor_surat, $perihal, $kepada, $file_path, $id);
        } else {
            $stmt->bind_param("sssssi", $kode_surat, $tanggal, $nomor_surat, $perihal, $kepada, $id);
        }

        if ($stmt->execute()) {
            $_SESSION['message'] = "Data surat edaran berhasil diperbarui.";
            $_SESSION['msg_type'] = "success";
            header("Location: read_surat_edaran.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!-- HTML Form Edit Surat Edaran -->
<html lang="en">
<head>
<meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Admin Dashboard</title>
  <!-- Add Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="p-4 rounded-lg shadow-lg w-100" style="max-width: 900px;">
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
            <a href="read_surat_edaran.php" class="h6 mb-5">Lihat Daftar Surat Edaran</a>
        </div>

        <div class="mb-4">
            <p class="h6 fw-bold">Edit Surat Edaran</p>
        </div>

        <!-- Form Inputan -->
<form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group row mb-3">
        <label for="kodeSurat" class="col-sm-3 col-form-label">Kode Surat</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="kodeSurat" name="kode_surat" value="<?= $data['kode_surat']; ?>" required />
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="tanggalSurat" class="col-sm-3 col-form-label">Tanggal Surat</label>
        <div class="col-sm-9">
            <input type="date" class="form-control" id="tanggalSurat" name="tanggal" value="<?= $data['tanggal']; ?>" required />
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="nomorSurat" class="col-sm-3 col-form-label">Nomor Surat</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="nomorSurat" name="nomor_surat" value="<?= $data['nomor_surat']; ?>" required />
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="perihal" class="col-sm-3 col-form-label">Perihal</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="perihal" name="perihal" value="<?= $data['perihal']; ?>" required />
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="kepada" class="col-sm-3 col-form-label">Kepada</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="kepada" name="kepada" value="<?= $data['kepada']; ?>" required />
        </div>
    </div>
    <div class="form-group row mb-3">
        <label for="uploadPdf" class="col-sm-3 col-form-label">Upload PDF</label>
        <div class="col-sm-9">
            <input type="file" class="form-control" id="uploadPdf" name="upload_surat" />
            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah file PDF.</small>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="read_surat_edaran.php" class="btn btn-danger">Batal</a>
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
