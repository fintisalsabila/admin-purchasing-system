<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseModel extends Model
{
    protected $table = 'purchases';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'invoice_number', 'product_id', 'quantity', 
        'total_price', 'customer_name', 'customer_phone',
        'status', 'cancelled_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    // GET ALL WITH PRODUCT - MENGGUNAKAN QUERY MANUAL
    public function getAllWithProduct()
    {
        $query = "SELECT p.*, pr.name as product_name, pr.product_code, pr.price as product_price 
                  FROM purchases p 
                  JOIN products pr ON p.product_id = pr.id 
                  ORDER BY p.created_at DESC";
        
        $result = $this->db->query($query);
        return $result->getResultArray();
    }

    // GET ACTIVE WITH PRODUCT
    public function getActiveWithProduct()
    {
        $query = "SELECT p.*, pr.name as product_name, pr.product_code, pr.price as product_price 
                  FROM purchases p 
                  JOIN products pr ON p.product_id = pr.id 
                  WHERE p.status = 'active' 
                  ORDER BY p.created_at DESC";
        
        $result = $this->db->query($query);
        return $result->getResultArray();
    }

    // GET WITH PRODUCT BY ID
    public function getWithProduct($id)
    {
        $id = (int)$id;
        $query = "SELECT p.*, pr.name as product_name, pr.product_code, pr.price as product_price 
                  FROM purchases p 
                  JOIN products pr ON p.product_id = pr.id 
                  WHERE p.id = ?";
        
        $result = $this->db->query($query, [$id]);
        return $result->getRowArray();
    }

    // CREATE PURCHASE
    public function createPurchase($data)
    {
        $this->db->transBegin();
        
        try {
            // Generate invoice number
            $countQuery = "SELECT COUNT(*) as total FROM purchases";
            $countResult = $this->db->query($countQuery);
            $count = $countResult->getRowArray()['total'] + 1;
            $invoiceNumber = 'INV-' . date('Y') . date('m') . str_pad($count, 4, '0', STR_PAD_LEFT);
            
            // Insert purchase
            $insertQuery = "INSERT INTO purchases (invoice_number, product_id, quantity, total_price, customer_name, customer_phone) 
                           VALUES (?, ?, ?, ?, ?, ?)";
            
            $this->db->query($insertQuery, [
                $invoiceNumber,
                $data['product_id'],
                $data['quantity'],
                $data['total_price'],
                $data['customer_name'],
                $data['customer_phone']
            ]);
            
            $purchaseId = $this->db->insertID();
            
            // Update stock
            $updateStockQuery = "UPDATE stocks SET quantity = quantity - ? WHERE product_id = ?";
            $this->db->query($updateStockQuery, [
                $data['quantity'],
                $data['product_id']
            ]);
            
            $this->db->transCommit();
            
            return $purchaseId;
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    // CANCEL PURCHASE
    public function cancelPurchase($id)
    {
        $this->db->transBegin();
        
        try {
            $id = (int)$id;
            
            // Get purchase data
            $selectQuery = "SELECT * FROM purchases WHERE id = ? AND status = 'active'";
            $result = $this->db->query($selectQuery, [$id]);
            $purchase = $result->getRowArray();
            
            if (!$purchase) {
                return false;
            }
            
            // Update status
            $updateQuery = "UPDATE purchases SET status = 'cancelled', cancelled_at = NOW() WHERE id = ?";
            $this->db->query($updateQuery, [$id]);
            
            // Return stock
            $updateStockQuery = "UPDATE stocks SET quantity = quantity + ? WHERE product_id = ?";
            $this->db->query($updateStockQuery, [
                $purchase['quantity'],
                $purchase['product_id']
            ]);
            
            $this->db->transCommit();
            
            return true;
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    // GET STATS
    public function getStats()
    {
        // Total purchases
        $totalQuery = "SELECT COUNT(*) as total, COALESCE(SUM(total_price), 0) as total_amount 
                       FROM purchases WHERE status = 'active'";
        $totalResult = $this->db->query($totalQuery);
        $total = $totalResult->getRowArray();
            
        // Today purchases
        $todayQuery = "SELECT COUNT(*) as total, COALESCE(SUM(total_price), 0) as total_amount 
                       FROM purchases 
                       WHERE status = 'active' AND DATE(created_at) = CURDATE()";
        $todayResult = $this->db->query($todayQuery);
        $today = $todayResult->getRowArray();
            
        // Cancelled purchases
        $cancelledQuery = "SELECT COUNT(*) as total FROM purchases WHERE status = 'cancelled'";
        $cancelledResult = $this->db->query($cancelledQuery);
        $cancelled = $cancelledResult->getRowArray();
            
        return [
            'total' => $total ?: ['total' => 0, 'total_amount' => 0],
            'today' => $today ?: ['total' => 0, 'total_amount' => 0],
            'cancelled' => $cancelled ?: ['total' => 0]
        ];
    }

    // GET MONTHLY STATS
    public function getMonthlyStats($month = null, $year = null)
    {
        $month = $month ?? date('m');
        $year = $year ?? date('Y');
        
        $query = "SELECT DATE(created_at) as date, COUNT(*) as total, COALESCE(SUM(total_price), 0) as amount 
                  FROM purchases 
                  WHERE status = 'active' 
                  AND MONTH(created_at) = ? 
                  AND YEAR(created_at) = ? 
                  GROUP BY DATE(created_at)";
        
        $result = $this->db->query($query, [(int)$month, (int)$year]);
        return $result->getResultArray();
    }
}