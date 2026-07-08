<?php
// index.php
session_start();

// --- ADD THESE CACHE-CONTROL HEADERS ---
// Tell the browser never to cache the pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// ---------------------------------------

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Make sure your database config is required somewhere here if not handled inside the controllers!
// require_once 'config/database.php'; 

require_once 'controllers/UserController.php';
require_once 'controllers/CustomerController.php';
require_once 'controllers/ServiceController.php';
require_once 'controllers/BodyShopController.php';

$userController = new UserController();
$customerController = new CustomerController();


$serviceController = new ServiceController(); 
$bodyShopController = new BodyShopController(); 


$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'login';

switch ($action) {
    case 'register':
        $userController->registerAction();
        break;
        
    case 'user':
        $userController->listAction();
        break;

    case 'customer_register':
    case 'create_customer':
        $customerController->createAction();
        break;  
            
    case 'dashboard':
        $userController->dashboardAction();
        break;
        
    case 'logout':
        $userController->logoutAction();
        break;

    // ---------------------------------------------------------
    // NEW SERVICE ROUTES
    // ---------------------------------------------------------
    case 'create_service':
        // Shows the empty form (User clicks the button on dashboard)
        $serviceController->create();
        break;

    case 'store_service':
        // Handles the form submission and saves to the database
        $serviceController->store();
        break;

    case 'service_history':             
        $serviceController->history();  
        break;                             
    // ---------------------------------------------------------

    case 'create_bodyshop':
        $bodyShopController->create();
        break;
    case 'store_bodyshop':
        $bodyShopController->store();
        break;
    case 'bodyshop_history':
        $bodyShopController->history();
        break;


    case 'login':
    default:
        $userController->loginAction();
        break;
}
?>