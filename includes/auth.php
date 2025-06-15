<?php
require_once __DIR__ . '/helpers.php';  
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/User.php';
session_start();

// ini untuk ngammbil input
$email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// ini untuk validasi input
if (!$email || !$password) {
    redirectWithError('empty');
}

// ini untuk ngambil user dari database
$user = getUserByEmail($conn, $email);

// ngevalidasi email ama password
if (!$user || !password_verify($password, $user['password'])) {
    redirectWithError('invalid');
}

// buat nyimpen sesi
$_SESSION['user'] = [
    'id'    => $user['idUser'],
    'nama'  => $user['nama'],
    'role'  => $user['role']
];

// ini buat ngedirect sesuai ama role
redirectToDashboard($user['role']);

