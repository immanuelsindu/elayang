<?php
// Include file koneksi
include('../db_connection.php');
$message = ''; // Variabel untuk menyimpan pesan sukses atau error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $kodeSurat = $_POST['kodeSurat'];
    $tanggalSurat = $_POST['tanggalSurat'];
    $nomorSurat = $_POST['nomorSurat'];
    $asalSurat = $_POST['asalSurat'];
    $perihal = $_POST['perihal'];
    
    // Proses upload file PDF
    if (isset($_FILES['uploadSurat']) && $_FILES['uploadSurat']['error'] == 0) {
        $fileTmpName = $_FILES['uploadSurat']['tmp_name'];
        $fileData = file_get_contents($fileTmpName); // Membaca file sebagai data binary
    } else {
        $fileData = null; // Tidak ada file yang diupload
    }

    // Menyimpan data ke dalam database
    $sql = "INSERT INTO surat_masuk (kode_surat, tanggal_surat, nomor_surat, asal_surat, perihal, upload_surat) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    // Persiapkan statement untuk menghindari SQL injection
    $stmt = $conn->prepare($sql);
    
    // Bind parameter: 'sssssb' untuk 5 string dan 1 binary
    $stmt->bind_param("sssssb", $kodeSurat, $tanggalSurat, $nomorSurat, $asalSurat, $perihal, $fileData);
    
    if ($stmt->execute()) {
        $message = "Data berhasil disimpan."; // Pesan sukses
    } else {
        $message = "Error: " . $stmt->error; // Pesan error
    }

    // Menutup statement dan koneksi
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SMK Gajah Mada 01 Margoyoso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
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
        <img alt="School logo" class="me-3" height="50"
          src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi7rtoRw8nA-XuqtQ5Wfpyy3xMh5g-Vv4iYZozeZQ_eUHpmA4nLGHHEJ3xQbIAFNwxeVzXA0Zys5A4Tsw74dPRXD7cyQ5PayEuMZFsNj7Kgpd5tuHkUhKV_iP1JiMLgTAYAP9y3rfuUdC0/s1600/Logo+SMK.jpg"
          width="50" />
        <div>
          <h1 class="h5 fw-bold">SMK Gajah Mada 01 Margoyoso</h1>
          <p class="mb-0">Jalan Pasar Bulumanis Margoyoso Pati, Kode Pos 59154</p>
        </div>
      </div>
      <div class="d-flex align-items-center">
        <img alt="User profile picture" class="rounded-circle me-2" height="50"
          src="https://static.vecteezy.com/system/resources/previews/000/439/863/original/vector-users-icon.jpg"
          width="50" />
        <span>Budi Kristiono</span>
      </div>
    </div>

    <div class="mb-4">
      <a href="read_surat.php" class="h6 mb-5">Lihat Daftar Surat</a>
    </div>

    <div class="mb-4">
      <p class="h6 fw-bold">Tambah Surat Masuk</p>
    </div>

    <div class="d-flex gap-2 mb-4">
    </div>
    <div class="mb-4">
    <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3 row">
                <label for="kodeSurat" class="col-sm-3 col-form-label">Kode Surat</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="kodeSurat" name="kodeSurat" placeholder="Masukkan kode surat (contoh: SM001)" required />
                </div>
            </div>
            <div class="mb-3 row">
                <label for="tanggalSurat" class="col-sm-3 col-form-label">Tanggal Surat</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control" id="tanggalSurat" name="tanggalSurat" required />
                </div>
            </div>
            <div class="mb-3 row">
                <label for="nomorSurat" class="col-sm-3 col-form-label">Nomor Surat</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="nomorSurat" name="nomorSurat" placeholder="Masukkan nomor surat (contoh: 123/SM/2023)" required />
                </div>
            </div>
            <div class="mb-3 row">
                <label for="asalSurat" class="col-sm-3 col-form-label">Asal Surat</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="asalSurat" name="asalSurat" placeholder="Masukkan asal surat" required />
                </div>
            </div>
            <div class="mb-3 row">
                <label for="perihal" class="col-sm-3 col-form-label">Perihal</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="perihal" name="perihal" placeholder="Masukkan perihal surat" required />
                </div>
            </div>
            <div class="mb-3 row">
                <label for="uploadSurat" class="col-sm-3 col-form-label">Upload PDF</label>
                <div class="col-sm-9">
                    <input type="file" class="form-control" id="uploadSurat" name="uploadSurat" accept="application/pdf" />
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <a href="read_surat.html" class="btn btn-danger" type="reset">Batal</a>
                <button class="btn btn-success" type="submit">Simpan</button>
            </div>
        </form>
    </div>

    <!-- <div class="d-flex justify-content-between">
      <button class="btn btn-danger">Batal</button>
      <button class="btn btn-success">Simpan</button>
    </div> -->
  </div>
</body>

</html>