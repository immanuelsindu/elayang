<?php
// Koneksi ke database
include('../db_connection.php');

// Periksa jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $kode_surat = $_POST['kode_surat'];
    $tanggal = $_POST['tanggal'];
    $nomor_surat = $_POST['nomor_surat'];
    $perihal = $_POST['perihal'];
    $kepada = $_POST['kepada'];

    // Upload file PDF
    $upload_surat = null;
    if (isset($_FILES['upload_pdf']) && $_FILES['upload_pdf']['error'] == 0) {
        $upload_surat = file_get_contents($_FILES['upload_pdf']['tmp_name']);
    }

    // Query untuk memasukkan data ke dalam tabel disposisi
    $sql = "INSERT INTO disposisi (kode_surat, tanggal, perihal, nomor_surat, kepada, upload_surat)
            VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("sssssb", $kode_surat, $tanggal, $perihal, $nomor_surat, $kepada, $upload_surat);

        // Eksekusi query
        if ($stmt->execute()) {
            $message = "Data berhasil disimpan."; // Pesan sukses
        } else {
            $message = "Error: " . $stmt->error; // Pesan error
        }
  

        $stmt->close();
    } else {
        $message = "Error pada query: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>SMK Gajah Mada 01 Margoyoso</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
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
    <div class="bg-white p-5 rounded-lg shadow-lg w-100" style="max-width: 900px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <img alt="School logo" class="w-10 mr-4" height="80"
                    src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi7rtoRw8nA-XuqtQ5Wfpyy3xMh5g-Vv4iYZozeZQ_eUHpmA4nLGHHEJ3xQbIAFNwxeVzXA0Zys5A4Tsw74dPRXD7cyQ5PayEuMZFsNj7Kgpd5tuHkUhKV_iP1JiMLgTAYAP9y3rfuUdC0/s1600/Logo+SMK.jpg"
                    width="80" />
                <div>
                    <h1 class="h4 font-weight-bold">SMK Gajah Mada 01 Margoyoso</h1>
                    <p>Jalan Pasar Bulumanis Margoyoso Pati, Kode Pos 59154</p>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <img alt="User avatar" class="rounded-circle mr-2" width="50" height="50"
                    src="https://static.vecteezy.com/system/resources/previews/000/439/863/original/vector-users-icon.jpg" />
                <span>Budi Kristiono</span>
            </div>
        </div>

        <div class="mb-4">
            <a href="read_disposisi.php" class="h6 mb-5">Lihat Daftar Disposisi</a>
        </div>

        <div class="mb-4">
            <p class="h6 fw-bold">Tambah Disposisi</p>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="kode-surat">Kode Surat</label>
                    <input type="text" class="form-control" id="kode-surat" name="kode_surat" placeholder="Masukkan Kode Surat" required />
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
                    <input type="text" class="form-control" id="kepada" name="kepada" placeholder="Masukkan Nama Penerima" required />
                </div>
                <div class="col-md-6 mb-3">
                    <label for="upload-pdf">Upload PDF</label>
                    <input type="file" class="form-control" id="upload-pdf" name="upload_pdf" placeholder="Pilih File PDF" />
                </div>
            </div>

            <!-- <button type="submit" class="btn btn-primary w-100 py-2 font-weight-bold">Simpan</button> -->

            <div class="d-flex justify-content-between">
                <a href="read_disposisi.html" class="btn btn-danger" type="reset">Batal</a>
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
