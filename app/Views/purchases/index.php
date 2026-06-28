<div class="purchases-page animate__animated animate__fadeIn">
    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-shopping-cart"></i> Manajemen Pembelian</h2>
            <span class="page-subtitle">Kelola transaksi pembelian toko</span>
        </div>
        <div class="page-header-right">
            <button class="btn btn-outline-primary" onclick="exportPurchases()">
                <i class="fas fa-file-export"></i> Export
            </button>
            <a href="<?= base_url('purchases/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pembelian
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-group">
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" id="searchPurchase" placeholder="Cari invoice atau customer..." onkeyup="filterPurchases()">
            </div>
            <select id="filterStatus" onchange="filterPurchases()" class="form-select">
                <option value="all">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="cancelled">Dibatalkan</option>
            </select>
            <input type="date" id="filterDate" onchange="filterPurchases()" class="form-control">
        </div>
        <div class="filter-stats">
            <span>Menampilkan <strong id="purchaseCountDisplay">0</strong> transaksi</span>
            <span class="total-amount" id="totalAmountDisplay">Rp 0</span>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="purchaseTable">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Customer</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($purchases)): ?>
                            <?php foreach ($purchases as $purchase): ?>
                                <tr data-status="<?= $purchase['status'] ?>" 
                                    data-date="<?= date('Y-m-d', strtotime($purchase['purchase_date'])) ?>"
                                    data-total="<?= $purchase['total_price'] ?>">
                                    <td><span class="invoice-code"><?= $purchase['invoice_number'] ?></span></td>
                                    <td>
                                        <div class="product-cell">
                                            <span class="product-name"><?= $purchase['product_name'] ?></span>
                                            <span class="product-code-small"><?= $purchase['product_code'] ?></span>
                                        </div>
                                    </td>
                                    <td><span class="qty-badge"><?= $purchase['quantity'] ?></span></td>
                                    <td><span class="price">Rp <?= number_format($purchase['total_price'], 0, ',', '.') ?></span></td>
                                    <td>
                                        <div class="customer-cell">
                                            <span class="customer-name"><?= $purchase['customer_name'] ?></span>
                                            <?php if (!empty($purchase['customer_phone'])): ?>
                                                <span class="customer-phone"><?= $purchase['customer_phone'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($purchase['purchase_date'])) ?></td>
                                    <td><span class="badge badge-<?= $purchase['status'] ?>"><?= $purchase['status'] ?></span></td>
                                    <td>
                                        <?php if ($purchase['status'] === 'active'): ?>
                                            <button onclick="cancelPurchase(<?= $purchase['id'] ?>)" 
                                                    class="btn btn-sm btn-danger">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox" style="font-size: 2rem; color: #ccc;"></i>
                                    <p style="color: #999;">Belum ada pembelian</p>
                                    <a href="<?= base_url('purchases/create') ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Tambah Pembelian
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Form -->
<form id="cancelForm" method="POST" style="display:none;">
    <input type="hidden" name="_method" value="PUT">
</form>

<script>
function filterPurchases() {
    const search = document.getElementById('searchPurchase').value.toLowerCase();
    const status = document.getElementById('filterStatus').value;
    const date = document.getElementById('filterDate').value;
    const rows = document.querySelectorAll('#purchaseTable tbody tr');
    let visible = 0;
    let totalAmount = 0;
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const rowStatus = row.dataset.status;
        const rowDate = row.dataset.date;
        const rowTotal = parseFloat(row.dataset.total);
        
        let show = true;
        if (search && !text.includes(search)) show = false;
        if (status !== 'all' && rowStatus !== status) show = false;
        if (date && rowDate !== date) show = false;
        
        row.style.display = show ? '' : 'none';
        if (show) {
            visible++;
            totalAmount += rowTotal;
        }
    });
    
    document.getElementById('purchaseCountDisplay').textContent = visible;
    document.getElementById('totalAmountDisplay').textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');
}

function cancelPurchase(id) {
    if (confirm('Apakah Anda yakin ingin membatalkan pembelian ini?')) {
        const form = document.getElementById('cancelForm');
        form.action = `<?= base_url('purchases') ?>/${id}/cancel`;
        form.submit();
    }
}

function exportPurchases() {
    alert('Fitur export akan segera tersedia');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    filterPurchases();
});
</script>