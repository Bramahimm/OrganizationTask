<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pengurus') {
  header("Location: ../index.php");
  exit;
}
$title = 'Orgenius - Pengurus';
$namaUser = $_SESSION['user']['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<?php include '../layout/header.php'?>
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
                    <li><a href="dashboardPengurus.php" class="flex items-center p-3 bg-blue-600 text-white rounded-md hover:bg-blue-700"><i class="fas fa-home w-5"></i><span class="ml-3">Dashboard</span></a></li>
                    <li><a href="tugasPengurus.php" class="flex items-center p-3 hover:bg-gray-100 rounded-md"><i class="fas fa-tasks w-5"></i><span class="ml-3">Tugas</span></a></li>
                    <li><a href="jadwalKegiatanPengurus.php" class="flex items-center p-3 hover:bg-gray-100 rounded-md"><i class="fas fa-calendar w-5"></i><span class="ml-3">Jadwal Kegiatan</span></a></li>
                    <li><a href="../process/logout.php" class="flex items-center p-3 hover:bg-red-100 text-red-700 rounded-md"><i class="fas fa-sign-out-alt w-5"></i><span class="ml-3">Logout</span></a></li>
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
      $query = "SELECT * FROM tugas ORDER BY deadline ASC";
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
                    <button onclick="showTaskDetail(<?= htmlspecialchars(json_encode($data)) ?>)" 
                            class="text-blue-600 hover:underline cursor-pointer">
                      Detail
                    </button>
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

  <!-- Modal Detail Tugas -->
  <div id="taskModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Detail Tugas</h2>
        
        <div class="space-y-4">
          <div class="border-b pb-3">
            <label class="block text-sm font-semibold text-gray-600 mb-1">ID Tugas</label>
            <p id="modal-id" class="text-gray-800"></p>
          </div>
          
          <div class="border-b pb-3">
            <label class="block text-sm font-semibold text-gray-600 mb-1">Judul Tugas</label>
            <p id="modal-judul" class="text-gray-800 font-semibold"></p>
          </div>
          
          <div class="border-b pb-3">
            <label class="block text-sm font-semibold text-gray-600 mb-1">Deskripsi</label>
            <p id="modal-deskripsi" class="text-gray-800"></p>
          </div>
          
          <div class="border-b pb-3">
            <label class="block text-sm font-semibold text-gray-600 mb-1">Deadline</label>
            <p id="modal-deadline" class="text-gray-800"></p>
          </div>
          
          <div class="border-b pb-3">
            <label class="block text-sm font-semibold text-gray-600 mb-1">Status</label>
            <p id="modal-status" class="font-semibold"></p>
          </div>
          
          <div class="border-b pb-3">
            <label class="block text-sm font-semibold text-gray-600 mb-1">ID User</label>
            <p id="modal-iduser" class="text-gray-800"></p>
          </div>
        </div>
        
        <div class="mt-6 flex justify-end">
          <button onclick="closeModal()" 
                  class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function showTaskDetail(taskData) {
      // Mengisi data ke modal
      document.getElementById('modal-id').textContent = taskData.idTugas || '-';
      document.getElementById('modal-judul').textContent = taskData.judul || '-';
      document.getElementById('modal-deskripsi').textContent = taskData.deskripsi || '-';
      document.getElementById('modal-iduser').textContent = taskData.idUser || '-';
      
      // Format tanggal
      if (taskData.deadline) {
        const date = new Date(taskData.deadline);
        const formattedDate = date.toLocaleDateString('id-ID', {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric'
        });
        document.getElementById('modal-deadline').textContent = formattedDate;
      } else {
        document.getElementById('modal-deadline').textContent = '-';
      }
      
      // Status dengan warna
      const statusElement = document.getElementById('modal-status');
      statusElement.textContent = taskData.status ? taskData.status.charAt(0).toUpperCase() + taskData.status.slice(1) : '-';
      
      if (taskData.status === 'Selesai') {
        statusElement.className = 'font-semibold text-green-600';
      } else {
        statusElement.className = 'font-semibold text-red-600';
      }
      
      // Tampilkan modal
      document.getElementById('taskModal').style.display = 'block';
    }

    function closeModal() {
      document.getElementById('taskModal').style.display = 'none';
    }

    // Tutup modal ketika klik di luar modal
    window.onclick = function(event) {
      const modal = document.getElementById('taskModal');
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    }

    // Tutup modal dengan tombol ESC
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closeModal();
      }
    });
  </script>

</body>

</html>