<?php
if (!isset($namaUser) && isset($_SESSION['user']['nama'])) {
  $namaUser = $_SESSION['user']['nama'];
}
?>

<nav class="bg-[#ffe4c4] shadow-md px-6 py-3 flex justify-between items-center fixed w-full top-0 left-0 z-10">
  <!-- Logo kiri -->
  <div class="flex items-center gap-3">
    <img src="../assets/img/orgenius.png" class="w-10 h-10" alt="Logo">
    <h1 class="text-xl font-bold text-blue-700">Orgenius</h1>
  </div>

  <!-- Profil kanan -->
  <div class="flex items-center gap-3">
    <span class="font-medium text-gray-800">
      <?= htmlspecialchars($namaUser ?? 'Pengguna') ?>
    </span>
    <img src="https://ui-avatars.com/api/?name=<?= urlencode($namaUser ?? 'U') ?>" alt="Avatar"
      class="w-10 h-10 rounded-full border border-blue-200 shadow-sm">
  </div>
</nav>