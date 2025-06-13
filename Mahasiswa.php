<?php
class Mahasiswa {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Create - Tambah data mahasiswa
    public function tambahMahasiswa($nim, $nama, $jurusan, $semester, $ipk) {
        $sql = "INSERT INTO mahasiswa (nim, nama, jurusan, semester, ipk) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssid", $nim, $nama, $jurusan, $semester, $ipk);
        return $stmt->execute();
    }
    
    // Read - Ambil semua data mahasiswa
    public function semuaMahasiswa() {
        $sql = "SELECT * FROM mahasiswa";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Update - Edit data mahasiswa
    public function editMahasiswa($id, $nim, $nama, $jurusan, $semester, $ipk) {
        $sql = "UPDATE mahasiswa SET nim=?, nama=?, jurusan=?, semester=?, ipk=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssidi", $nim, $nama, $jurusan, $semester, $ipk, $id);
        return $stmt->execute();
    }
    
    // Delete - Hapus data mahasiswa
    public function hapusMahasiswa($id) {
        $sql = "DELETE FROM mahasiswa WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // Ambil data mahasiswa berdasarkan ID
    public function getMahasiswaById($id) {
        $sql = "SELECT * FROM mahasiswa WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>