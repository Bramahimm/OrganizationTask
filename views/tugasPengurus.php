<?php
session_start();
include '../includes/database.php'; // Koneksi ke database

// Pastikan user telah login dan memiliki role "pengurus"
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pengurus') {
    header("Location: ../index.php");
    exit;
}

$namaUser = $_SESSION['user']['nama'];
$idUser = $_SESSION['user']['id'];

// Handle CRUD Operations
$message = '';
$messageType = '';

// CREATE - Tambah tugas
if (isset($_POST['tambah_tugas'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $query = "INSERT INTO tugas (judul, deskripsi, deadline, status, idUser) VALUES ('$judul', '$deskripsi', '$deadline', '$status', '$idUser')";
    
    if (mysqli_query($conn, $query)) {
        $message = "Tugas berhasil ditambahkan!";
        $messageType = "success";
    } else {
        $message = "Error: " . mysqli_error($conn);
        $messageType = "error";
    }
}

// UPDATE - Edit tugas
if (isset($_POST['edit_tugas'])) {
    $idTugas = mysqli_real_escape_string($conn, $_POST['idTugas']);
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $query = "UPDATE tugas SET judul='$judul', deskripsi='$deskripsi', deadline='$deadline', status='$status' WHERE idTugas='$idTugas'";
    
    if (mysqli_query($conn, $query)) {
        $message = "Tugas berhasil diupdate!";
        $messageType = "success";
    } else {
        $message = "Error: " . mysqli_error($conn);
        $messageType = "error";
    }
}

// DELETE - Hapus tugas
if (isset($_GET['hapus'])) {
    $idTugas = mysqli_real_escape_string($conn, $_GET['hapus']);
    $query = "DELETE FROM tugas WHERE idTugas='$idTugas' ";
    
    if (mysqli_query($conn, $query)) {
        $message = "Tugas berhasil dihapus!";
        $messageType = "success";
    } else {
        $message = "Error: " . mysqli_error($conn);
        $messageType = "error";
    }
}

// READ - Ambil data tugas
$query = "SELECT * FROM tugas ORDER BY deadline ASC";
$result = mysqli_query($conn, $query);

// Untuk edit - ambil data tugas yang akan diedit
$editData = null;
if (isset($_GET['edit'])) {
    $idEdit = mysqli_real_escape_string($conn, $_GET['edit']);
    $editQuery = "SELECT * FROM tugas WHERE idTugas='$idEdit'";
    $editResult = mysqli_query($conn, $editQuery);
    $editData = mysqli_fetch_assoc($editResult);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orgenius - Manajemen Tugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .modal {
            display: none;
        }
        .modal.active {
            display: flex;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-[#ffe4c4] shadow-md px-6 py-2 flex justify-between items-center fixed w-full top-0 left-0 z-10">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <img src="../assets/img/orgenius.png" class="w-12 h-auto" alt="Orgenius">
            <h1 class="text-2xl font-bold text-blue-600">Orgenius</h1>
        </div>

        <!-- Profil User -->
        <div class="flex items-center gap-3">
            <span class="font-semibold"><?= htmlspecialchars($namaUser) ?></span>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($namaUser) ?>" class="w-10 h-10 rounded-full" alt="Avatar">
        </div>
    </nav>

    <!-- Layout utama -->
    <div class="flex pt-16">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md h-screen fixed left-0 top-16">
            <nav class="p-6">
                <ul class="space-y-3">
                    <li><a href="dashboardPengurus.php" class="flex items-center p-3 hover:bg-gray-100 rounded-md"><i class="fas fa-home w-5"></i><span class="ml-3">Dashboard</span></a></li>
                    <li><a href="tugasPengurus.php" class="flex items-center p-3 bg-blue-600 text-white rounded-md hover:bg-blue-700"><i class="fas fa-tasks w-5"></i><span class="ml-3">Tugas</span></a></li>
                    <li><a href="jadwalKegiatanPengurus.php" class="flex items-center p-3 hover:bg-gray-100 rounded-md"><i class="fas fa-calendar w-5"></i><span class="ml-3">Jadwal Kegiatan</span></a></li>
                    <li><a href="../process/logout.php" class="flex items-center p-3 hover:bg-red-100 text-red-700 rounded-md"><i class="fas fa-sign-out-alt w-5"></i><span class="ml-3">Logout</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Konten Utama -->
        <main class="flex-1 p-6 ml-64">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Tugas</h2>
                <button onclick="openModal('modalTambah')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition duration-200">
                    <i class="fas fa-plus"></i>
                    Tambah Tugas
                </button>
            </div>

            <!-- Pesan Notifikasi -->
            <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-lg <?= $messageType === 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' ?>">
                <i class="fas <?= $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> mr-2"></i>
                <?= htmlspecialchars($message) ?>
            </div>
            <?php endif; ?>

            <!-- Tabel Tugas -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Tugas Anda</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Tugas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $no = 1;
                            if (mysqli_num_rows($result) > 0) {
                                while ($data = mysqli_fetch_assoc($result)) {
                                    $statusColor = '';
                                    $statusText = '';
                                    switch($data['status']) {
                                        case 'belum':
                                            $statusColor = 'bg-red-100 text-red-800';
                                            $statusText = 'Belum Mulai';
                                            break;
                                        case 'proses':
                                            $statusColor = 'bg-yellow-100 text-yellow-800';
                                            $statusText = 'Dalam Proses';
                                            break;
                                        case 'selesai':
                                            $statusColor = 'bg-green-100 text-green-800';
                                            $statusText = 'Selesai';
                                            break;
                                    }
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $no++ ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($data['judul']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate"><?= htmlspecialchars($data['deskripsi']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= date('d/m/Y', strtotime($data['deadline'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusColor ?>">
                                        <?= $statusText ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="?edit=<?= $data['idTugas'] ?>" class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded-md transition duration-200">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="?hapus=<?= $data['idTugas'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded-md transition duration-200">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                            ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2"></i>
                                    <p>Belum ada tugas yang dibuat</p>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Tambah Tugas -->
    <div id="modalTambah" class="modal fixed inset-0 bg-gray-600 bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Tugas Baru</h3>
                <button onclick="closeModal('modalTambah')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" action="">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Tugas</label>
                        <input type="text" name="judul" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                        <input type="date" name="deadline" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="belum">Belum Mulai</option>
                            <option value="proses">Dalam Proses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('modalTambah')" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300 transition duration-200">Batal</button>
                    <button type="submit" name="tambah_tugas" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Tugas -->
    <?php if ($editData): ?>
    <div id="modalEdit" class="modal active fixed inset-0 bg-gray-600 bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit Tugas</h3>
                <a href="tugasPengurus.php" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="idTugas" value="<?= $editData['idTugas'] ?>">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Tugas</label>
                        <input type="text" name="judul" value="<?= htmlspecialchars($editData['judul']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($editData['deskripsi']) ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                        <input type="date" name="deadline" value="<?= $editData['deadline'] ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="belum" <?= $editData['status'] === 'belum' ? 'selected' : '' ?>>Belum Mulai</option>
                            <option value="proses" <?= $editData['status'] === 'proses' ? 'selected' : '' ?>>Dalam Proses</option>
                            <option value="selesai" <?= $editData['status'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="tugasPengurus.php" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300 transition duration-200">Batal</a>
                    <button type="submit" name="edit_tugas" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">Update</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.getElementsByClassName('modal');
            for (let i = 0; i < modals.length; i++) {
                if (event.target === modals[i]) {
                    modals[i].classList.remove('active');
                }
            }
        }

        // Auto hide success message after 5 seconds
        <?php if ($message && $messageType === 'success'): ?>
        setTimeout(function() {
            const alert = document.querySelector('.bg-green-100');
            if (alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
        <?php endif; ?>
    </script>
</body>
</html>