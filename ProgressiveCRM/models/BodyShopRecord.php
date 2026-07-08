<?php
class BodyShopRecord {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    private function getPointRules() {
        $stmt = $this->pdo->prepare("SELECT item_code, points, point_type, amount_unit FROM points_master WHERE category = 'SERVICE' AND status = 'ACTIVE'");
        $stmt->execute();
        $rules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $pointRules = [];
        foreach ($rules as $rule) {
            $pointRules[$rule['item_code']] = $rule;
        }
        return $pointRules;
    }

    public function saveRecord($data, $userId) {
        $rules = $this->getPointRules();
        $earnedPoints = 0;

        // 1. Calculate Base Points for the Body Shop Bill Amount (BODY_PAINT_JOB)
        if (isset($rules['BODY_PAINT_JOB']) && $data['bill_amount'] > 0) {
            $unit = floatval($rules['BODY_PAINT_JOB']['amount_unit']);
            $pts = floatval($rules['BODY_PAINT_JOB']['points']);
            if ($unit > 0) {
                $earnedPoints += floor($data['bill_amount'] / $unit) * $pts; 
            }
        }
        
        // 2. Add Fixed Bonus Points
        if ($data['extended_warranty'] === 'Yes' && isset($rules['EXTENDED_WARRANTY'])) {
            $earnedPoints += floatval($rules['EXTENDED_WARRANTY']['points']);
        }
        if ($data['amc'] === 'Yes' && isset($rules['AMC'])) {
            $earnedPoints += floatval($rules['AMC']['points']);
        }
        if ($data['insurance_renewal'] === 'Yes' && isset($rules['INSURANCE_RENEWAL'])) {
            $earnedPoints += floatval($rules['INSURANCE_RENEWAL']['points']);
        }

        // 3. Execute Database Transaction
        try {
            $this->pdo->beginTransaction();

            // Insert into bodyshop_records
            $insertStmt = $this->pdo->prepare("
                INSERT INTO bodyshop_records 
                (customer_registration_no, bodyshop_invoice_no, bodyshop_bill_amount, extended_warranty, amc, insurance_renewal, earned_points, created_by, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insertStmt->execute([
                $data['customer_registration_no'], 
                $data['bodyshop_invoice_no'], 
                $data['bill_amount'], 
                $data['extended_warranty'], 
                $data['amc'], 
                $data['insurance_renewal'], 
                $earnedPoints, 
                $userId, 
                date('Y-m-d H:i:s')
            ]);

            // Update total_privilege_points in customers table
            $updateStmt = $this->pdo->prepare("
                UPDATE customers 
                SET total_privilege_points = total_privilege_points + ? 
                WHERE customer_registration_no = ?
            ");
            $updateStmt->execute([$earnedPoints, $data['customer_registration_no']]);

            $this->pdo->commit();
            return $earnedPoints;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function getCustomerHistory($custId) {
        $stmt = $this->pdo->prepare("
            SELECT bodyshop_invoice_no as invoice_no, bodyshop_bill_amount as bill_amount, extended_warranty, amc, insurance_renewal, earned_points, created_at 
            FROM bodyshop_records 
            WHERE customer_registration_no = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$custId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>