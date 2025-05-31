<?php
session_start();
// Cek apakah user sudah login & berperan sebagai anggota
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'anggota') {
    header("Location: ../login.php");
    exit;
}
$namaUser = $_SESSION['user']['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Anggota - TaskUKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

<!-- Sidebar & Header -->
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
        <div class="p-6 text-center border-b">
            <h1 class="text-2xl font-bold text-blue-600">TaskUKM</h1>
        </div>
        <nav class="p-4">
            <ul class="space-y-2">
                <li><a href="#" class="flex items-center p-2 bg-blue-600 text-white rounded"><span class="ml-2">Dashboard</span></a></li>
                <li><a href="#" class="flex items-center p-2 hover:bg-gray-100 rounded"><span class="ml-2">Tugas</span></a></li>
                <li><a href="#" class="flex items-center p-2 hover:bg-gray-100 rounded"><span class="ml-2">Jadwal Kegiatan</span></a></li>
                <li><a href="#" class="flex items-center p-2 hover:bg-gray-100 rounded"><span class="ml-2">Logout</span></a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Dashboard</h2>
            <div class="flex items-center gap-3">
                <span class="font-semibold"><?= htmlspecialchars($namaUser) ?></span>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($namaUser) ?>" class="w-10 h-10 rounded-full" alt="Avatar">
            </div>
        </div>

        <!-- Cards -->
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

        <!-- Tabel SOP / Tugas -->
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
                            <th class="p-2">Deadline</th>
                            <th class="p-2">Status</th>
                            <th class="p-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t">
                            <td class="p-2">1</td>
                            <td class="p-2">Menyusun proposal kegiatan</td>
                            <td class="p-2">2025-06-10</td>
                            <td class="p-2 text-green-600">Belum</td>
                            <td class="p-2 text-center"><a href="#" class="text-blue-600 hover:underline">Lihat</a></td>
                        </tr>
                        <!-- Tambahkan loop PHP untuk data tugas dari database di sini -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

</body>
</html>
