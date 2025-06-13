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
$dataMahasiswa = $mahasiswa->semuaMahasiswa();

require_once 'vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

$html = '
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Mahasiswa</title>
    <style>
        body { font-family: Arial; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Laporan Data Mahasiswa</h1>
    <table>
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Semester</th>
                <th>IPK</th>
            </tr>
        </thead>
        <tbody>';

foreach ($dataMahasiswa as $mhs) {
    $html .= '
            <tr>
                <td>'.$mhs['nim'].'</td>
                <td>'.$mhs['nama'].'</td>
                <td>'.$mhs['jurusan'].'</td>
                <td>'.$mhs['semester'].'</td>
                <td>'.$mhs['ipk'].'</td>
            </tr>';
}

$html .= '
        </tbody>
    </table>
</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output('laporan_mahasiswa.pdf', 'D');
?>