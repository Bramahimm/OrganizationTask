<?php
require_once __DIR__ . '/../includes/helpers.php';
$role = $_SESSION['user']['role'] ?? 'anggota';
$pendingCount = ($role === 'pengurus') ? getPendingRequestCount($conn, $_SESSION['user']['id']) : 0;
?>
<aside class="w-64 bg-white shadow-md h-screen fixed left-0 top-16">
  <nav class="p-6">
    <ul class="space-y-3">
      <li>
        <a href="<?= $role === 'pengurus' ? 'index.php?route=dashboard' : 'index.php?route=dashboard' ?>" class="flex items-center p-3 rounded-md hover:bg-gray-100">
          <i class="fas fa-home w-5"></i>
          <span class="ml-3">Dashboard</span>
        </a>
      </li>
      <li>
        <a href="index.php?route=tugas" class="flex items-center p-3 rounded-md hover:bg-gray-100">
          <i class="fas fa-tasks w-5"></i>
          <span class="ml-3">Tugas</span>
        </a>
      </li>
      <li>
        <a href="index.php?route=jadwal-kegiatan-<?= $role ?>" class="flex items-center p-3 rounded-md hover:bg-gray-100">
          <i class="fas fa-calendar w-5"></i>
          <span class="ml-3">Jadwal Kegiatan</span>
        </a>
      </li>
      <?php if ($role === 'pengurus'): ?>
        <li>
          <a href="index.php?route=verifikasi-request" class="flex items-center p-3 rounded-md hover:bg-gray-100">
            <i class="fas fa-user-clock w-5"></i>
            <span class="ml-3">Permintaan
              <?php if ($pendingCount > 0): ?>
                <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                  <?= $pendingCount ?>
                </span>
              <?php endif; ?>
            </span>
          </a>
        </li>
      <?php endif; ?>
      <?php if ($role === 'anggota'): ?>
        <li>
          <a href="index.php?route=organisasi"
            class="flex items-center p-3 rounded-md <?= activeClass('organisasi') ?>">
            <i class="fas fa-building w-5"></i>
            <span class="ml-3">Organisasi</span>
          </a>
        </li>
      <?php endif; ?>

      <li class="pt-4 border-t">
        <a href="process/logout.php" class="flex items-center p-3 hover:bg-red-100 text-red-700 rounded-md">
          <i class="fas fa-sign-out-alt w-5"></i>
          <span class="ml-3">Logout</span>
        </a>
      </li>
    </ul>
  </nav>
</aside>