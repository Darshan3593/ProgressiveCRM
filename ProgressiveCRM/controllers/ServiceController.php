<?php
require_once 'models/ServiceRecord.php';
// Make sure this path correctly points to the file you just showed me!
require_once 'config/database.php'; 

class ServiceController {
    private $model;

    public function __construct() {
        // 1. Create a new instance of your Database class
        $database = new Database();
        
        // 2. Call your specific method to get the PDO connection
        $pdo = $database->getConnection();
        
        // 3. Safety check
        if ($pdo === null) {
            die("Database connection failed! Check your database credentials.");
        }

        // 4. Pass the valid PDO connection to the model
        $this->model = new ServiceRecord($pdo);
    }

    /**
     * Display the Create Service Form
     */
    public function create() {
        // Grab the customer ID from the URL if it exists
        $custId = isset($_GET['cust_id']) ? trim($_GET['cust_id']) : '';
        
        // Load the view
        require 'views/create_service.php';
    }

    /**
     * Handle Form Submission
     */

    /**
     * Handle Form Submission
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect and sanitize input data
            $data = [
                'customer_registration_no' => trim($_POST['customer_registration_no']),
                'service_invoice_no'       => trim($_POST['service_invoice_no']),
                'bill_amount'              => floatval($_POST['service_bill_amount']),
                'extended_warranty'        => $_POST['extended_warranty'],
                'amc'                      => $_POST['amc'],
                'insurance_renewal'        => $_POST['insurance_renewal']
            ];
            
            $userId = $_SESSION['user_id'] ?? 1;

            try {
                // Pass data to the model
                $earnedPoints = $this->model->saveServiceRecord($data, $userId);
                
                // Store success details in an array in the session
                $_SESSION['service_success'] = [
                    'earned_points' => $earnedPoints,
                    'invoice_no'    => $data['service_invoice_no'],
                    'bill_amount'   => $data['bill_amount']
                ];

                // Redirect back to the create service form for this customer
                $redirectUrl = "index.php?action=create_service&cust_id=" . urlencode($data['customer_registration_no']);
                header("Location: " . $redirectUrl);
                exit;

            } catch (Exception $e) {
                // On failure, load the view again with an error message
                $error_msg = "Database Error: " . $e->getMessage();
                $custId = $data['customer_registration_no']; 
                require 'views/create_service.php';
            }
        }
    }
    
    public function store_() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect and sanitize input data
            $data = [
                'customer_registration_no' => trim($_POST['customer_registration_no']),
                'service_invoice_no'       => trim($_POST['service_invoice_no']),
                'bill_amount'              => floatval($_POST['service_bill_amount']),
                'extended_warranty'        => $_POST['extended_warranty'],
                'amc'                      => $_POST['amc'],
                'insurance_renewal'        => $_POST['insurance_renewal']
            ];
            
            $userId = $_SESSION['user_id'] ?? 1; // Fallback to 1 if session is lost

            try {
                // Pass data to the model
                $earnedPoints = $this->model->saveServiceRecord($data, $userId);
                
                // On success, redirect to dashboard with a success message
                $_SESSION['success_msg'] = "Service record saved! Customer earned {$earnedPoints} points.";
                header("Location: index.php?action=dashboard");
                exit;

            } catch (Exception $e) {
                // On failure, load the view again with an error message
                $error_msg = "Database Error: " . $e->getMessage();
                $custId = $data['customer_registration_no']; // Preserve the ID in the form
                require 'views/create_service.php';
            }
        }
    }

    /**
     * Display the Service History Page
     */
    public function history() {
        $custId = isset($_GET['cust_id']) ? trim($_GET['cust_id']) : '';
        
        if (empty($custId)) {
            $_SESSION['error_msg'] = "Customer ID is required to view history.";
            header("Location: index.php?action=dashboard");
            exit;
        }

        // Fetch history from the model
        $historyRecords = $this->model->getCustomerServiceHistory($custId);
        
        // Load the history view
        require 'views/service_history.php';
    }
}
?>