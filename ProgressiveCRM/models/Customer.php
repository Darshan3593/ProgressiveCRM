<?php
// models/Customer.php

class Customer {
    private $conn;
    private $table_name = "customers";

    // Customer Properties
    public $customer_type;
    public $customer_name;
    public $address;
    public $pin_code;
    public $vehicle_model;
    public $reg_no;
    public $vin_number;
    public $mobile_number;
    public $vehicle_purchase_date;
    public $invoice_number;
    public $extended_warranty;
    public $warranty_start_date;
    public $warranty_end_date; 
    public $preferred_location;
    
    // Calculated Properties
    public $customer_registration_no;
    public $privilege_points_earned;
    public $extended_warranty_points;
    public $total_privilege_points;
    public $privilege_category;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetch dynamic car models for the dropdown
    public function getCarModels() {
        $query = "SELECT item_code, item_name, points FROM points_master WHERE category = 'CAR_MODEL' AND status = 'ACTIVE'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get specific points for a model securely from DB
    public function getModelPoints($model_code) {
        $query = "SELECT points FROM points_master WHERE item_code = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$model_code]);
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['points'];
        }
        return 0;
    }

    // Save Customer
    public function register($created_by) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (customer_registration_no, customer_type, customer_name, address, pin_code, vehicle_model, 
                  reg_no, vin_number, mobile_number, vehicle_purchase_date, invoice_number, extended_warranty, 
                  warranty_start_date, warranty_end_date,
                  preferred_location, privilege_points_earned, extended_warranty_points, total_privilege_points, 
                  privilege_category, created_by, created_on) 
                  VALUES 
                  (:reg_no_gen, :customer_type, :name, :address, :pin, :model, 
                  :reg, :vin, :mobile, :purchase_date, :invoice, :warranty,
                  :warranty_start_date, :warranty_end_date, 
                  :location, :base_points, :ext_points, :total_points, 
                  :category, :created_by, :created_on)";

        $stmt = $this->conn->prepare($query);
        $created_on = date('Y-m-d');

        // Bind Variables
        $stmt->bindParam(":reg_no_gen", $this->customer_registration_no);
        $stmt->bindParam(":customer_type", $this->customer_type);
        $stmt->bindParam(":name", $this->customer_name);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":pin", $this->pin_code);
        $stmt->bindParam(":model", $this->vehicle_model);
        $stmt->bindParam(":reg", $this->reg_no);
        $stmt->bindParam(":vin", $this->vin_number);
        $stmt->bindParam(":mobile", $this->mobile_number);
        $stmt->bindParam(":purchase_date", $this->vehicle_purchase_date);
        $stmt->bindParam(":invoice", $this->invoice_number);
        $stmt->bindParam(":warranty", $this->extended_warranty);
        $stmt->bindParam(":warranty_start_date", $this->warranty_start_date);
        $stmt->bindParam(":warranty_end_date", $this->warranty_end_date);
        $stmt->bindParam(":location", $this->preferred_location);
        $stmt->bindParam(":base_points", $this->privilege_points_earned);
        $stmt->bindParam(":ext_points", $this->extended_warranty_points);
        $stmt->bindParam(":total_points", $this->total_privilege_points);
        $stmt->bindParam(":category", $this->privilege_category);
        $stmt->bindParam(":created_by", $created_by);
        $stmt->bindParam(":created_on", $created_on);

        return $stmt->execute();
    }

    public function searchCustomerAdvanced($params) {
        $conditions = [];
        $binds = [];

        if (!empty($params['customer_registration_no'])) {
            $conditions[] = "customer_registration_no = :cust_reg_no";
            $binds[':cust_reg_no'] = $params['customer_registration_no'];
        }
        if (!empty($params['reg_no'])) {
            $conditions[] = "reg_no = :reg_no";
            $binds[':reg_no'] = $params['reg_no'];
        }
        if (!empty($params['vin_number'])) {
            $conditions[] = "vin_number = :vin";
            $binds[':vin'] = $params['vin_number'];
        }
        if (!empty($params['mobile_number'])) {
            $conditions[] = "mobile_number = :mobile";
            $binds[':mobile'] = $params['mobile_number'];
        }

        if (empty($conditions)) {
            return null;
        }

        $query = "SELECT * FROM " . $this->table_name . " WHERE " . implode(" AND ", $conditions) . " LIMIT 1";
        $stmt = $this->conn->prepare($query);
        
        foreach ($binds as $key => $value) {
            $stmt->bindValue($key, htmlspecialchars(strip_tags($value)));
        }
        
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null; 
    }
}
?>