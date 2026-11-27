<?php
$user = $_SESSION['user'] ?? null;
$role = $user['role'] ?? null;
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Praktikum Jarkom') ?></title>

    <!-- Bootstrap lokal & CSS khusus aplikasi -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg-dark text-light">

<table class="table table-borderless min-vh-100 mb-0">
    <!-- BARIS 1: NAVBAR / HEADER -->
    <tr style="height: 1%;">
        <td class="p-0">
            <nav class="navbar navbar-expand-lg navbar-dark bg-black border-bottom border-secondary px-3">
                <div class="container">
                    <a class="navbar-brand fw-bold text-info" href="/dashboard">Praktikum Jarkom</a>
                    
                    <?php if ($user): ?>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="/dashboard">Dashboard</a>
                            </li>
                            <?php if ($role === 'admin'): ?>
                                <li class="nav-item"><a class="nav-link" href="/admin/users">Pengguna</a></li>
                                <li class="nav-item"><a class="nav-link" href="/admin/course">Praktikum</a></li>
                                <li class="nav-item"><a class="nav-link" href="/admin/audit-logs">Log Audit</a></li>
                            <?php elseif ($role === 'aslab'): ?>
                                <li class="nav-item"><a class="nav-link" href="/aslab/sessions">Sesi Presensi</a></li>
                                <li class="nav-item"><a class="nav-link" href="/grades">Penilaian</a></li>
                                <li class="nav-item"><a class="nav-link" href="/points">Poin Keaktifan</a></li>
                                <li class="nav-item"><a class="nav-link" href="/recap">Rekap</a></li>
                            <?php elseif ($role === 'mahasiswa'): ?>
                                <li class="nav-item"><a class="nav-link" href="/mahasiswa/attendance">Presensi</a></li>
                                <li class="nav-item"><a class="nav-link" href="/mahasiswa/grades">Nilai</a></li>
                                <li class="nav-item"><a class="nav-link" href="/mahasiswa/recap">Rekap</a></li>
                            <?php endif; ?>
                        </ul>

                        <div class="d-flex align-items-center text-light gap-3">
                            <span class="small">
                                <?= e($user['name']) ?> 
                                <span class="badge bg-info text-dark text-uppercase"><?= e($role) ?></span>
                            </span>
                            <a href="/password/change" class="btn btn-outline-light btn-sm">Ganti Password</a>
                            <form method="post" action="/logout" class="m-0">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Keluar</button>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </nav>
        </td>
    </tr>

    <!-- BARIS 2: PEMBUKA KONTEN UTAMA -->
    <tr>
        <td class="align-top py-4">
            <main class="container">