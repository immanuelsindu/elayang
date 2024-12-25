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

    // Jika ada file baru yang di-upload, simpan file tersebut
    if ($upload_pdf) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($upload_pdf);
        move_uploaded_file($_FILES["upload_pdf"]["tmp_name"], $target_file);
    } else {
        $target_file = $row['upload_surat']; // Jika tidak ada file baru, gunakan file yang lama
    }

    // Buat query untuk update data disposisi
    $update_sql = "UPDATE disposisi SET kode_surat = ?, tanggal = ?, perihal = ?, nomor_surat = ?, kepada = ?, upload_surat = ? WHERE id = ?";

    if ($stmt = $conn->prepare($update_sql)) {
        // Bind parameter
        $stmt->bind_param("ssssssi", $kode_surat, $tanggal, $perihal, $nomor_surat, $kepada, $target_file, $id);

        // Eksekusi query
        if ($stmt->execute()) {
            // Jika berhasil, alihkan ke halaman daftar disposisi
            header("Location: read_disposisi.php"); // Ganti dengan halaman daftar disposisi Anda
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        // Tutup statement
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
    <title>Edit Disposisi</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
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
                <img alt="School logo" class="w-10 mr-4" height="80"
                    src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi7rtoRw8nA-XuqtQ5Wfpyy3xMh5g-Vv4iYZozeZQ_eUHpmA4nLGHHEJ3xQbIAFNwxeVzXA0Zys5A4Tsw74dPRXD7cyQ5PayEuMZFsNj7Kgpd5tuHkUhKV_iP1JiMLgTAYAP9y3rfuUdC0/s1600/Logo+SMK.jpg"
                    width="80" />
                <div>
                    <h1 class="h4 font-weight-bold">SMK Gajah Mada 01 Margoyoso</h1>
                    <p>Jalan Pasar Bulumanis Margoyoso Pati, Kode Pos 59154</p>
                </div>
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
                    <input type="file" class="form-control" id="upload-pdf" name="upload_pdf" />
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
