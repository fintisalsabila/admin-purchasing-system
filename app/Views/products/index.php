<div class="products-page animate__animated animate__fadeIn">
    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-box"></i> Manajemen Produk</h2>
            <span class="page-subtitle">Kelola data produk toko Anda</span>
        </div>
        <div class="page-header-right">
            <button class="btn btn-outline-primary" onclick="exportProducts()">
                <i class="fas fa-file-export"></i> Export
            </button>
            <a href="<?= base_url('products/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="filter-section">
        <div class="filter-group">
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" id="searchProduct" placeholder="Cari produk..." onkeyup="filterProducts()">
            </div>
            <select id="filterCategory" onchange="filterProducts()" class="form-select">
                <option value="">Semua Kategori</option>
                <?php 
                $categories = array_unique(array_column($products, 'category'));
                foreach ($categories as $cat): 
                    if ($cat): ?>
                        <option value="<?= $cat ?>"><?= $cat ?></option>
                <?php endif; endforeach; ?>
            </select>
            <select id="filterStock" onchange="filterProducts()" class="form-select">
                <option value="">Semua Stok</option>
                <option value="low">Stok Rendah</option>
                <option value="empty">Habis</option>
                <option value="available">Tersedia</option>
            </select>
        </div>
        <div class="filter-stats">
            <span>Menampilkan <strong id="productCountDisplay">0</strong> produk</span>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="products-grid" id="productsGrid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card" 
                     data-name="<?= strtolower($product['name']) ?>"
                     data-category="<?= $product['category'] ?? '' ?>"
                     data-stock="<?= $product['stock'] ?? 0 ?>">
                    <div class="product-card-header">
                        <div class="product-status">
                            <?php if (($product['stock'] ?? 0) <= 0): ?>
                                <span class="status-badge danger">Habis</span>
                            <?php elseif (($product['stock'] ?? 0) <= ($product['min_stock'] ?? 0)): ?>
                                <span class="status-badge warning">Stok Rendah</span>
                            <?php else: ?>
                                <span class="status-badge success">Tersedia</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-actions">
                            <a href="<?= base_url("products/{$product['id']}/edit") ?>" class="btn-icon" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteProduct(<?= $product['id'] ?>)" class="btn-icon danger" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-card-body">
                        <div class="product-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <h4 class="product-name"><?= $product['name'] ?></h4>
                        <span class="product-code"><?= $product['product_code'] ?></span>
                        <div class="product-category">
                            <i class="fas fa-tag"></i> <?= $product['category'] ?? 'Uncategorized' ?>
                        </div>
                        <div class="product-price">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                        <div class="product-stock">
                            <div class="stock-bar">
                                <div class="stock-fill" 
                                     style="width: <?= min(100, (($product['stock'] ?? 0) / 50) * 100) ?>%;
                                          background: <?= ($product['stock'] ?? 0) <= 0 ? '#ef4444' : (($product['stock'] ?? 0) <= ($product['min_stock'] ?? 0) ? '#f59e0b' : '#22c55e') ?>">
                                </div>
                            </div>
                            <div class="stock-info">
                                <span>Stok: <strong><?= $product['stock'] ?? 0 ?></strong></span>
                                <span>Min: <?= $product['min_stock'] ?? 5 ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>Belum Ada Produk</h3>
                <p>Mulai tambahkan produk pertama Anda</p>
                <a href="<?= base_url('products/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Produk
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display:none;">
    <input type="hidden" name="_method" value="DELETE">
</form>

<script>
function filterProducts() {
    const search = document.getElementById('searchProduct').value.toLowerCase();
    const category = document.getElementById('filterCategory').value;
    const stock = document.getElementById('filterStock').value;
    const cards = document.querySelectorAll('.product-card');
    let visible = 0;
    
    cards.forEach(card => {
        const name = card.dataset.name;
        const cat = card.dataset.category;
        const stockVal = parseInt(card.dataset.stock);
        
        let show = true;
        if (search && !name.includes(search)) show = false;
        if (category && cat !== category) show = false;
        if (stock === 'low' && stockVal > 0 && stockVal > (card.dataset.minStock || 5)) show = false;
        if (stock === 'empty' && stockVal > 0) show = false;
        if (stock === 'available' && stockVal <= 0) show = false;
        
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    
    document.getElementById('productCountDisplay').textContent = visible;
}

function exportProducts() {
    alert('Fitur export akan segera tersedia');
}

function deleteProduct(id) {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        const form = document.getElementById('deleteForm');
        form.action = `<?= base_url('products') ?>/${id}`;
        form.submit();
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    filterProducts();
});
</script>