<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireRole($role) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== $role) {
        header("Location: ../index.php");
        exit;
    }
}

function activeClass($file) {
    $current = basename($_SERVER['PHP_SELF']);
    return $current === $file ? 'bg-blue-600 text-white' : 'hover:bg-gray-100 text-black';
}

function getUserId() {
    return $_SESSION['user']['id'] ?? null;
}

function getUserName() {
    return $_SESSION['user']['nama'] ?? 'Pengguna';
}


function isPengurus() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'pengurus';
}

function isAnggota() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'anggota';
}
function redirectWithError($type) {
    header("Location: ../index.php?error=$type");
    exit;
}

function redirectToDashboard($role) {
    if ($role === 'pengurus') {
        header("Location: ../dashboardPengurus.php");
    } elseif ($role === 'anggota') {
        header("Location: ../dashboardAnggota.php");
    } else {
        header("Location: ../index.php");
    }
    exit;
}
