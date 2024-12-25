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

    // Buat query untuk menghapus data disposisi berdasarkan ID
    $sql = "DELETE FROM disposisi WHERE id = ?";

    // Siapkan statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameter
        $stmt->bind_param("i", $id);

        // Eksekusi query
        if ($stmt->execute()) {
            // Jika berhasil, alihkan ke halaman utama disposisi
            header("Location: read_disposisi.php"); // Ganti dengan halaman disposisi Anda
            exit;
        } else {
            // Jika terjadi kesalahan saat eksekusi query
            echo "Error: " . $stmt->error;
        }

        // Tutup statement
        $stmt->close();
    }
} else {
    // Jika tidak ada ID yang diteruskan, alihkan ke halaman disposisi
    header("Location: read_disposisi.php"); // Ganti dengan halaman disposisi Anda
    exit;
}

// Tutup koneksi database
$conn->close();
?>
