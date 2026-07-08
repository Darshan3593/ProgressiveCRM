<?php
class ServiceRecord {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Fetch active point rules from points_master
     */
    private function getServicePointRules() {
        $stmt = $this->pdo->prepare("SELECT item_code, points, point_type, amount_unit FROM points_master WHERE category = 'SERVICE' AND status = 'ACTIVE'");
        $stmt->execute();
        $rules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $pointRules = [];
        foreach ($rules as $rule) {
            $pointRules[$rule['item_code']] = $rule;
        }
        return $pointRules;
    }

    /**
     * Calculate points and save the transaction
     */
    public function saveServiceRecord($data, $userId) {
        $rules = $this->getServicePointRules();
        $earnedPoints = 0;

        // 1. Calculate Base Points for the Service Bill Amount
        if (isset($rules['SERVICE_BILL']) && $data['bill_amount'] > 0) {
            $unit = floatval($rules['SERVICE_BILL']['amount_unit']);
            $pts = floatval($rules['SERVICE_BILL']['points']);
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

            // Insert into service_records
            $insertStmt = $this->pdo->prepare("
                INSERT INTO service_records 
                (customer_registration_no, invoice_no, bill_amount, extended_warranty, amc, insurance_renewal, earned_points, created_by, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insertStmt->execute([
                $data['customer_registration_no'], 
                $data['service_invoice_no'], 
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
            
            return $earnedPoints; // Return earned points to the controller

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e; // Throw error back to controller
        }
    }

    public function getCustomerServiceHistory($custId) {
        $stmt = $this->pdo->prepare("
            SELECT invoice_no, bill_amount, extended_warranty, amc, insurance_renewal, earned_points, created_at 
            FROM service_records 
            WHERE customer_registration_no = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$custId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>