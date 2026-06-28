<?php

namespace App\Controllers;

use App\Models\PurchaseModel;
use App\Models\ProductModel;

class Purchase extends BaseController
{
    public function index()
    {
        $model = new PurchaseModel();
        $productModel = new ProductModel();
        
        $data = [
            'title' => 'Manajemen Pembelian',
            'purchases' => $model->getAllWithProduct(),
            'products' => $productModel->getAllWithStock()
        ];
        
        return view('layout/main', [
            'title' => 'Manajemen Pembelian',
            'content' => view('purchases/index', $data)
        ]);
    }

    public function create()
    {
        $productModel = new ProductModel();
        $data = [
            'products' => $productModel->getAllWithStock()
        ];
        
        return view('layout/main', [
            'title' => 'Tambah Pembelian',
            'content' => view('purchases/form', $data)
        ]);
    }

    public function store()
    {
        $model = new PurchaseModel();
        $productModel = new ProductModel();
        
        // Get POST data
        $productId = (int)$this->request->getPost('product_id');
        $quantity = (int)$this->request->getPost('quantity');
        $customerName = trim($this->request->getPost('customer_name'));
        $customerPhone = trim($this->request->getPost('customer_phone'));
        
        // Validation rules
        $rules = [
            'product_id' => 'required|numeric',
            'quantity' => 'required|numeric|greater_than[0]',
            'customer_name' => 'required|min_length[2]|max_length[100]',
            'customer_phone' => 'permit_empty|max_length[20]'
        ];
        
        $messages = [
            'product_id' => [
                'required' => 'Silakan pilih produk',
                'numeric' => 'Produk tidak valid'
            ],
            'quantity' => [
                'required' => 'Jumlah harus diisi',
                'numeric' => 'Jumlah harus berupa angka',
                'greater_than' => 'Jumlah harus lebih dari 0'
            ],
            'customer_name' => [
                'required' => 'Nama customer harus diisi',
                'min_length' => 'Nama customer minimal 2 karakter',
                'max_length' => 'Nama customer terlalu panjang (maksimal 100 karakter)'
            ]
        ];
        
        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        // Check product exists
        $product = $productModel->getWithStock($productId);
        if (!$product) {
            session()->setFlashdata('error', 'Produk tidak ditemukan');
            return redirect()->back()->withInput();
        }
        
        // Check stock
        if ($product['stock'] < $quantity) {
            session()->setFlashdata('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product['stock']);
            return redirect()->back()->withInput();
        }
        
        // Prepare data
        $data = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'total_price' => $product['price'] * $quantity,
            'customer_name' => ucwords(strtolower($customerName)),
            'customer_phone' => $customerPhone
        ];
        
        // Save purchase
        try {
            $purchaseId = $model->createPurchase($data);
            if ($purchaseId) {
                session()->setFlashdata('success', 'Pembelian berhasil ditambahkan');
                return redirect()->to('/purchases');
            } else {
                session()->setFlashdata('error', 'Gagal menambahkan pembelian');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function cancel($id)
    {
        $model = new PurchaseModel();
        
        try {
            if ($model->cancelPurchase((int)$id)) {
                session()->setFlashdata('success', 'Pembelian berhasil dibatalkan');
            } else {
                session()->setFlashdata('error', 'Gagal membatalkan pembelian');
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        
        return redirect()->to('/purchases');
    }

    public function detail($id)
    {
        $model = new PurchaseModel();
        $purchase = $model->getWithProduct((int)$id);
        
        if ($purchase) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $purchase
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Pembelian tidak ditemukan'
        ]);
    }
}