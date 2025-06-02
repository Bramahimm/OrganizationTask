<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pengurus') {
  header("Location: ../index.php");
  exit;
}
$namaUser = $_SESSION['user']['nama'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Dashboard Pengurus - TaskUKM</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="icon" type="image/x-png" href="assets/img/orgenius.png">

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-50">

  <!-- Navbar -->
  <nav class="bg-[#ffe4c4] shadow-md px-6 py-2 flex justify-between items-center fixed w-full top-0 left-0 z-10">
    <!-- Logo -->
    <div class="flex items-center space-x-3">
      <img src="../assets/img/orgenius.png" class="w-12 h-auto" alt="Orgenius">
      <h1 class="text-2xl font-bold text-black">Orgenius</h1>
    </div>

    <!-- Profil User -->
    <div class="flex items-center gap-3">
      <span class="font-semibold"><?= htmlspecialchars($namaUser) ?></span>
      <img src="https://ui-avatars.com/api/?name=<?= urlencode($namaUser) ?>" class="w-10 h-10 rounded-full"
        alt="Avatar">
    </div>
  </nav>

  <!-- Layout utama -->
  <div class="flex pt-16">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md h-screen fixed left-0 top-16">
      <nav class="p-6">
        <ul class="space-y-3">
          <li><a href="#" class="flex items-center p-3 hover:bg-blue-700 bg-blue-600 rounded-md"><i
                class="fas fa-home w-5"></i><span class="ml-3">Dashboard</span></a></li>
          <li><a href="#" class="flex items-center p-3 hover:bg-gray-100 rounded-md"><i
                class="fas fa-tasks w-5"></i><span class="ml-3">Tugas</span></a></li>
          <li><a href="#" class="flex items-center p-3 text-black rounded-md hover:bg-gray-100"><i
                class="fas fa-calendar w-5"></i><span class="ml-3">Jadwal Kegiatan</span></a></li>
          <li><a href="../process/logout.php" class="flex items-center p-3 hover:bg-red-100 text-red-700 rounded-md"><i
                class="fas fa-sign-out-alt w-5"></i><span class="ml-3">Logout</span></a></li>
        </ul>
      </nav>
    </aside>

    <!-- Konten Utama -->
    <main class="flex-1 p-6 ml-64">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Dashboard Pengurus</h2>
      </div>

      <!-- Cards Statistik -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-lg shadow text-center">
          <p class="text-2xl font-bold text-blue-600">0</p>
          <p class="text-sm text-gray-500">Tugas Aktif</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow text-center">
          <p class="text-2xl font-bold text-blue-600">0</p>
          <p class="text-sm text-gray-500">Kegiatan Diikuti</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow text-center">
          <p class="text-2xl font-bold text-blue-600">4</p>
          <p class="text-sm text-gray-500">Semester</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow text-center">
          <p class="text-2xl font-bold text-blue-600">Aktif</p>
          <p class="text-sm text-gray-500">Status</p>
        </div>
      </div>

      <!-- Tabel Tugas -->
      <?php
      // Koneksi ke database
      include '../includes/database.php';

      // Ambil ID pengguna dari session (pastikan pengguna sudah login)
      $idUser = $_SESSION['user']['id'];

      // Query untuk mengambil tugas berdasarkan idUser
      $query = "SELECT * FROM tugas WHERE idUser = '$idUser' ORDER BY deadline ASC";
      $result = mysqli_query($conn, $query);
      ?>

      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Daftar Tugas</h3>
          <input type="text" class="border px-3 py-1 rounded text-sm" placeholder="Pencarian">
        </div>
        <div class="overflow-x-auto">
          <table class="w-full table-auto text-sm">
            <thead class="bg-gray-100 text-left">
              <tr>
                <th class="p-2">No</th>
                <th class="p-2">Judul Tugas</th>
                <th class="p-2">Deskripsi</th>
                <th class="p-2">Deadline</th>
                <th class="p-2">Status</th>
                <th class="p-2 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              while ($data = mysqli_fetch_assoc($result)) {
                ?>
                <tr class="border-t hover:bg-gray-50">
                  <td class="p-2"><?= $no++; ?></td>
                  <td class="p-2 font-semibold"><?= htmlspecialchars($data['judul']); ?></td>
                  <td class="p-2"><?= htmlspecialchars($data['deskripsi']); ?></td>
                  <td class="p-2"><?= date('d-m-Y', strtotime($data['deadline'])); ?></td>
                  <td class="p-2 text-<?= $data['status'] === 'Selesai' ? 'green' : 'red'; ?>-600">
                    <?= ucfirst($data['status']); ?>
                  </td>
                  <td class="p-2 text-center">
                    <a href="#" class="text-blue-600 hover:underline">Detail</a>
                  </td>
                </tr>
                <?php
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>


    </main>
  </div>

</body>

</html>