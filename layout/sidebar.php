<?php
require_once __DIR__ . '/../includes/helpers.php';
$role = $_SESSION['user']['role'] ?? 'anggota';
?>

<aside class="w-64 bg-white shadow-md h-screen fixed left-0 top-16">
  <nav class="p-6">
    <ul class="space-y-3">

      <li>
        <a href="index.php?route=dashboard"
          class="flex items-center p-3 rounded-md <?= activeClass('dashboard') ?>">
          <i class="fas fa-home w-5"></i>
          <span class="ml-3">Dashboard</span>
        </a>
      </li>

      <li>
        <a href="index.php?route=tugas"
          class="flex items-center p-3 rounded-md <?= activeClass('tugas') ?>">
          <i class="fas fa-tasks w-5"></i>
          <span class="ml-3">Tugas</span>
        </a>
      </li>

      <li>
        <a href="index.php?route=<?= $role === 'pengurus' ? 'jadwal-kegiatan-pengurus' : 'jadwal-kegiatan' ?>"
          class="flex items-center p-3 rounded-md <?= activeClass($role === 'pengurus' ? 'jadwal-kegiatan-pengurus' : 'jadwal-kegiatan') ?>">
          <i class="fas fa-calendar w-5"></i>
          <span class="ml-3">Jadwal Kegiatan</span>
        </a>
      </li>


      <li class="pt-4 border-t">
        <a href="../process/logout.php"
          class="flex items-center p-3 hover:bg-red-100 text-red-700 rounded-md">
          <i class="fas fa-sign-out-alt w-5"></i>
          <span class="ml-3">Logout</span>
        </a>
      </li>

    </ul>
  </nav>
</aside>