<?php
session_start(); // Mulai session

// Periksa apakah user sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Jika belum login, redirect ke halaman login
    header("Location: login.php");
    exit;
}
?>

<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Admin Dashboard</title>
  <!-- Add Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div class="d-flex align-items-center">
        <img alt="School Logo" class="me-3" height="50"
          src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi7rtoRw8nA-XuqtQ5Wfpyy3xMh5g-Vv4iYZozeZQ_eUHpmA4nLGHHEJ3xQbIAFNwxeVzXA0Zys5A4Tsw74dPRXD7cyQ5PayEuMZFsNj7Kgpd5tuHkUhKV_iP1JiMLgTAYAP9y3rfuUdC0/s1600/Logo+SMK.jpg"
          width="50" />
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
        <img alt="User Avatar" class="rounded-circle me-2" height="50"
          src="https://static.vecteezy.com/system/resources/previews/000/439/863/original/vector-users-icon.jpg"
          width="50" />
        <span>
          Budi Kristiono
        </span>
      </div>
    </div>
    <div class="bg-white p-4 border mb-4">
      <h2 class="h4 fw-bold">
        Selamat Datang Administrator
      </h2>
      <p>
        Anda login sebagai Admin. Anda memiliki akses penuh terhadap sistem
      </p>
    </div>
    <div class="row row-cols-2 g-4 bg-success p-4">
      <a href="surat/read_surat.php" class="col">
        <div class="bg-primary p-4 d-flex align-items-center rounded">
          <img alt="Surat Masuk Icon" class="me-2" height="50"
            src="https://tse3.mm.bing.net/th?id=OIP.YU1vRLM2Q90Yfl32SN7kDQHaHa&pid=Api&P=0&h=180" width="50" />
          <span class="text-white">
            SURAT MASUK
          </span>
        </div>
      </a>

      <a href="read_disposisi.html" class="col">
        <div class="bg-warning p-4 d-flex align-items-center rounded">
          <img alt="Disposisi Icon" class="me-2" height="50"
            src="https://e7.pngegg.com/pngimages/176/67/png-clipart-person-logo-people-travel-text-rectangle.png"
            width="50" />
          <span class="text-dark">
            DISPOSISI
          </span>
        </div>
      </a>

      <a href="read_surat_edaran.html" class="col">
        <div class="bg-warning p-4 d-flex align-items-center rounded">
          <img alt="Surat Edaran Icon" class="me-2" height="50"
            src="https://png.pngtree.com/png-vector/20220624/ourlarge/pngtree-mail-logo-fast-png-image_5360233.png"
            width="50" />
          <span class="text-dark">
            SURAT EDARAN
          </span>
        </div>
      </a>

      <a href="download_surat.html" class="col">
        <div class="bg-primary p-4 d-flex align-items-center rounded">
          <img alt="Download Surat Icon" class="me-2" height="50"
            src="https://w7.pngwing.com/pngs/309/298/png-transparent-paper-letterhead-design-angle-triangle-logo.png"
            width="50" />
          <span class="text-white">
            DOWNLOAD SURAT
          </span>
        </div>
      </a>

      <a href="read_surat_keluar.html" class="col-12">
        <div class="bg-danger p-4 d-flex align-items-center rounded">
          <img alt="Surat Keluar Icon" class="me-2" height="50"
            src="https://bapasjaksel.com/smile/wp-content/uploads/2021/03/Surat-Keluar-1-1024x1024.png" width="50" />
          <span class="text-white">
            SURAT KELUAR
          </span>
        </div>
      </a>
    </div>
  </div>

  <!-- Add Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>