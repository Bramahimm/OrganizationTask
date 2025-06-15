<nav class="bg-[#ffe4c4] shadow-md px-6 py-2 flex justify-between items-center fixed w-full top-0 left-0 z-10">
  <div class="flex items-center space-x-3">
    <img src="../assets/img/orgenius.png" class="w-12 h-auto" alt="Orgenius">
    <h1 class="text-2xl font-bold text-blue-600">Orgenius</h1>
  </div>

  <div class="flex items-center gap-3">
    <span class="font-semibold"><?= htmlspecialchars($namaUser) ?></span>
    <img src="https://ui-avatars.com/api/?name=<?= urlencode($namaUser) ?>" class="w-10 h-10 rounded-full"
      alt="Avatar">
  </div>
</nav>
