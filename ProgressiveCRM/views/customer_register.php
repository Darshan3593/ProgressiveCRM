<?php require_once 'views/layouts/header.php'; ?>
<body>
<div class="wrapper">
    
    <nav id="sidebar" class="sidebar shadow-sm collapsed d-md-flex">
       <div class="p-4 border-bottom d-flex align-items-center">
            <img src="assets/Logo.png" alt="ProgressiveCRM Logo" class="img-fluid me-2" style="max-height: 50px;">
       </div>
        <div class="p-3 text-center border-bottom bg-light">
            <div class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($_SESSION['full_name']); ?></div>
            <span class="badge bg-primary rounded-pill px-3 py-1"><?php echo htmlspecialchars($_SESSION['user_type']); ?></span>
        </div>
        <ul class="nav nav-pills flex-column mb-auto py-2">
            <li class="nav-item">
                <a href="index.php?action=dashboard" class="nav-link">
                    <i class="bi bi-speedometer2 me-3 fs-5"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="index.php?action=user" class="nav-link">
                    <i class="bi bi-people me-3 fs-5"></i> Users
                </a>
            </li>
            <li>
                <a href="index.php?action=create_customer" class="nav-link active">
                    <i class="bi bi-person-plus me-3 fs-5"></i> New Customer
                </a>
            </li>
        </ul>
        <div class="p-3 border-top mt-auto">
            <a href="index.php?action=logout" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center py-2 fw-medium">
                <i class="bi bi-box-arrow-right me-2"></i> Sign Out
            </a>
        </div>
    </nav>

    <div class="main-content w-100">
        <header class="p-3 bg-white border-bottom shadow-sm d-flex align-items-center">
            <button class="btn btn-light border-0 me-3" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            <div class="d-flex justify-content-between align-items-center flex-grow-1">
                <h5 class="mb-0 text-dark fw-semibold">Customer Registration</h5>
                <span class="badge bg-primary">Active User: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>
        </header>

        <div class="container-fluid p-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                <?php foreach ($errors as $error) echo "<li>$error</li>"; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill me-2"></i><?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?action=create_customer" method="POST">
                        
                        <h6 class="text-primary border-bottom pb-2 mb-4">Personal Details</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Customer Type *</label>
                                <select class="form-select border-primary" name="customer_type" required>
                                    <option value="New_Customer" <?php echo (isset($_POST['customer_type']) && $_POST['customer_type'] == 'New_Customer') ? 'selected' : 'selected'; ?>>New Customer</option>
                                    <option value="Existing_Customer" <?php echo (isset($_POST['customer_type']) && $_POST['customer_type'] == 'Existing_Customer') ? 'selected' : ''; ?>>Existing (Old) Customer</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Customer Name *</label>
                                <input type="text" class="form-control" name="customer_name" value="<?php echo isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : ''; ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Mobile Number *</label>
                                <input type="text" class="form-control" name="mobile_number" value="<?php echo isset($_POST['mobile_number']) ? htmlspecialchars($_POST['mobile_number']) : ''; ?>" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Address *</label>
                                <textarea class="form-control" name="address" rows="2" required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Pin Code *</label>
                                <input type="text" class="form-control" name="pin_code" value="<?php echo isset($_POST['pin_code']) ? htmlspecialchars($_POST['pin_code']) : ''; ?>" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Preferred Location *</label>
                                <input type="text" class="form-control" name="preferred_location" placeholder="e.g., SG Highway, Motera" value="<?php echo isset($_POST['preferred_location']) ? htmlspecialchars($_POST['preferred_location']) : ''; ?>" required>
                            </div>
                        </div>

                        <h6 class="text-primary border-bottom pb-2 mb-4">Vehicle & Sales Details</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Vehicle Model *</label>
                                <select class="form-select" name="vehicle_model" required>
                                    <option value="" disabled selected>Select Model...</option>
                                    <?php foreach ($car_models as $model): ?>
                                        <option value="<?php echo $model['item_code']; ?>" <?php echo (isset($_POST['vehicle_model']) && $_POST['vehicle_model'] == $model['item_code']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($model['item_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Registration No *</label>
                                <input type="text" class="form-control" name="reg_no" value="<?php echo isset($_POST['reg_no']) ? htmlspecialchars($_POST['reg_no']) : ''; ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">VIN Number *</label>
                                <input type="text" class="form-control" name="vin_number" value="<?php echo isset($_POST['vin_number']) ? htmlspecialchars($_POST['vin_number']) : ''; ?>" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Purchase Date *</label>
                                <input type="date" class="form-control" name="vehicle_purchase_date" value="<?php echo isset($_POST['vehicle_purchase_date']) ? htmlspecialchars($_POST['vehicle_purchase_date']) : ''; ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Invoice Number *</label>
                                <input type="text" class="form-control" name="invoice_number" value="<?php echo isset($_POST['invoice_number']) ? htmlspecialchars($_POST['invoice_number']) : ''; ?>" required>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label text-success fw-bold">Extended Warranty? *</label>
                                <select class="form-select border-success" name="extended_warranty" required>
                                    <option value="NO" <?php echo (isset($_POST['extended_warranty']) && $_POST['extended_warranty'] == 'NO') ? 'selected' : ''; ?>>NO (0 Points)</option>
                                    <option value="YES" <?php echo (isset($_POST['extended_warranty']) && $_POST['extended_warranty'] == 'YES') ? 'selected' : ''; ?>>YES (+200 Points)</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Warranty Start Date *</label>
                                <input type="date" class="form-control" name="warranty_start_date" value="<?php echo isset($_POST['warranty_start_date']) ? htmlspecialchars($_POST['warranty_start_date']) : ''; ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Warranty Expiry Date *</label>
                                <input type="date" class="form-control" name="warranty_end_date" value="<?php echo isset($_POST['warranty_end_date']) ? htmlspecialchars($_POST['warranty_end_date']) : ''; ?>" required>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end mt-3 border-top pt-4">
                            <button type="reset" class="btn btn-light me-2">Clear Form</button>
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i> Register Customer</button>
                        </div>
                    </form>

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
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });
    });
</script>
</body>
</html>