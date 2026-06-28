<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Product extends BaseController
{
    public function index()
    {
        $model = new ProductModel();
        $data = [
            'title' => 'Manajemen Produk',
            'products' => $model->getAllWithStock()
        ];
        
        return view('layout/main', [
            'title' => 'Manajemen Produk',
            'content' => view('products/index', $data)
        ]);
    }

    public function create()
    {
        return view('layout/main', [
            'title' => 'Tambah Produk',
            'content' => view('products/form', ['product' => null, 'isEdit' => false])
        ]);
    }

    public function store()
    {
        $model = new ProductModel();
        
        $rules = [
            'product_code' => 'required|is_unique[products.product_code]',
            'name' => 'required|min_length[3]',
            'price' => 'required|numeric',
            'stock' => 'required|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'product_code' => $this->request->getPost('product_code'),
            'name' => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'price' => $this->request->getPost('price'),
            'description' => $this->request->getPost('description'),
            'stock' => $this->request->getPost('stock'),
            'min_stock' => $this->request->getPost('min_stock') ?: 5
        ];
        
        if ($model->createWithStock($data)) {
            session()->setFlashdata('success', 'Produk berhasil ditambahkan');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan produk');
        }
        
        return redirect()->to('/products');
    }

    public function edit($id)
    {
        $model = new ProductModel();
        $product = $model->getWithStock($id);
        
        if (!$product) {
            session()->setFlashdata('error', 'Produk tidak ditemukan');
            return redirect()->to('/products');
        }
        
        return view('layout/main', [
            'title' => 'Edit Produk',
            'content' => view('products/form', ['product' => $product, 'isEdit' => true])
        ]);
    }

    public function update($id)
    {
        $model = new ProductModel();
        
        $rules = [
            'name' => 'required|min_length[3]',
            'price' => 'required|numeric',
            'stock' => 'required|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'price' => $this->request->getPost('price'),
            'description' => $this->request->getPost('description'),
            'stock' => $this->request->getPost('stock'),
            'min_stock' => $this->request->getPost('min_stock') ?: 5
        ];
        
        if ($model->updateWithStock($id, $data)) {
            session()->setFlashdata('success', 'Produk berhasil diupdate');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate produk');
        }
        
        return redirect()->to('/products');
    }

    public function delete($id)
    {
        $model = new ProductModel();
        
        if ($model->deleteWithStock($id)) {
            session()->setFlashdata('success', 'Produk berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus produk');
        }
        
        return redirect()->to('/products');
    }

    public function detail($id)
    {
        $model = new ProductModel();
        $product = $model->getWithStock($id);
        
        if ($product) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $product
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Produk tidak ditemukan'
        ]);
    }
}