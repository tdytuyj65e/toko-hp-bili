<?php
include '../koneksi/koneksi.php';
include 'header.php';
$query = "SELECT * FROM phones";
$result = $conn->query($query);

// Handle error jika query gagal
if (!$result) {
    die("Error pada query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dealer HP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
 

    <div class="container mt-5">
        <h1 class="text-center">Daftar Produk</h1>
        <a href="tambah_produk.php" class="btn btn-primary mb-3">Tambah Produk</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Merek</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($no++); ?></td>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= htmlspecialchars($row['brand']); ?></td>
                            <td>Rp <?= number_format($row['price'], 2); ?></td>
                            <td><?= htmlspecialchars($row['stock']); ?></td>
                            <td>
                                <?php if (!empty($row['photo'])): ?>
                                    <img src="../uploads/<?= htmlspecialchars($row['photo']); ?>" alt="Foto Produk" width="100">
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada foto</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="pages/edit_product.php?id=<?= urlencode($row['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="pages/delete_product.php?id=<?= urlencode($row['id']); ?>" class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
