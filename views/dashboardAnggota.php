<?php
session_start();
include '../includes/database.php';

$title = 'Orgenius - Anggota';

$namaUser = $_SESSION['user']['nama'];
$idUser = $_SESSION['user']['id'];

// untuk ambil data tugas berdasarkan idUser dari database
$query = "SELECT * FROM tugas WHERE idUser = '$idUser' ORDER BY deadline ASC";
$result = mysqli_query($conn, $query);

?>
<?php include '../layout/header.php';?>
<?php include '../layout/navbar.php';?>
<?php include '../layout/sidebar.php';?>
<!-- Konten Utama -->
<main class="flex-1 p-6 ml-64">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Dashboard Anggota</h2>
  </div>

  <!-- Daftar Tugas -->
  <div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold mb-4">Daftar Tugas</h3>
    <div class="overflow-x-auto">
      <table class="w-full table-auto text-sm">
        <thead class="bg-gray-100 text-left">
          <tr>
            <th class="p-2">No</th>
            <th class="p-2">Judul Tugas</th>
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

