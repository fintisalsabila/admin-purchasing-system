<div class="form-page animate__animated animate__fadeIn">
    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-plus-circle"></i> Tambah Pembelian</h2>
            <span class="page-subtitle">Input transaksi pembelian baru</span>
        </div>
        <a href="<?= base_url('purchases') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="form-card">
        <!-- === PERBAIKAN: Tambahkan error summary === -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('purchases') ?>" method="POST" class="form" id="purchaseForm" novalidate>
            <!-- Product Selection -->
            <div class="form-row">
                <div class="form-group">
                    <label for="product_id">Pilih Produk <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-icon"><i class="fas fa-box"></i></span>
                        <select id="product_id" name="product_id" class="form-control" required onchange="updateProductInfo()">
                            <option value="">-- Cari dan pilih produk --</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['id'] ?>" 
                                        data-price="<?= $product['price'] ?>"
                                        data-stock="<?= $product['stock'] ?>"
                                        data-name="<?= $product['name'] ?>"
                                        <?= old('product_id') == $product['id'] ? 'selected' : '' ?>>
                                    <?= $product['name'] ?> (Stok: <?= $product['stock'] ?>) - Rp <?= number_format($product['price'], 0, ',', '.') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if (isset($errors['product_id'])): ?>
                        <small class="text-danger"><?= $errors['product_id'] ?></small>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Product Preview -->
            <div class="product-preview" id="productPreview" style="display:none;">
                <div class="preview-card">
                    <div class="preview-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="preview-info">
                        <h4 id="previewName">-</h4>
                        <div class="preview-details">
                            <span><i class="fas fa-rupiah-sign"></i> <span id="previewPrice">0</span></span>
                            <span><i class="fas fa-cubes"></i> Stok: <span id="previewStock">0</span></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-row row-2">
                <div class="form-group">
                    <label for="quantity">Jumlah <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-icon"><i class="fas fa-sort-numeric-up"></i></span>
                        <input type="number" 
                               id="quantity" 
                               name="quantity" 
                               value="<?= old('quantity', 1) ?>" 
                               class="form-control" 
                               required 
                               min="1"
                               onchange="calculateTotal()">
                    </div>
                    <?php if (isset($errors['quantity'])): ?>
                        <small class="text-danger"><?= $errors['quantity'] ?></small>
                    <?php endif; ?>
                    <div class="stock-info" id="stockInfo">
                        <i class="fas fa-info-circle"></i> Stok tersedia: <span id="stockAvailable">-</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Total Harga</label>
                    <div class="total-display" id="totalDisplay">
                        <span class="total-amount">Rp 0</span>
                        <span class="total-label">Total Pembayaran</span>
                    </div>
                    <input type="hidden" id="total_price" name="total_price" value="0">
                </div>
            </div>
            
            <div class="form-row row-2">
                <div class="form-group">
                    <label for="customer_name">Nama Customer <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-icon"><i class="fas fa-user"></i></span>
                        <input type="text" 
                               id="customer_name" 
                               name="customer_name" 
                               value="<?= old('customer_name') ?>" 
                               class="form-control <?= isset($errors['customer_name']) ? 'is-invalid' : '' ?>" 
                               required
                               minlength="2"
                               maxlength="100"
                               placeholder="Masukkan nama customer"
                               oninput="validateCustomerName(this)">
                    </div>
                    <?php if (isset($errors['customer_name'])): ?>
                        <small class="text-danger"><?= $errors['customer_name'] ?></small>
                    <?php endif; ?>
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i> Minimal 2 karakter, maksimal 100 karakter
                    </small>
                    <div class="char-counter" id="nameCounter">0/100</div>
                </div>
                
                <div class="form-group">
                    <label for="customer_phone">No. HP Customer</label>
                    <div class="input-group">
                        <span class="input-group-icon"><i class="fas fa-phone"></i></span>
                        <input type="text" 
                               id="customer_phone" 
                               name="customer_phone" 
                               value="<?= old('customer_phone') ?>" 
                               class="form-control" 
                               placeholder="08xx-xxxx-xxxx"
                               maxlength="20">
                    </div>
                    <?php if (isset($errors['customer_phone'])): ?>
                        <small class="text-danger"><?= $errors['customer_phone'] ?></small>
                    <?php endif; ?>
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i> Contoh: 081234567890
                    </small>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                    <i class="fas fa-save"></i> Proses Pembelian
                </button>
                <a href="<?= base_url('purchases') ?>" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let selectedProduct = null;

function updateProductInfo() {
    const select = document.getElementById('product_id');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        selectedProduct = {
            id: selectedOption.value,
            name: selectedOption.dataset.name,
            price: parseInt(selectedOption.dataset.price),
            stock: parseInt(selectedOption.dataset.stock)
        };
        
        document.getElementById('productPreview').style.display = 'block';
        document.getElementById('previewName').textContent = selectedProduct.name;
        document.getElementById('previewPrice').textContent = selectedProduct.price.toLocaleString('id-ID');
        document.getElementById('previewStock').textContent = selectedProduct.stock;
        document.getElementById('stockAvailable').textContent = selectedProduct.stock;
        
        calculateTotal();
    } else {
        selectedProduct = null;
        document.getElementById('productPreview').style.display = 'none';
        document.getElementById('stockAvailable').textContent = '-';
        document.getElementById('totalDisplay').innerHTML = `
            <span class="total-amount">Rp 0</span>
            <span class="total-label">Total Pembayaran</span>
        `;
        document.getElementById('total_price').value = '0';
    }
}

function calculateTotal() {
    if (!selectedProduct) {
        return;
    }
    
    const quantity = parseInt(document.getElementById('quantity').value) || 0;
    const total = selectedProduct.price * quantity;
    
    document.getElementById('totalDisplay').innerHTML = `
        <span class="total-amount">Rp ${total.toLocaleString('id-ID')}</span>
        <span class="total-label">${quantity} x Rp ${selectedProduct.price.toLocaleString('id-ID')}</span>
    `;
    document.getElementById('total_price').value = total;
    
    const stockInfo = document.getElementById('stockInfo');
    const submitBtn = document.getElementById('submitBtn');
    
    if (quantity > selectedProduct.stock) {
        stockInfo.className = 'stock-info text-danger';
        stockInfo.innerHTML = `<i class="fas fa-exclamation-triangle"></i> Stok tidak mencukupi! Tersedia: ${selectedProduct.stock}`;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Stok Tidak Cukup';
    } else {
        stockInfo.className = 'stock-info text-success';
        const remaining = selectedProduct.stock - quantity;
        stockInfo.innerHTML = `<i class="fas fa-check-circle"></i> Stok tersedia: ${selectedProduct.stock} (akan tersisa ${remaining})`;
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Proses Pembelian';
    }
}

// === PERBAIKAN: Validasi customer name real-time ===
function validateCustomerName(input) {
    const value = input.value;
    const counter = document.getElementById('nameCounter');
    const length = value.length;
    
    counter.textContent = `${length}/100`;
    
    if (length > 0 && length < 2) {
        counter.style.color = '#ef4444';
    } else if (length >= 2 && length <= 100) {
        counter.style.color = '#22c55e';
    } else if (length > 100) {
        counter.style.color = '#ef4444';
    } else {
        counter.style.color = '#94a3b8';
    }
}

// === PERBAIKAN: Form validation sebelum submit ===
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('purchaseForm');
    
    form.addEventListener('submit', function(e) {
        const customerName = document.getElementById('customer_name');
        const nameLength = customerName.value.trim().length;
        
        if (nameLength < 2) {
            e.preventDefault();
            alert('Nama customer minimal 2 karakter!');
            customerName.focus();
            customerName.classList.add('is-invalid');
            return false;
        }
        
        if (nameLength > 100) {
            e.preventDefault();
            alert('Nama customer terlalu panjang! Maksimal 100 karakter.');
            customerName.focus();
            customerName.classList.add('is-invalid');
            return false;
        }
        
        return true;
    });
    
    // Remove invalid class on focus
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.classList.remove('is-invalid');
        });
    });
    
    updateProductInfo();
});
</script>

<style>
.row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.is-invalid {
    border-color: #ef4444 !important;
}

.text-danger {
    color: #ef4444;
    font-size: 0.85rem;
    margin-top: 0.25rem;
}

.char-counter {
    text-align: right;
    font-size: 0.8rem;
    color: #94a3b8;
    margin-top: 0.2rem;
}

@media (max-width: 768px) {
    .row-2 {
        grid-template-columns: 1fr;
        gap: 0;
    }
}
</style>