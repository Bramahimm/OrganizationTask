<?php
session_start();

$route = $_GET['route'] ?? 'login';

switch ($route) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/includes/auth.php';
        } else {
            include __DIR__ . '/process/login.php';
        }
        break;

    case 'dashboard':
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?route=login");
            exit;
        }

        $role = $_SESSION['user']['role'];

        if ($role === 'pengurus') {
            include 'controllers/DashboardPengurusController.php';
        } elseif ($role === 'anggota') {
            include 'controllers/DashboardAnggotaController.php';
        } else {
            echo "<h1>403 - Role tidak dikenali</h1>";
        }
        break;

    case 'jadwal-kegiatan-pengurus':
        include 'controllers/kegiatanController.php';
        break;

    default:
        http_response_code(404);
        echo "<h1>404 - Halaman tidak ditemukan</h1>";
        break;
    case 'dashboard':
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?route=login");
            exit;
        }

        if ($_SESSION['user']['role'] === 'pengurus') {
            include 'controllers/DashboardPengurusController.php';
        } elseif ($_SESSION['user']['role'] === 'anggota') {
            include 'controllers/DashboardAnggotaController.php';
        }
        break;

    case 'tugas':
        include 'controllers/tugasController.php';
        break;
}
