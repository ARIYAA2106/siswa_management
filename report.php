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

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'orientation' => 'P',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 15,
    'margin_bottom' => 15,
    'margin_header' => 10,
    'margin_footer' => 10
]);

// Metadata dokumen
$mpdf->SetTitle('Laporan Data Mahasiswa');
$mpdf->SetAuthor('Aplikasi Akademik');

// CSS styling minimalis
$stylesheet = '
<style>
    body {
        font-family: "Helvetica", Arial, sans-serif;
        font-size: 11px;
        color: #333;
        line-height: 1.5;
    }
    .header {
        text-align: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    .title {
        font-size: 16px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 5px;
    }
    .subtitle {
        font-size: 12px;
        color: #7f8c8d;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0;
    }
    th {
        background-color: #2c3e50;
        color: white;
        padding: 8px;
        text-align: left;
        font-weight: normal;
    }
    td {
        padding: 7px;
        border-bottom: 1px solid #eee;
    }
    .footer {
        font-size: 10px;
        text-align: center;
        color: #7f8c8d;
        border-top: 1px solid #eee;
        padding-top: 5px;
    }
    .text-success { color: #27ae60; }
    .text-info { color: #2980b9; }
    .text-warning { color: #f39c12; }
    .text-danger { color: #e74c3c; }
</style>';

// Header sederhana
$header = '
<div class="header">
    <div class="title">LAPORAN DATA MAHASISWA</div>
    <div class="subtitle">Dicetak pada: '.date('d F Y H:i:s').'</div>
</div>';

// Footer sederhana
$footer = '
<div class="footer">
    Halaman {PAGENO} dari {nbpg} | Aplikasi Akademik | '.date('d/m/Y').'
</div>';

$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer);

// Konten laporan
$html = $stylesheet . '
<table>
    <thead>
        <tr>
            <th width="15%">NIM</th>
            <th width="30%">Nama</th>
            <th width="20%">Jurusan</th>
            <th width="10%">Semester</th>
            <th width="10%">IPK</th>
            <th width="15%">Status</th>
        </tr>
    </thead>
    <tbody>';

foreach ($dataMahasiswa as $mhs) {
    // Tentukan status berdasarkan IPK
    if ($mhs['ipk'] >= 3.5) {
        $status = '<span class="text-success">Cumlaude</span>';
    } elseif ($mhs['ipk'] >= 3.0) {
        $status = '<span class="text-info">Sangat Baik</span>';
    } elseif ($mhs['ipk'] >= 2.0) {
        $status = '<span class="text-warning">Baik</span>';
    } else {
        $status = '<span class="text-danger">Perhatian</span>';
    }
    
    $html .= '
        <tr>
            <td>'.$mhs['nim'].'</td>
            <td>'.$mhs['nama'].'</td>
            <td>'.$mhs['jurusan'].'</td>
            <td>'.$mhs['semester'].'</td>
            <td>'.$mhs['ipk'].'</td>
            <td>'.$status.'</td>
        </tr>';
}

// Ringkasan data
$total = count($dataMahasiswa);
$avgIpk = $total > 0 ? array_sum(array_column($dataMahasiswa, 'ipk')) / $total : 0;

$html .= '
    </tbody>
</table>

<div style="margin-top: 20px; font-size: 12px;">
    <strong>Ringkasan:</strong> Total '.$total.' mahasiswa | IPK Rata-rata: '.number_format($avgIpk, 2).'
</div>';

$mpdf->WriteHTML($html);

// Output PDF
$mpdf->Output('Laporan_Mahasiswa_'.date('Ymd').'.pdf', 'D');
?>