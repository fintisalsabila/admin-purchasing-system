<div class="form-page animate__animated animate__fadeIn">
    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas <?= $isEdit ? 'fa-edit' : 'fa-plus-circle' ?>"></i> <?= $isEdit ? 'Edit Produk' : 'Tambah Produk' ?></h2>
            <span class="page-subtitle"><?= $isEdit ? 'Perbarui informasi produk' : 'Isi data produk baru' ?></span>
        </div>
        <a href="<?= base_url('products') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="form-card">
        <form action="<?= $isEdit ? base_url("products/{$product['id']}") : base_url('products') ?>" 
              method="POST" 
              class="form"
              id="productForm">
            <?php if ($isEdit): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="product_code">Kode Produk <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-icon"><i class="fas fa-barcode"></i></span>
                        <input type="text" 
                               id="product_code" 
                               name="product_code" 
                               value="<?= old('product_code', $product['product_code'] ?? '') ?>" 
                               <?= $isEdit ? 'readonly' : 'required' ?>
                               class="form-control <?= $isEdit ? 'readonly' : '' ?>"
                               placeholder="PRD-001">
                    </div>
                    <?php if ($isEdit): ?>
                        <small class="form-text text-muted"><i class="fas fa-info-circle"></i> Kode produk tidak dapat diubah</small>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nama Produk <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-icon"><i class="fas fa-tag"></i></span>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="<?= old('name', $product['name'] ?? '') ?>" 
                               class="form-control" 
                               required
                               placeholder="Masukkan nama produk">
                    </div>
                </div>
            </div>
            
            <div class="form-row row-2">
                <div class="form-group">
                    <label for="category">Kategori</label>
                    <div class="input-group">
                        <span class="input-group-icon"><i class="fas fa-folder"></i></span>
                        <input type="text" 
                               id="category" 
                               name="category" 
                               value="<?= old('category', $product['category'] ?? '') ?>" 
                               class="form-control"
                               placeholder="Elektronik, Fashion, dll">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="price">Harga <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-icon"><i class="fas fa-rupiah-sign"></i></span>
                        <input type="number" 
                               id="price" 
                               name="price" 
                               value="<?= old('price', $product['price'] ?? '') ?>" 
                               class="form-control" 
                               required 
                               min="0"
                               placeholder="0">
                    </div>
                </div>
            </div>
            
            <div class="form-row row-2">
                <div class="form-group">
                    <label for="stock">Stok <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-icon"><i class="fas fa-cubes"></i></span>
                        <input type="number" 
                               id="stock" 
                               name="stock" 
                               value="<?= old('stock', $product['stock'] ?? 0) ?>" 
                               class="form-control" 
                               required 
                               min="0"
                               placeholder="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="min_stock">Minimal Stok</label>
                    <div class="input-group">
                        <span class="input-group-icon"><i class="fas fa-flag"></i></span>
                        <input type="number" 
                               id="min_stock" 
                               name="min_stock" 
                               value="<?= old('min_stock', $product['min_stock'] ?? 5) ?>" 
                               class="form-control" 
                               min="0"
                               placeholder="5">
                    </div>
                    <small class="form-text text-muted"><i class="fas fa-info-circle"></i> Peringatan muncul jika stok di bawah nilai ini</small>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" 
                              name="description" 
                              class="form-control" 
                              rows="4"
                              placeholder="Deskripsi produk..."><?= old('description', $product['description'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> <?= $isEdit ? 'Update Produk' : 'Simpan Produk' ?>
                </button>
                <a href="<?= base_url('products') ?>" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

@media (max-width: 768px) {
    .row-2 {
        grid-template-columns: 1fr;
        gap: 0;
    }
}
</style>