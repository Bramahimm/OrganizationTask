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

// CREATE - Tambah kegiatan
if (isset($_POST['tambah_kegiatan'])) {
    $namaKegiatan = mysqli_real_escape_string($conn, $_POST['namaKegiatan']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    
    $query = "INSERT INTO kegiatan (namaKegiatan, deskripsi, tanggal, lokasi, idPembuat) VALUES ('$namaKegiatan', '$deskripsi', '$tanggal', '$lokasi', '$idUser')";
    
    if (mysqli_query($conn, $query)) {
        $message = "Kegiatan berhasil ditambahkan!";
        $messageType = "success";
    } else {
        $message = "Error: " . mysqli_error($conn);
        $messageType = "error";
    }
}

// UPDATE - Edit kegiatan
if (isset($_POST['edit_kegiatan'])) {
    $idKegiatan = mysqli_real_escape_string($conn, $_POST['idKegiatan']);
    $namaKegiatan = mysqli_real_escape_string($conn, $_POST['namaKegiatan']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    
    $query = "UPDATE kegiatan SET namaKegiatan='$namaKegiatan', deskripsi='$deskripsi', tanggal='$tanggal', lokasi='$lokasi' WHERE idKegiatan='$idKegiatan'";
    
    if (mysqli_query($conn, $query)) {
        $message = "Kegiatan berhasil diupdate!";
        $messageType = "success";
    } else {
        $message = "Error: " . mysqli_error($conn);
        $messageType = "error";
    }
}

// DELETE - Hapus kegiatan
if (isset($_GET['hapus'])) {
    $idKegiatan = mysqli_real_escape_string($conn, $_GET['hapus']);
    $query = "DELETE FROM kegiatan WHERE idKegiatan='$idKegiatan'";
    
    if (mysqli_query($conn, $query)) {
        $message = "Kegiatan berhasil dihapus!";
        $messageType = "success";
    } else {
        $message = "Error: " . mysqli_error($conn);
        $messageType = "error";
    }
}

// READ - Ambil data kegiatan
$query = "SELECT * FROM kegiatan ORDER BY tanggal ASC";
$result = mysqli_query($conn, $query);

// Untuk edit - ambil data kegiatan yang akan diedit
$editData = null;
if (isset($_GET['edit'])) {
    $idEdit = mysqli_real_escape_string($conn, $_GET['edit']);
    $editQuery = "SELECT * FROM kegiatan WHERE idKegiatan='$idEdit'";
    $editResult = mysqli_query($conn, $editQuery);
    $editData = mysqli_fetch_assoc($editResult);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orgenius - Jadwal Kegiatan</title>
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
                    <li><a href="tugasPengurus.php" class="flex items-center p-3 hover:bg-gray-100 rounded-md"><i class="fas fa-tasks w-5"></i><span class="ml-3">Tugas</span></a></li>
                    <li><a href="jadwalKegiatanPengurus.php" class="flex items-center p-3 bg-blue-600 text-white rounded-md hover:bg-blue-700"><i class="fas fa-calendar w-5"></i><span class="ml-3">Jadwal Kegiatan</span></a></li>
                    <li><a href="../process/logout.php" class="flex items-center p-3 hover:bg-red-100 text-red-700 rounded-md"><i class="fas fa-sign-out-alt w-5"></i><span class="ml-3">Logout</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Konten Utama -->
        <main class="flex-1 p-6 ml-64">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Jadwal Kegiatan</h2>
                <button onclick="openModal('modalTambah')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition duration-200">
                    <i class="fas fa-plus"></i>
                    Tambah Kegiatan
                </button>
            </div>

            <!-- Pesan Notifikasi -->
            <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-lg <?= $messageType === 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' ?>">
                <i class="fas <?= $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> mr-2"></i>
                <?= htmlspecialchars($message) ?>
            </div>
            <?php endif; ?>

            <!-- Card Kegiatan -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($data = mysqli_fetch_assoc($result)) {
                        $tanggalKegiatan = new DateTime($data['tanggal']);
                        $tanggalSekarang = new DateTime();
                        $selisihHari = $tanggalSekarang->diff($tanggalKegiatan)->days;
                        $statusWarna = '';
                        $statusText = '';
                        
                        if ($tanggalKegiatan < $tanggalSekarang) {
                            $statusWarna = 'bg-gray-100 border-gray-300';
                            $statusText = 'Sudah Berlalu';
                        } elseif ($selisihHari <= 3) {
                            $statusWarna = 'bg-red-50 border-red-200';
                            $statusText = 'Segera Dimulai';
                        } elseif ($selisihHari <= 7) {
                            $statusWarna = 'bg-yellow-50 border-yellow-200';
                            $statusText = 'Minggu Ini';
                        } else {
                            $statusWarna = 'bg-blue-50 border-blue-200';
                            $statusText = 'Akan Datang';
                        }
                ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 <?= $statusWarna ?>">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2"><?= htmlspecialchars($data['namaKegiatan']) ?></h3>
                                <span class="inline-block px-2 py-1 text-xs font-medium rounded-full <?= 
                                    $tanggalKegiatan < $tanggalSekarang ? 'bg-gray-100 text-gray-600' :
                                    ($selisihHari <= 3 ? 'bg-red-100 text-red-700' :
                                    ($selisihHari <= 7 ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700'))
                                ?>">
                                    <?= $statusText ?>
                                </span>
                            </div>
                            <div class="flex space-x-1">
                                <a href="?edit=<?= $data['idKegiatan'] ?>" class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-100 rounded-full transition duration-200">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <a href="?hapus=<?= $data['idKegiatan'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')" class="text-red-600 hover:text-red-800 p-2 hover:bg-red-100 rounded-full transition duration-200">
                                    <i class="fas fa-trash text-sm"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-calendar-alt w-5 mr-3"></i>
                                <span class="text-sm"><?= date('d F Y', strtotime($data['tanggal'])) ?></span>
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt w-5 mr-3"></i>
                                <span class="text-sm"><?= htmlspecialchars($data['lokasi']) ?></span>
                            </div>
                            
                            <?php if (!empty($data['deskripsi'])): ?>
                            <div class="flex items-start text-gray-600">
                                <i class="fas fa-align-left w-5 mr-3 mt-1"></i>
                                <p class="text-sm leading-relaxed"><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                ?>
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <i class="fas fa-calendar-times text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Kegiatan</h3>
                        <p class="text-gray-500 mb-6">Mulai tambahkan kegiatan pertama Anda</p>
                        <button onclick="openModal('modalTambah')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Kegiatan
                        </button>
                    </div>
                </div>
                <?php } ?>
            </div>
        </main>
    </div>

    <!-- Modal Tambah Kegiatan -->
    <div id="modalTambah" class="modal fixed inset-0 bg-gray-600 bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Kegiatan Baru</h3>
                <button onclick="closeModal('modalTambah')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" action="">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan</label>
                        <input type="text" name="namaKegiatan" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <input type="text" name="lokasi" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('modalTambah')" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300 transition duration-200">Batal</button>
                    <button type="submit" name="tambah_kegiatan" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kegiatan -->
    <?php if ($editData): ?>
    <div id="modalEdit" class="modal active fixed inset-0 bg-gray-600 bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit Kegiatan</h3>
                <a href="JadwalKegiatanPengurus.php" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="idKegiatan" value="<?= $editData['idKegiatan'] ?>">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan</label>
                        <input type="text" name="namaKegiatan" value="<?= htmlspecialchars($editData['namaKegiatan']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($editData['deskripsi']) ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="<?= $editData['tanggal'] ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <input type="text" name="lokasi" value="<?= htmlspecialchars($editData['lokasi']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="JadwalKegiatanPengurus.php" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300 transition duration-200">Batal</a>
                    <button type="submit" name="edit_kegiatan" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">Update</button>
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

        // Set minimum date to today for date input
        document.addEventListener('DOMContentLoaded', function() {
            const dateInputs = document.querySelectorAll('input[type="date"]');
            const today = new Date().toISOString().split('T')[0];
            dateInputs.forEach(input => {
                if (!input.value) {
                    input.min = today;
                }
            });
        });
    </script>
</body>
</html>