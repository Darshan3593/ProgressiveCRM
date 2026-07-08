<?php
// controllers/CustomerController.php

require_once 'config/database.php';
require_once 'models/Customer.php';

class CustomerController {
    
    public function createAction() {
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $database = new Database();
        $db = $database->getConnection();
        $customer = new Customer($db);

        // Fetch models for the view dropdown
        $models_stmt = $customer->getCarModels();
        $car_models = $models_stmt->fetchAll(PDO::FETCH_ASSOC);

        $errors = [];
        $success = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Assign Post Data
            $customer->customer_type = htmlspecialchars(strip_tags($_POST['customer_type']));
            $customer->customer_name = htmlspecialchars(strip_tags($_POST['customer_name']));
            $customer->address = htmlspecialchars(strip_tags($_POST['address']));
            $customer->pin_code = htmlspecialchars(strip_tags($_POST['pin_code']));
            $customer->vehicle_model = htmlspecialchars(strip_tags($_POST['vehicle_model']));
            $customer->reg_no = htmlspecialchars(strip_tags($_POST['reg_no']));
            $customer->vin_number = htmlspecialchars(strip_tags($_POST['vin_number']));
            $customer->mobile_number = htmlspecialchars(strip_tags($_POST['mobile_number']));
            $customer->vehicle_purchase_date = htmlspecialchars(strip_tags($_POST['vehicle_purchase_date']));
            $customer->invoice_number = htmlspecialchars(strip_tags($_POST['invoice_number']));
            $customer->extended_warranty = htmlspecialchars(strip_tags($_POST['extended_warranty']));
            $customer->warranty_start_date = htmlspecialchars(strip_tags($_POST['warranty_start_date']));
            $customer->warranty_end_date = htmlspecialchars(strip_tags($_POST['warranty_end_date']));
            $customer->preferred_location = htmlspecialchars(strip_tags($_POST['preferred_location']));

            // --- BUSINESS LOGIC ---
            
            // 1. Generate Unique Registration Number: "NC-YEAR-LAST4VIN-RANDOM"
            $year = date('Y');
            $vin_suffix = substr($customer->vin_number, -4);
            $random = rand(1000, 9999);
            $customer->customer_registration_no = "NC-{$year}-{$vin_suffix}-{$random}";

            // 2. Calculate Points
            $customer->privilege_points_earned = $customer->getModelPoints($customer->vehicle_model);
            $customer->extended_warranty_points = ($customer->extended_warranty === 'YES') ? 200 : 0;
            $customer->total_privilege_points = $customer->privilege_points_earned + $customer->extended_warranty_points;

            // 3. Set Default Privilege Category to GREEN
            $customer->privilege_category = 'GREEN';

            // Save to DB using the logged-in user's username
            if (empty($errors)) {
                if ($customer->register($_SESSION['username'])) {
                    $success = "Customer successfully registered! Registration No: " . $customer->customer_registration_no;
                    $_POST = []; // Clear form on success
                } else {
                    $errors[] = "Failed to save customer. Please check your data.";
                }
            }
        }

        $page_title = "ProgressiveCRM - Register Customer";
        require_once 'views/customer_register.php';
    }
}
?>