<?php
require_once 'koneksi.php';

class Buku {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    public function all() {
        $result = $this->db->query("SELECT * FROM buku");
        return $result;
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM buku WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($judul, $penulis, $stok) {
        if (empty($judul)) {
            return "Error: Judul tidak boleh kosong!";
        }
        if ($stok < 0) {
            return "Error: Stok tidak boleh negatif!";
        }

        $stmt = $this->db->prepare("INSERT INTO buku (judul, penulis, stok) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $judul, $penulis, $stok);
        return $stmt->execute();
    }

    public function update($id, $judul, $penulis, $stok) {
        if (empty($judul)) {
            return "Error: Judul tidak boleh kosong!";
        }
        if ($stok < 0) {
            return "Error: Stok tidak boleh negatif!";
        }

        $stmt = $this->db->prepare("UPDATE buku SET judul = ?, penulis = ?, stok = ? WHERE id = ?");
        $stmt->bind_param("ssii", $judul, $penulis, $stok, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM buku WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    public function search($keyword) {
        $stmt = $this->db->prepare("SELECT * FROM buku WHERE judul LIKE ?");
        $param = "%" . $keyword . "%";
        $stmt->bind_param("s", $param);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>