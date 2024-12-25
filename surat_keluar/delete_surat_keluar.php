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

// Periksa apakah ada id yang dikirimkan
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Query untuk menghapus data berdasarkan id
    $sql = "DELETE FROM surat_keluar WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        // Jika penghapusan berhasil, arahkan ke halaman utama
        header("Location: read_surat_keluar.php");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Tutup koneksi
$conn->close();
?>
