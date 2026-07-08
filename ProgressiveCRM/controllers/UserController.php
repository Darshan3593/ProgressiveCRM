<?php
require_once 'config/database.php';
require_once 'models/User.php';

class UserController {
    
    public function registerAction() {
        $errors = [];
        $success = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $database = new Database();
            $db = $database->getConnection();
            $user = new User($db);

            $user->username  = trim($_POST['username']);
            $user->fname     = trim($_POST['fname']);
            $user->Lname     = trim($_POST['Lname']);
            $user->user_type = trim($_POST['user_type']);
            $user->emailid   = trim($_POST['emailid']);
            $user->password  = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if (empty($user->username) || empty($user->fname) || empty($user->Lname) || empty($user->user_type) || empty($user->emailid) || empty($user->password)) {
                $errors[] = "All fields are required.";
            }
            if (!filter_var($user->emailid, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }
            if (strlen($user->password) < 6) {
                $errors[] = "Password must be at least 6 characters long.";
            }
            if ($user->password !== $confirm_password) {
                $errors[] = "Passwords do not match.";
            }

            if (empty($errors)) {
                if ($user->register()) {
                    $success = "Registration successful! User account created successfully.";
                    $_POST = []; // Clear form on success
                } else {
                    $errors[] = "Username is already taken. Please choose another one.";
                }
            }
        }

        $page_title = "ProgressiveCRM - Register Account";
        require_once 'views/register.php';
    }
    
    public function loginAction() {
        // Redirect to dashboard if already logged in
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $database = new Database();
            $db = $database->getConnection();
            $user = new User($db);

            $user->username = trim($_POST['username']);
            $user->password = $_POST['password'];

            // Validation
            if (empty($user->username) || empty($user->password)) {
                $errors[] = "Both username and password are required.";
            }

            if (empty($errors)) {
                if ($user->login()) {
                    // Create Session Variables
                    $_SESSION['user_id']   = $user->id;
                    $_SESSION['username']  = $user->username;
                    $_SESSION['full_name'] = $user->fname . ' ' . $user->Lname;
                    $_SESSION['user_type'] = $user->user_type;
                    
                    header("Location: index.php?action=dashboard");
                    exit;
                } else {
                    $errors[] = "Invalid username or password.";
                }
            }
        }

        $page_title = "ProgressiveCRM - Login";
        require_once 'views/login.php';
    }

    public function dashboardAction() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        require_once 'models/Customer.php';
        $database = new Database();
        $db = $database->getConnection();
        $customerModel = new Customer($db);

        $search_result = null;
        $search_error = "";
        $search_params = [];

        if (!empty($_GET['s_cust_no'])) $search_params['customer_registration_no'] = trim($_GET['s_cust_no']);
        if (!empty($_GET['s_reg_no'])) $search_params['reg_no'] = trim($_GET['s_reg_no']);
        if (!empty($_GET['s_vin'])) $search_params['vin_number'] = trim($_GET['s_vin']);
        if (!empty($_GET['s_mobile'])) $search_params['mobile_number'] = trim($_GET['s_mobile']);

        if (!empty($search_params)) {
            $search_result = $customerModel->searchCustomerAdvanced($search_params);
            
            if (!$search_result) {
                $search_error = "No customer found matching those exact details.";
            }
        }

        $page_title = "ProgressiveCRM - Dashboard";
        require_once 'views/dashboard.php';
    }

   public function logoutAction() {
        // 1. Unset all of the session variables
        $_SESSION = array();

        // 2. Destroy the session cookie in the browser
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // 3. Destroy the server session completely
        session_destroy();
        
        // 4. Redirect to login
        header("Location: index.php?action=login");
        exit;
    }

    // --- ADD THIS TO controllers/UserController.php ---
    public function listAction() {
        // Security Check: Kick user out if not logged in OR if they are NOT an Admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $database = new Database();
        $db = $database->getConnection();
        $userModel = new User($db);

        // Fetch the users
        $stmt = $userModel->getAllUsers();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $page_title = "ProgressiveCRM - User Management";
        require_once 'views/user_list.php';
    }
}
?>