<?php
// Ambil urutan sort dari parameter URL
$order = ($_GET['sort'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';
$result = Kegiatan::getByUser($conn, $idUser, $order);
?>

<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../layout/header.php'; ?>

<body class="bg-gray-100">
    <?php include __DIR__ . '/../layout/navbar.php'; ?>
    <div class="flex pt-16">
        <?php include __DIR__ . '/../layout/sidebar.php'; ?>

        <main class="flex-1 p-6 ml-64">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Jadwal Kegiatan</h2>
                <button onclick="openModal('modalTambah')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition duration-200">
                    <i class="fas fa-plus"></i> Tambah Kegiatan
                </button>
            </div>

            <?php if (!empty($message)): ?>
                <div class="mb-6 p-4 rounded-lg <?= $messageType === 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' ?>">
                    <i class="fas <?= $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> mr-2"></i>
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="get" class="mb-6 flex justify-end">
                <label for="sort" class="mr-2 text-sm text-gray-600 mt-2">Urutkan:</label>
                <select name="sort" id="sort" onchange="this.form.submit()"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 text-sm">
                    <option value="asc" <?= ($_GET['sort'] ?? '') === 'asc' ? 'selected' : '' ?>>Terdekat</option>
                    <option value="desc" <?= ($_GET['sort'] ?? '') === 'desc' ? 'selected' : '' ?>>Terjauh</option>
                </select>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($data = mysqli_fetch_assoc($result)) {
                        $tanggalKegiatan = new DateTime($data['tanggal']);
                        $sekarang = new DateTime();
                        $selisihHari = (int)$sekarang->diff($tanggalKegiatan)->format('%r%a');

                        $statusText = $statusColor = '';
                        if ($selisihHari < 0) {
                            $statusText = 'Sudah Berlalu';
                            $statusColor = 'bg-gray-100 text-gray-600 border-gray-300';
                        } elseif ($selisihHari <= 3) {
                            $statusText = 'Segera Dimulai';
                            $statusColor = 'bg-red-100 text-red-700 border-red-200';
                        } elseif ($selisihHari <= 7) {
                            $statusText = 'Minggu Ini';
                            $statusColor = 'bg-yellow-100 text-yellow-700 border-yellow-200';
                        } else {
                            $statusText = 'Akan Datang';
                            $statusColor = 'bg-blue-100 text-blue-700 border-blue-200';
                        }
                ?>
                        <div class="bg-white rounded-xl shadow-md border-l-4 <?= $statusColor ?> hover:shadow-lg transition duration-300">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                            <?= htmlspecialchars($data['namaKegiatan']) ?>
                                        </h3>
                                        <span class="inline-block px-2 py-1 text-xs font-medium rounded-full <?= $statusColor ?>">
                                            <?= $statusText ?>
                                        </span>
                                    </div>
                                    <div class="flex space-x-1">
                                        <a href="?edit=<?= $data['idKegiatan'] ?>"
                                            class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-100 rounded-full transition duration-200">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <a href="?hapus=<?= $data['idKegiatan'] ?>"
                                            onclick="return confirm('Yakin ingin menghapus kegiatan ini?')"
                                            class="text-red-600 hover:text-red-800 p-2 hover:bg-red-100 rounded-full transition duration-200">
                                            <i class="fas fa-trash text-sm"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="space-y-3 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt w-5 mr-3"></i>
                                        <?= date('d F Y', strtotime($data['tanggal'])) ?>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt w-5 mr-3"></i>
                                        <?= htmlspecialchars($data['lokasi']) ?>
                                    </div>
                                    <?php if (!empty($data['deskripsi'])): ?>
                                        <div class="flex items-start">
                                            <i class="fas fa-align-left w-5 mr-3 mt-1"></i>
                                            <p class="leading-relaxed">
                                                <?= nl2br(htmlspecialchars($data['deskripsi'])) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php }
                } else { ?>
                    <div class="col-span-full">
                        <div class="bg-white rounded-lg shadow-md p-12 text-center">
                            <i class="fas fa-calendar-times text-gray-400 text-6xl mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Kegiatan</h3>
                            <p class="text-gray-500 mb-6">Mulai tambahkan kegiatan pertama Anda</p>
                            <button onclick="openModal('modalTambah')"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                                <i class="fas fa-plus mr-2"></i> Tambah Kegiatan
                            </button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </main>
    </div>

    <?php include __DIR__ . '/../partials/modal-tambah-kegiatan.php'; ?>
    <?php if ($editData) include __DIR__ . '/../partials/modal-edit-kegiatan.php'; ?>
    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>

</html>