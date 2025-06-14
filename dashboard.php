<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: index.php");
    exit;
}

require_once 'Mahasiswa.php';

// Koneksi database
$conn = new mysqli('localhost', 'root', '', 'akademik');
$mahasiswa = new Mahasiswa($conn);

// Proses CRUD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tambah'])) {
        $mahasiswa->tambahMahasiswa($_POST['nim'], $_POST['nama'], $_POST['jurusan'], $_POST['semester'], $_POST['ipk']);
    } elseif (isset($_POST['edit'])) {
        $mahasiswa->editMahasiswa($_POST['id'], $_POST['nim'], $_POST['nama'], $_POST['jurusan'], $_POST['semester'], $_POST['ipk']);
    }
}

if (isset($_GET['hapus'])) {
    $mahasiswa->hapusMahasiswa($_GET['hapus']);
}

$dataMahasiswa = $mahasiswa->semuaMahasiswa();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Aplikasi Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(rgba(245, 245, 245, 0.9), rgba(245, 245, 245, 0.9)), 
                        url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .container-main {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        .table thead {
            background-color: #3f51b5;
            color: white;
        }
        .btn-action {
            padding: 5px 10px;
            margin: 0 3px;
        }
        .page-title {
            color: #3f51b5;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
        .btn-add {
            margin-bottom: 20px;
            padding: 8px 20px;
        }
        .modal-header {
            background-color: #3f51b5;
            color: white;
        }
        .form-label {
            font-weight: 500;
        }
        .nav-buttons {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i>Aplikasi Akademik
            </a>
            <div class="nav-buttons">
                <a href="report.php" class="btn btn-light">
                    <i class="fas fa-file-pdf me-1"></i> Report PDF
                </a>
                <a href="?logout" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container container-main">
        <h2 class="page-title">
            <i class="fas fa-users me-2"></i>Data Mahasiswa
        </h2>
        
        <!-- Button trigger modal tambah -->
        <button type="button" class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-target="#tambahModal">
            <i class="fas fa-plus-circle me-1"></i>Tambah Mahasiswa
        </button>
        
        <!-- Tabel data mahasiswa -->
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th>Semester</th>
                        <th>IPK</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataMahasiswa as $mhs): ?>
                    <tr>
                        <td><?= htmlspecialchars($mhs['nim']) ?></td>
                        <td><?= htmlspecialchars($mhs['nama']) ?></td>
                        <td><?= htmlspecialchars($mhs['jurusan']) ?></td>
                        <td><?= htmlspecialchars($mhs['semester']) ?></td>
                        <td><?= htmlspecialchars($mhs['ipk']) ?></td>
                        <td class="text-center">
                            <a href="#" class="btn btn-warning btn-sm btn-action" data-bs-toggle="modal" data-bs-target="#editModal<?= $mhs['id'] ?>">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?hapus=<?= $mhs['id'] ?>" class="btn btn-danger btn-sm btn-action" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal<?= $mhs['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Mahasiswa</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $mhs['id'] ?>">
                                        <div class="mb-3">
                                            <label for="nim" class="form-label">NIM</label>
                                            <input type="text" class="form-control" id="nim" name="nim" value="<?= htmlspecialchars($mhs['nim']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nama" class="form-label">Nama</label>
                                            <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($mhs['nama']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="jurusan" class="form-label">Jurusan</label>
                                            <select class="form-select" id="jurusan" name="jurusan" required>
                                                <option value="Teknik Informatika" <?= $mhs['jurusan'] == 'Teknik Informatika' ? 'selected' : '' ?>>Teknik Informatika</option>
                                                <option value="Sistem Informasi" <?= $mhs['jurusan'] == 'Sistem Informasi' ? 'selected' : '' ?>>Sistem Informasi</option>
                                                <option value="Teknik Elektro" <?= $mhs['jurusan'] == 'Teknik Elektro' ? 'selected' : '' ?>>Teknik Elektro</option>
                                                <option value="Manajemen" <?= $mhs['jurusan'] == 'Manajemen' ? 'selected' : '' ?>>Manajemen</option>
                                                <option value="Akuntansi" <?= $mhs['jurusan'] == 'Akuntansi' ? 'selected' : '' ?>>Akuntansi</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="semester" class="form-label">Semester</label>
                                            <input type="number" class="form-control" id="semester" name="semester" min="1" max="14" value="<?= htmlspecialchars($mhs['semester']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="ipk" class="form-label">IPK</label>
                                            <input type="number" step="0.01" class="form-control" id="ipk" name="ipk" min="0" max="4" value="<?= htmlspecialchars($mhs['ipk']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i>Tutup
                                        </button>
                                        <button type="submit" name="edit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Tambah Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nim" class="form-label">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <select class="form-select" id="jurusan" name="jurusan" required>
                                <option value="">Pilih Jurusan</option>
                                <option value="Teknik Informatika">Teknik Informatika</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="Teknik Elektro">Teknik Elektro</option>
                                <option value="Manajemen">Manajemen</option>
                                <option value="Akuntansi">Akuntansi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <input type="number" class="form-control" id="semester" name="semester" min="1" max="14" required>
                        </div>
                        <div class="mb-3">
                            <label for="ipk" class="form-label">IPK</label>
                            <input type="number" step="0.01" class="form-control" id="ipk" name="ipk" min="0" max="4" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Tutup
                        </button>
                        <button type="submit" name="tambah" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>