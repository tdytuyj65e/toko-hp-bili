<?php
include '../koneksi/koneksi.php';
include 'header.php';

// Query to get the total number of products
$totalProductsQuery = "SELECT COUNT(*) AS total_products FROM phones";
$totalProductsResult = $conn->query($totalProductsQuery);
$totalProducts = $totalProductsResult->fetch_assoc()['total_products'];

// Query to get the total stock
$totalStockQuery = "SELECT SUM(stock) AS total_stock FROM phones";
$totalStockResult = $conn->query($totalStockQuery);
$totalStock = $totalStockResult->fetch_assoc()['total_stock'];

// Query to calculate the total value of products in stock
$totalValueQuery = "SELECT SUM(price * stock) AS total_value FROM phones";
$totalValueResult = $conn->query($totalValueQuery);
$totalValue = $totalValueResult->fetch_assoc()['total_value'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dealer HP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8;
            color: #333;
        }
        .card-body h5 {
            font-size: 2.2rem;
            font-weight: 700;
        }
        .card-body p {
            font-size: 1.1rem;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            font-weight: 700;
            font-size: 1.4rem;
            background-color: #f8f9fa;
        }
        .card-body {
            padding: 1.5rem;
        }
        .icon-container {
            font-size: 2.5rem;
            color: #ffffff;
            border-radius: 50%;
            padding: 15px;
            background-color: #007bff;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .btn-primary {
            font-size: 1.3rem;
            padding: 10px 20px;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .card-header i {
            margin-right: 10px;
        }
        .btn-outline-primary {
            font-size: 1.2rem;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h1 class="text-center mb-5">Dashboard Dealer HP</h1>

        <div class="row">
            <!-- Total Products Card -->
            <div class="col-md-4 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-header d-flex align-items-center">
                        <div class="icon-container me-3">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <span>Total Produk</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $totalProducts; ?> Produk</h5>
                        <p class="card-text">Jumlah total produk yang tersedia di sistem.</p>
                    </div>
                </div>
            </div>

            <!-- Total Stock Card -->
            <div class="col-md-4 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-header d-flex align-items-center">
                        <div class="icon-container me-3">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <span>Total Stok</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $totalStock; ?> Unit</h5>
                        <p class="card-text">Jumlah total stok produk yang tersedia di sistem.</p>
                    </div>
                </div>
            </div>

            <!-- Total Value Card -->
            <div class="col-md-4 mb-4">
                <div class="card bg-warning text-dark">
                    <div class="card-header d-flex align-items-center">
                        <div class="icon-container me-3">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <span>Total Nilai Stok</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Rp <?= number_format($totalValue, 2); ?></h5>
                        <p class="card-text">Nilai total produk berdasarkan harga dan stok.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manage Products Link -->
        <div class="text-center mt-4">
            <a href="products.php" class="btn btn-primary btn-lg">
                <i class="fas fa-edit"></i> Kelola Produk
            </a>
            <a href="sales.php" class="btn btn-outline-primary btn-lg ms-3">
                <i class="fas fa-chart-line"></i> Lihat Penjualan
            </a>
        </div>
    </div>

</body>
</html>
