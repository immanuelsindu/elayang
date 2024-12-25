<?php
// Memulai session dan koneksi ke database
session_start();
include('../db_connection.php');

// Memeriksa apakah parameter ID ada di URL
if (isset($_GET['id'])) {
    // Mengambil ID dari parameter URL
    $id = $_GET['id'];

    // Membuat query SQL untuk menghapus data berdasarkan ID
    $sql = "DELETE FROM surat_edaran WHERE id = ?";

    // Menyiapkan statement untuk menghindari SQL Injection
    if ($stmt = $conn->prepare($sql)) {
        // Mengikat parameter ID ke query
        $stmt->bind_param("i", $id);

        // Menjalankan query untuk menghapus data
        if ($stmt->execute()) {
            // Jika berhasil, redirect ke halaman daftar surat edaran
            $_SESSION['message'] = "Data surat edaran berhasil dihapus.";
            $_SESSION['msg_type'] = "success";
            header("Location: read_surat_edaran.php");
        } else {
            // Jika gagal, menampilkan pesan error
            $_SESSION['message'] = "Gagal menghapus data.";
            $_SESSION['msg_type'] = "danger";
            header("Location: read_surat_edaran.php");
        }

        // Menutup statement
        $stmt->close();
    }
}

// Menutup koneksi database
$conn->close();
?>
