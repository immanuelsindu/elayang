<?php

session_start(); // Mulai session
include('../db_connection.php');


// Periksa apakah user sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Jika belum login, redirect ke halaman login
    header("Location: ../login.php");
    exit;
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
