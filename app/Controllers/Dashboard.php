<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\PurchaseModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $purchaseModel = new PurchaseModel();
        $productModel = new ProductModel();
        
        $data = [
            'title' => 'Dashboard',
            'stats' => $purchaseModel->getStats(),
            'recentPurchases' => $purchaseModel->getActiveWithProduct(),
            'products' => $productModel->getAllWithStock(),
            'lowStockProducts' => $productModel->getLowStockProducts()
        ];
        
        return view('layout/main', [
            'title' => 'Dashboard',
            'content' => view('dashboard/index', $data)
        ]);
    }
}