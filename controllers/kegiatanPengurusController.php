<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../models/Kegiatan.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pengurus') {
  header("Location: index.php?route=login");
  exit;
}

$idUser = $_SESSION['user']['id'];

// Ambil semua idOrganisasi yang dikelola oleh user
$orgQuery = $conn->prepare("SELECT idOrganisasi FROM user_organisasi WHERE idUser = ? AND role = 'pengurus'");
$orgQuery->bind_param("i", $idUser);
$orgQuery->execute();
$orgResult = $orgQuery->get_result();

$organisasiIds = array();
while ($row = $orgResult->fetch_assoc()) {
  $organisasiIds[] = $row['idOrganisasi'];
}

// Jika tidak mengelola organisasi apa pun
if (empty($organisasiIds)) {
  $kegiatanList = [];
} else {
  $inQuery = implode(',', array_fill(0, count($organisasiIds), '?'));
  $stmt = $conn->prepare("SELECT * FROM kegiatan WHERE idOrganisasi IN ($inQuery) ORDER BY tanggal ASC");
  $stmt->bind_param(str_repeat('i', count($organisasiIds)), ...$organisasiIds);
  $stmt->execute();
  $kegiatanList = $stmt->get_result();
}

$message = '';
$messageType = '';
$title = 'Jadwal Kegiatan Pengurus';
include __DIR__ . '/../views/jadwalKegiatanPengurus.php';
