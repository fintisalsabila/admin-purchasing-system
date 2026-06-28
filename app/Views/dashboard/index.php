<div class="dashboard-page animate__animated animate__fadeIn">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="welcome-text">
            <h2>Selamat Datang, <span class="highlight">Admin!</span></h2>
            <p>Berikut adalah ringkasan aktivitas toko Anda hari ini.</p>
        </div>
        <div class="welcome-actions">
            <a href="<?= base_url('purchases/create') ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i> Tambah Pembelian
            </a>
            <a href="<?= base_url('products/create') ?>" class="btn btn-secondary btn-lg">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card animate__animated animate__fadeInUp animate__delay-1s">
            <div class="stat-icon bg-primary">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-content">
                <h3>Total Pembelian</h3>
                <p class="stat-number"><?= $stats['total']['total'] ?? 0 ?></p>
                <small>Rp <?= number_format($stats['total']['total_amount'] ?? 0, 0, ',', '.') ?></small>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> 12.5%
                </div>
            </div>
        </div>
        
        <div class="stat-card animate__animated animate__fadeInUp animate__delay-2s">
            <div class="stat-icon bg-success">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-content">
                <h3>Pembelian Hari Ini</h3>
                <p class="stat-number"><?= $stats['today']['total'] ?? 0 ?></p>
                <small>Rp <?= number_format($stats['today']['total_amount'] ?? 0, 0, ',', '.') ?></small>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> 8.3%
                </div>
            </div>
        </div>
        
        <div class="stat-card animate__animated animate__fadeInUp animate__delay-3s">
            <div class="stat-icon bg-danger">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-content">
                <h3>Dibatalkan</h3>
                <p class="stat-number"><?= $stats['cancelled']['total'] ?? 0 ?></p>
                <small>Transaksi dibatalkan</small>
                <div class="stat-trend down">
                    <i class="fas fa-arrow-down"></i> 3.2%
                </div>
            </div>
        </div>
        
        <div class="stat-card animate__animated animate__fadeInUp animate__delay-4s">
            <div class="stat-icon bg-warning">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <h3>Total Produk</h3>
                <p class="stat-number"><?= count($products) ?></p>
                <small>Produk aktif</small>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> 5.0%
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <?php if (!empty($lowStockProducts)): ?>
    <div class="alert alert-warning animate__animated animate__headShake">
        <div class="alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="alert-content">
            <strong>Peringatan Stok Rendah!</strong>
            <p>Terdapat <?= count($lowStockProducts) ?> produk dengan stok kritis:</p>
            <div class="low-stock-list">
                <?php foreach ($lowStockProducts as $product): ?>
                    <span class="low-stock-tag">
                        <?= $product['name'] ?> 
                        <span class="stock-badge"><?= $product['stock'] ?>/<?= $product['min_stock'] ?></span>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Recent Purchases -->
    <div class="card animate__animated animate__fadeInUp">
        <div class="card-header">
            <div class="card-header-left">
                <i class="fas fa-clock"></i>
                <h3>Pembelian Terbaru</h3>
            </div>
            <a href="<?= base_url('purchases') ?>" class="btn btn-sm btn-outline-primary">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Customer</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentPurchases)): ?>
                            <?php foreach (array_slice($recentPurchases, 0, 8) as $purchase): ?>
                                <tr>
                                    <td><span class="invoice-code"><?= $purchase['invoice_number'] ?></span></td>
                                    <td><?= $purchase['product_name'] ?></td>
                                    <td><span class="qty-badge"><?= $purchase['quantity'] ?></span></td>
                                    <td><span class="price">Rp <?= number_format($purchase['total_price'], 0, ',', '.') ?></span></td>
                                    <td><?= $purchase['customer_name'] ?></td>
                                    <td><span class="badge badge-<?= $purchase['status'] ?>"><?= $purchase['status'] ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-inbox" style="font-size: 2rem; color: #ccc;"></i>
                                    <p style="color: #999;">Belum ada pembelian</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>