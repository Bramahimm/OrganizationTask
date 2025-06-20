<?php
require_once __DIR__ . '/../includes/helpers.php';
$title = 'Orgenius - Register';
include __DIR__ . '/../layout/header.php';
?>

<body class="flex items-center justify-center bg-[#ffe4c4] min-h-screen">
  <div class="bg-white shadow-lg flex rounded-xl overflow-hidden max-w-4xl w-full">
    <!-- Gambar kiri -->
    <div class="w-1/2 hidden md:block relative">
      <img src="assets/img/orgenius.png" alt="Illustration" class="object-cover w-full h-full" />
      <div class="absolute bottom-0 left-0 w-full text-black p-4 bg-gray-500 bg-opacity-50">
        <p class="text-sm font-semibold">Sistem Kelola Tugas & Organisasi</p>
      </div>
    </div>

    <!-- Form Registrasi -->
    <div class="w-full md:w-1/2 p-8 relative">
      <div class="text-center mb-6">
        <img src="assets/img/orgenius.png" alt="Unila" class="mx-auto w-20 mb-2" />
        <h2 class="text-xl font-semibold text-gray-700">Silakan Daftar</h2>

        <?php if (isset($_GET['error'])): ?>
          <p class="text-red-600 text-sm mt-2"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>
      </div>

      <form action="index.php?route=register" method="POST" class="space-y-4">
        <div>
          <label class="sr-only">Nama Lengkap</label>
          <input type="text" name="nama" placeholder="Nama Lengkap" required class="w-full py-2 px-3 border rounded-md focus:outline-none" />
        </div>

        <div>
          <label class="sr-only">Email</label>
          <input type="email" name="email" placeholder="Email" required class="w-full py-2 px-3 border rounded-md focus:outline-none" />
        </div>

        <div>
          <label class="sr-only">Password</label>
          <input type="password" name="password" placeholder="Password" required class="w-full py-2 px-3 border rounded-md focus:outline-none" />
        </div>

        <div>
          <label class="sr-only">Peran</label>
          <select name="role" id="roleSelect" required class="w-full py-2 px-3 border rounded-md focus:outline-none">
            <option value="">Pilih Peran</option>
            <option value="anggota">Anggota</option>
            <option value="pengurus">Pengurus</option>
          </select>
        </div>

        <div id="orgGroup" class="hidden">
          <label class="sr-only">Nama Organisasi</label>
          <input type="text" name="namaOrganisasi" id="orgInput" placeholder="Nama Organisasi"
            class="w-full py-2 px-3 border rounded-md focus:outline-none" />
        </div>

        <div class="flex justify-center items-center">
          <button type="submit" name="register" class="w-20 bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">
            Daftar
          </button>
        </div>
      </form>

      <p class="text-sm text-right mt-2">
        Sudah punya akun? <a href="index.php?route=login" class="text-blue-600 hover:underline">Login</a>
      </p>
    </div>
  </div>

  <script>
    const roleSelect = document.getElementById('roleSelect');
    const orgGroup = document.getElementById('orgGroup');
    const orgInput = document.getElementById('orgInput');

    roleSelect.addEventListener('change', function() {
      if (this.value === 'pengurus') {
        orgGroup.classList.remove('hidden');
        orgInput.setAttribute('required', 'required');
      } else {
        orgGroup.classList.add('hidden');
        orgInput.removeAttribute('required');
      }
    });
  </script>
</body>
