<?php
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
            include 'views/dashboardPengurus.php';
        } elseif ($role === 'anggota') {
            include 'views/dashboardAnggota.php';
        } else {
            echo "<h1>403 - Role tidak dikenali</h1>";
        }
        break;
}
