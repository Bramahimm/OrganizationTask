<?php
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/navbar.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="flex pt-16">
    <main class="flex-1 p-6 ml-64">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Tugas</h2>
            <button onclick="openModal('modalTambah')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition duration-200">
                <i class="fas fa-plus"></i> Tambah Tugas
            </button>

        </div>

        <?php if (!empty($message)): ?>
            <div class="mb-6 p-4 rounded-lg <?= $messageType === 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' ?>">
                <i class="fas <?= $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> mr-2"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Daftar Tugas Anda</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deadline</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($tugasList && $tugasList->num_rows > 0): ?>
                            <?php $no = 1;
                            foreach ($tugasList as $data): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4"><?= $no++ ?></td>
                                    <td class="px-6 py-4 font-medium"><?= htmlspecialchars($data['judul']) ?></td>
                                    <td class="px-6 py-4 truncate"><?= htmlspecialchars($data['deskripsi']) ?></td>
                                    <td class="px-6 py-4"><?= date('d/m/Y', strtotime($data['deadline'])) ?></td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $status = strtolower($data['status']);
                                        $color = match ($status) {
                                            'belum' => 'bg-red-100 text-red-800',
                                            'proses' => 'bg-yellow-100 text-yellow-800',
                                            'selesai' => 'bg-green-100 text-green-800',
                                            default => 'bg-gray-100 text-gray-600'
                                        };
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $color ?>">
                                            <?= ucfirst($status) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="index.php?route=tugas&edit=<?= $data['idTugas'] ?>" class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded">Edit</a>
                                            <button onclick="confirmHapus(<?= $data['idTugas'] ?>)" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">
                                    <i class="fas fa-inbox text-3xl mb-2"></i>
                                    <p>Belum ada tugas.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    function confirmHapus(id) {
        const modal = document.getElementById('modalHapus');
        const link = document.getElementById('btnHapusLink');
        if (modal && link) {
            link.href = `index.php?route=tugas&hapus=${id}`;
            modal.classList.remove('hidden');
        }
    }
</script>
<?php include __DIR__ . '/../partials/modal-tambah-tugas.php'; ?>
<?php include __DIR__ . '/../partials/modal-hapus-tugas.php'; ?>
<?php if ($editData) include __DIR__ . '/../partials/modal-edit-tugas.php'; ?>
<?php include __DIR__ . '/../layout/footer.php'; ?>