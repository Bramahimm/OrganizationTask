<div id="modalEdit" class="modal fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-semibold text-gray-900">Edit Kegiatan</h3>
      <a href="jadwalKegiatanPengurus.php" class="text-gray-400 hover:text-gray-600">
        <i class="fas fa-times"></i>
      </a>
    </div>
    <form method="POST" action="">
      <input type="hidden" name="idKegiatan" value="<?= $editData['idKegiatan'] ?>">
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan</label>
          <input type="text" name="namaKegiatan" value="<?= htmlspecialchars($editData['namaKegiatan']) ?>" required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
          <textarea name="deskripsi" rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($editData['deskripsi']) ?></textarea>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
          <input type="date" name="tanggal" value="<?= $editData['tanggal'] ?>" required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
          <input type="text" name="lokasi" value="<?= htmlspecialchars($editData['lokasi']) ?>"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
      </div>
      <div class="flex justify-end space-x-3 mt-6">
        <a href="jadwalKegiatanPengurus.php"
          class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300 transition duration-200">Batal</a>
        <button type="submit" name="edit_kegiatan"
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">Update</button>
      </div>
    </form>
  </div>
</div>