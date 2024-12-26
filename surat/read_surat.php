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

// Query untuk mengambil semua data surat masuk
$sql = "SELECT * FROM surat_masuk";
$result = $conn->query($sql);
?>

<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>SMK Gajah Mada 01 Margoyoso</title>
  <!-- Add Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-light p-4">
  <div class="container max-w-4xl bg-white p-4 rounded-lg shadow-lg">
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
  

    <div class="mt-4">
      <div class="d-flex justify-content-between align-items-center">
        <p class="h5 font-weight-bold">
          Surat Masuk
        </p>

        <a href="add_surat.php" class="btn btn-success">
          Tambah Surat
        </a>
      </div>
    </div>
    <div class="mt-4">
      <table class="table table-bordered">
        <thead>
          <tr class="table-light">
            <th>Kode Surat</th>
            <th>Tanggal Surat</th>
            <th>Nomor Surat</th>
            <th>Asal Surat</th>
            <th>Perihal</th>
            <th>File Surat</th>
            <th>Aksi</th> 
          </tr>
        </thead>
        <tbody>
            <?php
            // Cek apakah ada data dari hasil query
            if ($result->num_rows > 0) {
                // Loop melalui data yang diambil dari database dan tampilkan
                while ($row = $result->fetch_assoc()) {
                    // Convert tanggal menjadi format yang mudah dibaca
                    $tanggalSurat = date('d-m-Y', strtotime($row['tanggal_surat']));
                    echo "<tr>
                            <td>{$row['kode_surat']}</td>
                            <td>{$tanggalSurat}</td>
                            <td>{$row['nomor_surat']}</td>
                            <td>{$row['asal_surat']}</td>
                            <td>{$row['perihal']}</td>
                            <td><a href='view_pdf.php?id={$row['id']}' class='btn btn-primary btn-sm'>Lihat Surat</a></td>
                            <td>
                              <!-- Tombol Edit -->
                              <a href='edit_surat.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                              <!-- Tombol Hapus -->
                              <a href='delete_surat.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Anda yakin ingin menghapus surat ini?\")'>Hapus</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>Tidak ada data surat masuk.</td></tr>";
            }
            ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Add Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
