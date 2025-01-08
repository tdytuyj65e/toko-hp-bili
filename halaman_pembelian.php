<?php
// Turn off error reporting to prevent issues with PDF generation
error_reporting(0);
ini_set('display_errors', 0);

// Include database connection
include '../koneksi/koneksi.php';

// Include header (ensure the path is correct)
include 'header.php';

// Include FPDF library (ensure the path is correct)
include('../fpdf184/fpdf.php');

// Get all phones from the database
$query = "SELECT * FROM phones";
$result = $conn->query($query);

// Handle error jika query gagal
if (!$result) {
    die("Terjadi kesalahan saat memuat data produk. Silakan coba lagi nanti.");
}

// Handle form submission (purchase)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone_id = $_POST['phone_id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $quantity = $_POST['quantity'];

    // Fetch phone details using prepared statement to avoid SQL injection
    $phone_query = "SELECT * FROM phones WHERE id = ?";
    $stmt = $conn->prepare($phone_query);
    $stmt->bind_param('i', $phone_id);
    $stmt->execute();
    $phone_result = $stmt->get_result();
    $phone = $phone_result->fetch_assoc();

    if ($phone && $phone['stock'] >= $quantity) {
        // Calculate the total price
        $total_price = $phone['price'] * $quantity;

        // Insert into the sales table using prepared statement
        $insert_sale = "INSERT INTO sales (phone_id, name, address, quantity, total_price, date) 
                        VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($insert_sale);
        $stmt->bind_param('issid', $phone_id, $name, $address, $quantity, $total_price);
        $stmt->execute();

        // Get the inserted sale ID
        $sale_id = $conn->insert_id; // Ensure this is set properly

        // Update phone stock using prepared statement
        $new_stock = $phone['stock'] - $quantity;
        $update_stock = "UPDATE phones SET stock = ? WHERE id = ?";
        $stmt = $conn->prepare($update_stock);
        $stmt->bind_param('ii', $new_stock, $phone_id);
        $stmt->execute();

        // Generate PDF receipt
        generate_pdf($sale_id, $name, $address, $phone['name'], $quantity, $total_price);

        // Redirect to a success page or show success message
        echo "<script>alert('Pembelian berhasil!'); window.location.href='halaman_pembelian.php';</script>";
    } else {
        $error_message = "Stok tidak mencukupi untuk jumlah yang diminta.";
    }
}

// Function to generate PDF receipt
function generate_pdf($sale_id, $name, $address, $phone_name, $quantity, $total_price) {
    ob_end_clean();  // Clean the output buffer before generating the PDF

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Title
    $pdf->Cell(0, 10, 'Kwitansi Pembelian Smartphone', 0, 1, 'C');
    $pdf->Ln(10);

    // Sale Details
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 10, 'ID Penjualan:', 0, 0);
    $pdf->Cell(0, 10, $sale_id, 0, 1); // Ensure sale_id is printed

    $pdf->Cell(50, 10, 'Nama Pembeli:', 0, 0);
    $pdf->Cell(0, 10, $name, 0, 1);

    $pdf->Cell(50, 10, 'Alamat:', 0, 0);
    $pdf->MultiCell(0, 10, $address);

    $pdf->Cell(50, 10, 'Smartphone:', 0, 0);
    $pdf->Cell(0, 10, $phone_name, 0, 1);

    $pdf->Cell(50, 10, 'Jumlah:', 0, 0);
    $pdf->Cell(0, 10, $quantity, 0, 1);

    $pdf->Cell(50, 10, 'Total Harga:', 0, 0);
    $pdf->Cell(0, 10, 'Rp' . number_format($total_price, 2, ',', '.'), 0, 1);

    // Footer
    $pdf->Ln(20);
    $pdf->Cell(0, 10, 'Terima kasih atas pembelian Anda.', 0, 1, 'C');

    // Output PDF
    $pdf->Output('D', 'Kwitansi_Penjualan_' . $sale_id . '.pdf');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian Produk - Dealer HP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Pembelian Smartphone</h1>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="phone_id" class="form-label">Pilih Smartphone</label>
            <select name="phone_id" id="phone_id" class="form-select" required>
                <option value="">-- Pilih Smartphone --</option>
                <?php 
                // Save phones data to be used later in the form
                while ($row = $result->fetch_assoc()): 
                ?>
                    <option value="<?= htmlspecialchars($row['id']); ?>">
                        <?= htmlspecialchars($row['name']) . " - " . "Rp" . number_format($row['price'], 2); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Nama Pembeli</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Alamat Pembeli</label>
            <textarea id="address" name="address" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Jumlah Pembelian</label>
            <input type="number" id="quantity" name="quantity" class="form-control" min="1" required>
        </div>

        <button type="submit" class="btn btn-success">Proses Pembelian</button>
    </form>
</div>

<script>
    // JavaScript code for stock validation as before
</script>

</body>
</html>
