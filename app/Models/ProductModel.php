<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_code', 'name', 'category', 'price', 'description'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // GET ALL WITH STOCK
    public function getAllWithStock()
    {
        $query = "SELECT p.*, COALESCE(s.quantity, 0) as stock, COALESCE(s.min_stock, 5) as min_stock 
                  FROM products p 
                  LEFT JOIN stocks s ON p.id = s.product_id 
                  ORDER BY p.id DESC";
        
        $result = $this->db->query($query);
        return $result->getResultArray();
    }

    // GET WITH STOCK BY ID
    public function getWithStock($id)
    {
        $id = (int)$id;
        $query = "SELECT p.*, COALESCE(s.quantity, 0) as stock, COALESCE(s.min_stock, 5) as min_stock 
                  FROM products p 
                  LEFT JOIN stocks s ON p.id = s.product_id 
                  WHERE p.id = ?";
        
        $result = $this->db->query($query, [$id]);
        return $result->getRowArray();
    }

    // UPDATE STOCK
    public function updateStock($productId, $quantity)
    {
        $productId = (int)$productId;
        $quantity = (int)$quantity;
        
        $query = "UPDATE stocks SET quantity = quantity + ? WHERE product_id = ?";
        return $this->db->query($query, [$quantity, $productId]);
    }

    // CREATE WITH STOCK
    public function createWithStock($data)
    {
        $this->db->transBegin();
        
        try {
            // Insert product
            $insertProduct = "INSERT INTO products (product_code, name, category, price, description) 
                             VALUES (?, ?, ?, ?, ?)";
            
            $this->db->query($insertProduct, [
                $data['product_code'],
                $data['name'],
                $data['category'] ?? null,
                $data['price'],
                $data['description'] ?? null
            ]);
            
            $productId = $this->db->insertID();
            
            // Insert stock
            $insertStock = "INSERT INTO stocks (product_id, quantity, min_stock) VALUES (?, ?, ?)";
            $this->db->query($insertStock, [
                $productId,
                (int)($data['stock'] ?? 0),
                (int)($data['min_stock'] ?? 5)
            ]);
            
            $this->db->transCommit();
            
            return true;
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    // UPDATE WITH STOCK
    public function updateWithStock($id, $data)
    {
        $this->db->transBegin();
        
        try {
            $id = (int)$id;
            
            // Update product
            $updateProduct = "UPDATE products SET name = ?, category = ?, price = ?, description = ? WHERE id = ?";
            $this->db->query($updateProduct, [
                $data['name'],
                $data['category'] ?? null,
                $data['price'],
                $data['description'] ?? null,
                $id
            ]);
            
            // Update stock
            $updateStock = "UPDATE stocks SET quantity = ?, min_stock = ? WHERE product_id = ?";
            $this->db->query($updateStock, [
                (int)($data['stock'] ?? 0),
                (int)($data['min_stock'] ?? 5),
                $id
            ]);
            
            $this->db->transCommit();
            
            return true;
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    // DELETE WITH STOCK
    public function deleteWithStock($id)
    {
        $this->db->transBegin();
        
        try {
            $id = (int)$id;
            
            // Delete stock first
            $deleteStock = "DELETE FROM stocks WHERE product_id = ?";
            $this->db->query($deleteStock, [$id]);
            
            // Delete product
            $deleteProduct = "DELETE FROM products WHERE id = ?";
            $this->db->query($deleteProduct, [$id]);
            
            $this->db->transCommit();
            
            return true;
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    // GET LOW STOCK PRODUCTS
    public function getLowStockProducts()
    {
        $query = "SELECT p.*, s.quantity as stock, s.min_stock 
                  FROM products p 
                  JOIN stocks s ON p.id = s.product_id 
                  WHERE s.quantity <= s.min_stock";
        
        $result = $this->db->query($query);
        return $result->getResultArray();
    }

    public function getTotalProducts()
    {
        $query = "SELECT COUNT(*) as total FROM products";
        $result = $this->db->query($query);
        return $result->getRowArray()['total'] ?? 0;
    }
}