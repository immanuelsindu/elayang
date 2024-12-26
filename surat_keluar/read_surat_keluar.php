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

include('../db_connection.php');

// Query untuk mengambil data dari tabel surat_keluar
$sql = "SELECT * FROM surat_keluar";
$result = $conn->query($sql);

// Hapus data jika tombol hapus ditekan
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM surat_keluar WHERE id = $delete_id";
    if ($conn->query($delete_sql) === TRUE) {
        $message = "Data berhasil dihapus!";
    } else {
        $message = "Gagal menghapus data: " . $conn->error;
    }
}

?>

<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body class=" bg-opacity-25 py-5">
  <div class="container bg-light p-4 rounded shadow-lg">
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

    <div class="mt-4 mb-3">
      <div class="d-flex justify-content-between align-items-center">
        <p class="h5 font-weight-bold">
          Surat Keluar
        </p>

        <a href="add_surat_keluar.php" class="btn btn-success">
          Tambah Surat Keluar
        </a>
      </div>
    </div>

    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th scope="col">Kode Surat</th>
          <th scope="col">Tanggal Surat</th>
          <th scope="col">Nomor Surat</th>
          <th scope="col">Perihal</th>
          <th scope="col">Kepada</th>
          <th scope="col">File Surat</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0) : ?>
          <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
              <td><?php echo $row['kode_surat']; ?></td>
              <td><?php echo $row['tanggal']; ?></td>
              <td><?php echo $row['nomor_surat']; ?></td>
              <td><?php echo $row['perihal']; ?></td>
              <td><?php echo $row['kepada']; ?></td>
              <td><a href='view_pdf.php?id=<?php echo $row['id']; ?>' class='btn btn-primary btn-sm'>Lihat Surat</a></td>
              <td>
                <a href="edit_surat_keluar.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete_surat_keluar.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else : ?>
          <tr>
            <td colspan="7" class="text-center">Tidak ada data surat keluar.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>

<?php
// Tutup koneksi
$conn->close();
?>
