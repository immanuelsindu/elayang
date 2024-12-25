<?php
// Koneksi ke database
$servername = "localhost"; // Ganti dengan host database Anda
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "elayang"; // Ganti dengan nama database Anda

$conn = new mysqli($servername, $username, $password, $dbname);

// Check koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    <div class="d-flex align-items-center mb-4">
      <img alt="School logo" class="me-4" height="100"
        src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi7rtoRw8nA-XuqtQ5Wfpyy3xMh5g-Vv4iYZozeZQ_eUHpmA4nLGHHEJ3xQbIAFNwxeVzXA0Zys5A4Tsw74dPRXD7cyQ5PayEuMZFsNj7Kgpd5tuHkUhKV_iP1JiMLgTAYAP9y3rfuUdC0/s1600/Logo+SMK.jpg"
        width="100" />
      <div>
        <h1 class="h4 fw-bold">SMK Gajah Mada 01 Margoyoso</h1>
        <p class="small">Jalan Pasar Bulumanis Margoyoso Pati, Kode Pos 59154</p>
      </div>
      <div class="ms-auto d-flex align-items-center">
        <img alt="User avatar" class="rounded-circle me-2" height="50"
          src="https://static.vecteezy.com/system/resources/previews/000/439/863/original/vector-users-icon.jpg"
          width="50" />
        <span>Budi Kristiono</span>
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
              <td>
                <!-- <a href="path_to_file/<?php echo $row['upload_surat']; ?>" class="btn btn-primary btn-sm" target="_blank">
                  Lihat Surat
                </a> -->
                <a href="#" class="btn btn-primary btn-sm" target="_blank">
                  Lihat Surat
                </a>

              </td>
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
