<?php
session_start(); // Mulai session

// Periksa apakah user sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Include koneksi ke database
include('../db_connection.php');
// Direktori tempat file diunggah
$uploadDir = '../uploads/';

// Periksa apakah ID diberikan melalui URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mendapatkan path file berdasarkan ID
    $sql = "SELECT upload_surat FROM disposisi WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($fileName);
    $stmt->fetch();
    $stmt->close();

    // Periksa apakah file path ditemukan
    $filePath = $uploadDir . $fileName;
    if ($fileName && file_exists($filePath)) {
        // Mengatur header untuk menampilkan file PDF
        header("Content-Type: application/pdf");
        header("Content-Disposition: inline; filename='" . basename($filePath) . "'");
        readfile($filePath);
        exit;
    } else {
        echo "File tidak ditemukan.";
    }
} else {
    echo "ID surat tidak ditemukan.";
}

?>
