<?php
require_once 'koneksi.php';
require_once 'Buku.php';

$bukuObj = new Buku($koneksi);
$pesan = "";

if (isset($_POST['submit'])) {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $stok = $_POST['stok'];

    if (isset($_POST['id']) && $_POST['id'] != "") {
        $hasil = $bukuObj->update($_POST['id'], $judul, $penulis, $stok);
    } else {
        $hasil = $bukuObj->create($judul, $penulis, $stok);
    }

    if ($hasil === true) {
        $pesan = "Data berhasil disimpan!";
    } else {
        $pesan = $hasil; 
    }
}

if (isset($_GET['hapus'])) {
    $bukuObj->delete($_GET['hapus']);
    header("Location: index.php");
}

$editData = null;
if (isset($_GET['edit'])) {
    $editData = $bukuObj->find($_GET['edit']);
}

if (isset($_GET['cari'])) {
    $tampilBuku = $bukuObj->search($_GET['cari']);
} else {
    $tampilBuku = $bukuObj->all();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mini Proyek Perpustakaan</title>
</head>
<body>
    <h2>Form Tambah / Edit Buku</h2>
    <?php if ($pesan != "") echo "<p style='color:red;'><b>$pesan</b></p>"; ?>
    
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo isset($editData['id']) ? $editData['id'] : ''; ?>">
        <label>Judul Buku:</label><br>
        <input type="text" name="judul" value="<?php echo isset($editData['judul']) ? htmlspecialchars($editData['judul']) : ''; ?>"><br><br>
        
        <label>Penulis:</label><br>
        <input type="text" name="penulis" value="<?php echo isset($editData['penulis']) ? htmlspecialchars($editData['penulis']) : ''; ?>"><br><br>
        
        <label>Stok:</label><br>
        <input type="number" name="stok" value="<?php echo isset($editData['stok']) ? $editData['stok'] : ''; ?>"><br><br>
        
        <button type="submit" name="submit">Simpan</button>
    </form>

    <hr>

    <h2>Daftar Buku Perpustakaan</h2>
    <form method="GET" action="">
        <input type="text" name="cari" placeholder="Cari judul buku..." value="<?php echo isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : ''; ?>">
        <button type="submit">Cari</button>
        <a href="index.php"><button type="button">Reset</button></a>
    </form>
    <br>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $tampilBuku->fetch_assoc()) : ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['judul']); ?></td>
            <td><?php echo htmlspecialchars($row['penulis']); ?></td>
            <td><?php echo htmlspecialchars($row['stok']); ?></td>
            <td>
                <a href="index.php?edit=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="index.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
