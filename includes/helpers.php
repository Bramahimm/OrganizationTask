<?php
function checkUsersAccess() {
    $role = $_SESSION['user']['role'];
    if ($role === 'pengurus') {
        header("Location: ../views/dashboardPengurus.php");
        exit();
    } elseif ($role === 'anggota') {
        header("Location: ../views/dashboardAnggota.php");
        exit();
    }

    header("Location: ../index.php");
    exit();
}

function tambahTugas($conn, $data, $idUser) {
    $judul = mysqli_real_escape_string($conn, $data['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $data['deskripsi']);
    $deadline = mysqli_real_escape_string($conn, $data['deadline']);
    $status = mysqli_real_escape_string($conn, $data['status']);

    $query = "INSERT INTO tugas (judul, deskripsi, deadline, status, idUser) 
            VALUES ('$judul', '$deskripsi', '$deadline', '$status', '$idUser')";
    return mysqli_query($conn, $query);
}

function updateTugas($conn, $data) {
    $idTugas = mysqli_real_escape_string($conn, $data['idTugas']);
    $judul = mysqli_real_escape_string($conn, $data['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $data['deskripsi']);
    $deadline = mysqli_real_escape_string($conn, $data['deadline']);
    $status = mysqli_real_escape_string($conn, $data['status']);

    $query = "UPDATE tugas 
            SET judul='$judul', deskripsi='$deskripsi', deadline='$deadline', status='$status' 
            WHERE idTugas='$idTugas'";
    return mysqli_query($conn, $query);
}

function getTugasByUserId($conn, $idUser) {
    $query = "SELECT * FROM tugas WHERE idUser = '$idUser' ORDER BY deadline ASC";
    return mysqli_query($conn, $query);
}

function redirectWithError($type) {
    header("Location: index.php?route=login&error=$type");
    exit;
}

function redirectToDashboard($role) {
    switch ($role) {
        case 'pengurus':
            header("Location: views/dashboardPengurus.php");
            break;
        case 'anggota':
            header("Location: views/dashboardAnggota.php");
            break;
        default:
            redirectWithError('unknown_role');
    }
    exit;
}
