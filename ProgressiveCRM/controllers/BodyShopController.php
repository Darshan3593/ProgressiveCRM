<?php
require_once 'models/BodyShopRecord.php';
require_once 'config/database.php'; 

class BodyShopController {
    private $model;

    public function __construct() {
        $database = new Database();
        $pdo = $database->getConnection();
        
        if ($pdo === null) {
            die("Database connection failed! Check your database credentials.");
        }
        $this->model = new BodyShopRecord($pdo);
    }

    public function create() {
        $custId = isset($_GET['cust_id']) ? trim($_GET['cust_id']) : '';
        require 'views/create_bodyshop.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'customer_registration_no' => trim($_POST['customer_registration_no']),
                'bodyshop_invoice_no'      => trim($_POST['bodyshop_invoice_no']),
                'bill_amount'              => floatval($_POST['bodyshop_bill_amount']),
                'extended_warranty'        => $_POST['extended_warranty'],
                'amc'                      => $_POST['amc'],
                'insurance_renewal'        => $_POST['insurance_renewal']
            ];
            
            $userId = $_SESSION['user_id'] ?? 1;

            try {
                $earnedPoints = $this->model->saveRecord($data, $userId);
                
                $_SESSION['bodyshop_success'] = [
                    'earned_points' => $earnedPoints,
                    'invoice_no'    => $data['bodyshop_invoice_no'],
                    'bill_amount'   => $data['bill_amount']
                ];

                $redirectUrl = "index.php?action=create_bodyshop&cust_id=" . urlencode($data['customer_registration_no']);
                header("Location: " . $redirectUrl);
                exit;

            } catch (Exception $e) {
                $error_msg = "Database Error: " . $e->getMessage();
                $custId = $data['customer_registration_no']; 
                require 'views/create_bodyshop.php';
            }
        }
    }

    public function history() {
        $custId = isset($_GET['cust_id']) ? trim($_GET['cust_id']) : '';
        if (empty($custId)) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $historyRecords = $this->model->getCustomerHistory($custId);
        require 'views/bodyshop_history.php';
    }
}
?>