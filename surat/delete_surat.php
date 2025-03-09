<?php
// Koneksi ke database
session_start(); // Mulai session
include('../db_connection.php');

// Periksa apakah user sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Jika belum login, redirect ke halaman login
    header("Location: login.php");
    exit;
}

// Cek apakah parameter 'id' ada di URL
if (isset($_GET['id'])) {
    // Ambil ID surat yang akan dihapus
    $id = $_GET['id'];

    // Query untuk menghapus surat berdasarkan ID
    $sql = "DELETE FROM surat_masuk WHERE id = ?";

    // Persiapkan statement untuk mencegah SQL injection
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameter ID ke statement
        $stmt->bind_param("i", $id);

        // Eksekusi statement
        if ($stmt->execute()) {
            // Jika berhasil dihapus, tampilkan pesan sukses dan arahkan ke halaman surat
            echo "<script>alert('Surat berhasil dihapus.'); window.location.href='read_surat.php';</script>";
        } else {
            // Jika gagal, tampilkan pesan error
            echo "<script>alert('Terjadi kesalahan saat menghapus surat.'); window.location.href='read_surat.php';</script>";
        }

        // Tutup statement
        $stmt->close();
    } else {
        // Jika gagal menyiapkan statement
        echo "<script>alert('Terjadi kesalahan pada server.'); window.location.href='read_surat.php';</script>";
    }
} else {
    // Jika ID tidak ada di URL
    echo "<script>alert('ID surat tidak ditemukan.'); window.location.href='read_surat.php';</script>";
}

// Tutup koneksi database
$conn->close();
?>
