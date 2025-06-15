<?php
session_start();
include '../includes/database.php';


$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    header("Location: ../index.php?error=1");
    exit;
}

$query = "SELECT * FROM user WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if (!$data = mysqli_fetch_assoc($result)) {
    header("Location: ../index.php?error=1");
    exit;
}

if (password_verify($password, $data['password'])) {
    $_SESSION['user'] = [
        'id' => $data['idUser'],
        'nama' => $data['nama'],
        'role' => $data['role']
    ];

    if ($data['role'] === 'pengurus') {
        header("Location: ../views/dashboardPengurus.php");
    } else {
        header("Location: ../views/dashboardAnggota.php");
    }
    exit;
}

header("Location: ../index.php?error=1");
exit;
