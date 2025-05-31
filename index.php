<?php
session_start();
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    if ($role === 'pengurus') {
        header("Location: views/dashboardPengurus.php");
    } else {
        header("Location: views/dashboardAnggota.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Orgenius</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: bisque;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg flex rounded-xl overflow-hidden max-w-4xl w-full">
        <!-- Gambar kiri -->
        <div class="w-1/2 hidden md:block relative">
            <img src="assets/img/orgenius.png" alt="Gedung Rektorat" class="object-cover w-full h-full" />
            <div class="absolute bottom-0 left-0 w-full text-black p-4 bg-gray-500 bg-opacity-50">
                <p class="text-sm font-semibold">Sistem Kelola Tugas & Organisasi</p>
            </div>
        </div>
        <!-- Form Login -->
        <div class="w-full md:w-1/2 p-8 relative">
            <div class="text-center mb-6">
                <img src="assets/img/orgenius.png" alt="Unila" class="mx-auto w-20 mb-2" />
                <h2 class="text-xl font-semibold text-gray-700">Silakan Login</h2>
            </div>
            <form action="process/login.php" method="POST" class="space-y-4">
                <div>
                    <label class="sr-only">Email</label>
                    <div class="flex items-center border rounded-md overflow-hidden">
                        <span class="px-3 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 14a4 4 0 01-8 0m8 0a4 4 0 01-8 0m8 0H8m4 0v4m0 0H8m4 0h4" />
                            </svg>
                        </span>
                        <input type="email" name="email" placeholder="Email" required
                            class="w-full py-2 px-3 focus:outline-none" />
                    </div>
                </div>
                <div>
                    <label class="sr-only">Password</label>
                    <div class="flex items-center border rounded-md overflow-hidden">
                        <span class="px-3 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 11c0-1.657 1.343-3 3-3s3 1.343 3 3m-6 0c0-1.657-1.343-3-3-3s-3 1.343-3 3m6 0v2m0 2h6m-6 0H6" />
                            </svg>
                        </span>
                        <input type="password" name="password" placeholder="Password" required
                            class="w-full py-2 px-3 focus:outline-none" />
                    </div>
                </div>
                <div class="flex justify-center items-center">
                    <button type="submit"
                        class="w-20 bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">Login</button>
                </div>
            </form>
            <p class="text-sm text-right mt-2"><a href="#" class="text-blue-600 hover:underline">Lupa Password?</a></p>
        </div>
    </div>
</body>

</html>