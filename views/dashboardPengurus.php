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
<meta charset="UTF-8" />
<title>Dashboard Pengurus - TaskUKM</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
<style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100">
<!-- Sidebar & Header -->
<div class="flex min-h-screen">
  <aside class="w-64 bg-white shadow-md">
    <div class="p-6 text-center border-b">
      <h1 class="text-2xl font-bold text-blue-600">TaskUKM - Pengurus</h1>
    </div>
    <nav class="p-4">
      <ul class="space-y-2">
        <li><a href="#" class="flex items-center p-2 bg-blue-600 text-white rounded"><span class="ml-2">Dashboard</span></a></li>
        <li><a href="#" class="flex items-center p-2 hover:bg-gray-100 rounded"><span class="ml-2">Manajemen Tugas</span></a></li>
        <li><a href="#" class="flex items-center p-2 hover:bg-gray-100 rounded"><span class="ml-2">Laporan</span></a></li>
        <li><a href="../process/logout.php" class="flex items-center p-2 hover:bg-gray-100 rounded text-red-600"><span class="ml-2">Logout</span></a></li>
      </ul>
    </nav>
  </aside>
  <main class="flex-1 p-6">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Dashboard Pengurus</h2>
      <div class="flex items-center gap-3">
        <span class="font-semibold"><?= htmlspecialchars($namaUser) ?></span>
        <img src="https://ui-avatars.com/api/?name=<?= urlencode($namaUser) ?>" class="w-10 h-10 rounded-full" alt="Avatar" />
      </div>
    </div>
    <!-- Konten Dashboard Pengurus -->
    <p>Selamat datang, <?= htmlspecialchars($namaUser) ?>! Ini adalah halaman dashboard untuk pengurus.</p>
  </main>
</div>
</body>
</html>
