<?php 
require_once 'views/layouts/header.php'; 

// Check for success data in session and clear it immediately so it doesn't persist on refresh
$successData = null;
if (isset($_SESSION['service_success'])) {
    $successData = $_SESSION['service_success'];
    unset($_SESSION['service_success']);
}
?>
<body>

<div class="wrapper">
    <?php include 'views/layouts/sidebar.php'; ?> 

    <div class="main-content">
        <header class="p-3 bg-white border-bottom shadow-sm d-flex align-items-center">
            <button class="btn btn-light border-0 me-3" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            <div class="d-flex justify-content-between align-items-center flex-grow-1">
                <h5 class="mb-0 text-dark fw-semibold">Create Service Record</h5>
            </div>
        </header>

        <div class="container-fluid p-4">
            
            <?php if (isset($error_msg)): ?>
                <div class="alert alert-danger shadow-sm border-0">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    
                    <?php if ($successData): ?>
                        <div class="alert alert-success shadow-sm border-0 border-start border-success border-4 mb-4 p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="alert-heading fw-bold mb-0"><i class="bi bi-check-circle-fill me-2"></i>Service Recorded Successfully!</h5>
                                <span class="badge bg-success bg-opacity-25 text-success px-3 py-2 border border-success border-opacity-25">
                                    Invoice: <?php echo htmlspecialchars($successData['invoice_no']); ?>
                                </span>
                            </div>
                            <p class="mb-3 text-dark">The service record has been saved and points have been calculated.</p>
                            
                            <div class="bg-white rounded p-3 d-flex align-items-center border border-success border-opacity-25">
                                <div class="bg-success text-white rounded-circle d-flex justify-content-center align-items-center me-3 shadow-sm" style="width: 60px; height: 60px;">
                                    <span class="fs-4 fw-bold">+<?php echo htmlspecialchars($successData['earned_points']); ?></span>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold text-success">Privilege Points Earned!</h5>
                                    <p class="mb-0 small text-muted">Based on a bill amount of <strong>₹<?php echo number_format($successData['bill_amount'], 2); ?></strong> and selected add-ons.</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="card shadow border-0 border-top border-success border-4 mb-4">
                        <div class="card-header bg-white p-4 border-bottom-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1 fw-bold text-dark"><i class="bi bi-tools text-success me-2"></i> New Service Entry</h4>
                            </div>
                            <?php if (!empty($custId)): ?>
                                <a href="index.php?action=service_history&cust_id=<?php echo urlencode($custId); ?>" class="btn btn-outline-primary btn-sm shadow-sm">
                                    <i class="bi bi-clock-history me-1"></i> View Service History
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-body p-4 bg-light">
                            <form action="index.php" method="POST">
                                <input type="hidden" name="action" value="store_service">
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted small">Customer Registration No</label>
                                    <input type="text" class="form-control bg-white" name="customer_registration_no" 
                                           value="<?php echo htmlspecialchars($custId ?? ''); ?>" readonly required>
                                </div>

                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted small">Service Invoice No</label>
                                        <input type="text" class="form-control" name="service_invoice_no" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted small">Service Bill Amount (₹)</label>
                                        <input type="number" step="0.01" class="form-control" name="service_bill_amount" required>
                                    </div>
                                </div>

                                <hr class="my-4 text-muted">

                                <div class="row g-4 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-muted small">Extended Warranty</label>
                                        <select class="form-select" name="extended_warranty" required>
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-muted small">AMC</label>
                                        <select class="form-select" name="amc" required>
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-muted small">Insurance Renewal</label>
                                        <select class="form-select" name="insurance_renewal" required>
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                                    <a href="index.php?action=dashboard" class="btn btn-light me-2 px-4">Cancel</a>
                                    <button type="submit" class="btn btn-success px-4">Save & Calculate Points</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');

        if (window.innerWidth > 768) {
            sidebar.classList.remove('collapsed');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
            });
        }
    });
</script>
</body>
</html>