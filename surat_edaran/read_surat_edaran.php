<?php
// Memulai session dan koneksi ke database
session_start();
include('../db_connection.php');

// Query untuk mengambil semua data dari tabel surat_edaran
$sql = "SELECT * FROM surat_edaran";
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

<body class="py-5">
  <div class="container p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div class="d-flex align-items-center">
        <img alt="School logo" class="me-3" height="80"
          src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi7rtoRw8nA-XuqtQ5Wfpyy3xMh5g-Vv4iYZozeZQ_eUHpmA4nLGHHEJ3xQbIAFNwxeVzXA0Zys5A4Tsw74dPRXD7cyQ5PayEuMZFsNj7Kgpd5tuHkUhKV_iP1JiMLgTAYAP9y3rfuUdC0/s1600/Logo+SMK.jpg"
          width="80" />
        <div>
          <h1 class="h4 fw-bold">
            SMK Gajah Mada 01 Margoyoso
          </h1>
          <p>
            Jalan Pasar Bulumanis Margoyoso Pati, Kode Pos 59154
          </p>
        </div>
      </div>
      <div class="d-flex align-items-center">
        <img alt="Profile picture of Budi Kristiono" class="rounded-circle me-2" height="50"
          src="https://static.vecteezy.com/system/resources/previews/000/439/863/original/vector-users-icon.jpg"
          width="50" />
        <span>
          Budi Kristiono
        </span>
      </div>
    </div>

    <div class="mt-4 mb-3">
      <div class="d-flex justify-content-between align-items-center">
        <p class="h5 font-weight-bold">
          Surat Edaran
        </p>

        <a href="add_surat_edaran.php" class="btn btn-success">
          Tambah Surat Edaran
        </a>
      </div>
    </div>

    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Kode Surat</th>
          <th>Tanggal Surat</th>
          <th>Nomor Surat</th>
          <th>Perihal</th>
          <th>Kepada</th>
          <th>File Surat</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Mengecek apakah ada data dalam tabel
        if ($result->num_rows > 0) {
            // Output data dari setiap baris
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['kode_surat'] . "</td>";
                echo "<td>" . $row['tanggal'] . "</td>";
                echo "<td>" . $row['nomor_surat'] . "</td>";
                echo "<td>" . $row['perihal'] . "</td>";
                echo "<td>" . $row['kepada'] . "</td>";
                
                // Menampilkan file PDF jika ada
                if ($row['upload_surat']) {
                    echo "<td><a href='data:application/pdf;base64," . base64_encode($row['upload_surat']) . "' target='_blank'>Lihat PDF</a></td>";
                } else {
                    echo "<td>Tidak ada file</td>";
                }

                // Kolom Aksi (Edit dan Hapus)
                echo "<td>";
                echo "<a href='edit_surat_edaran.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a> ";
                echo "<a href='delete_surat_edaran.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus data?\")'>Hapus</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Tidak ada data</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Add Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Menutup koneksi database
$conn->close();
?>
