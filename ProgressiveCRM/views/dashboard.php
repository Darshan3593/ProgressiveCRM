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
                <a href="index.php?action=dashboard" class="nav-link active">
                    <i class="bi bi-speedometer2 me-3 fs-5"></i> Dashboard
                </a>
            </li>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'Admin'): ?>
            <li>
                <a href="index.php?action=user" class="nav-link">
                    <i class="bi bi-people me-3 fs-5"></i> Users
                </a>
            </li>
            <?php endif; ?>
            <li>
                <a href="index.php?action=create_customer" class="nav-link">
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

    <div class="main-content">
        
        <header class="p-3 bg-white border-bottom shadow-sm d-flex align-items-center">
            <button class="btn btn-light border-0 me-3" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            
            <div class="d-flex justify-content-between align-items-center flex-grow-1">
                <h5 class="mb-0 text-dark fw-semibold">Dashboard Overview</h5>
                <div class="text-muted small d-none d-sm-block">
                    <i class="bi bi-calendar3 me-1"></i> <?php echo date('F j, Y'); ?>
                </div>
            </div>
        </header>

        <div class="container-fluid p-4">
            
            <div class="row mb-4">
                <div class="col-12">
                    <form action="index.php" method="GET" class="card shadow-sm border-0 border-top border-primary border-3">
                        <div class="card-body p-4">
                            <h6 class="text-primary fw-bold mb-3"><i class="bi bi-search me-2"></i>Advanced Customer Search</h6>
                            
                            <input type="hidden" name="action" value="dashboard">
                            
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label small fw-semibold text-muted">Customer Reg No</label>
                                    <input type="text" class="form-control" name="s_cust_no" placeholder="e.g. NC-2026-..." value="<?php echo isset($_GET['s_cust_no']) ? htmlspecialchars($_GET['s_cust_no']) : ''; ?>">
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label small fw-semibold text-muted">Vehicle Reg No</label>
                                    <input type="text" class="form-control" name="s_reg_no" placeholder="e.g. GJ01..." value="<?php echo isset($_GET['s_reg_no']) ? htmlspecialchars($_GET['s_reg_no']) : ''; ?>">
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label small fw-semibold text-muted">VIN Number</label>
                                    <input type="text" class="form-control" name="s_vin" placeholder="Enter VIN" value="<?php echo isset($_GET['s_vin']) ? htmlspecialchars($_GET['s_vin']) : ''; ?>">
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label small fw-semibold text-muted">Mobile Number</label>
                                    <input type="text" class="form-control" name="s_mobile" placeholder="Enter Mobile" value="<?php echo isset($_GET['s_mobile']) ? htmlspecialchars($_GET['s_mobile']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                                <a href="index.php?action=dashboard" class="btn btn-light me-2 px-4">Clear Form</a>
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-search me-2"></i> Search System</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (!empty($search_error)): ?>
                <div class="alert alert-warning shadow-sm border-0"><i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $search_error; ?></div>
            <?php endif; ?>

            <?php if (!empty($search_result)): ?>
                <?php
                    $today = date('Y-m-d');
                    $is_active = (!empty($search_result['warranty_end_date']) && $search_result['warranty_end_date'] >= $today);
                    $warranty_badge = $is_active ? '<span class="badge bg-success px-3 py-2">ACTIVE</span>' : '<span class="badge bg-danger px-3 py-2">EXPIRED / N/A</span>';
                    
                    $cat_color = 'bg-primary';
                    if (strtoupper($search_result['privilege_category']) === 'GREEN') $cat_color = 'bg-success';
                    if (strtoupper($search_result['privilege_category']) === 'YELLOW') $cat_color = 'bg-warning text-dark';
                ?>
                <div class="card shadow border-0 mb-4 border-top border-primary border-4">
                    <div class="card-header bg-white p-4 d-flex justify-content-between align-items-center border-bottom-0">
                        <div>
                            <h4 class="mb-1 fw-bold text-dark"><?php echo htmlspecialchars($search_result['customer_name']); ?></h4>
                            <span class="text-muted"><i class="bi bi-hash"></i> <?php echo htmlspecialchars($search_result['customer_registration_no']); ?></span>
                        </div>
                        <div class="text-end">
                            <span class="badge <?php echo $cat_color; ?> rounded-pill fs-6 px-4 py-2 shadow-sm">
                                <i class="bi bi-star-fill me-1"></i> <?php echo htmlspecialchars($search_result['privilege_category']); ?> TIER
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4 bg-light">
                        <div class="row g-4">
                            <div class="col-md-4 border-end">
                                <h6 class="text-uppercase text-muted fw-bold mb-3"><i class="bi bi-person-lines-fill me-2"></i> Contact Info</h6>
                                <p class="mb-1"><strong>Mobile:</strong> <?php echo htmlspecialchars($search_result['mobile_number']); ?></p>
                                <p class="mb-1"><strong>Type:</strong> <?php echo str_replace('_', ' ', htmlspecialchars($search_result['customer_type'])); ?></p>
                                <p class="mb-1"><strong>Pref. Location:</strong> <?php echo htmlspecialchars($search_result['preferred_location']); ?></p>
                            </div>
                            
                            <div class="col-md-4 border-end">
                                <h6 class="text-uppercase text-muted fw-bold mb-3"><i class="bi bi-car-front-fill me-2"></i> Vehicle Profile</h6>
                                <p class="mb-1"><strong>Model:</strong> <?php echo htmlspecialchars($search_result['vehicle_model']); ?></p>
                                <p class="mb-1"><strong>Reg No:</strong> <?php echo htmlspecialchars($search_result['reg_no']); ?></p>
                                <p class="mb-1"><strong>VIN:</strong> <?php echo htmlspecialchars($search_result['vin_number']); ?></p>
                            </div>
                            
                            <div class="col-md-4">
                                <h6 class="text-uppercase text-muted fw-bold mb-3"><i class="bi bi-shield-check me-2"></i> Status & Points</h6>
                                <p class="mb-2 d-flex align-items-center">
                                    <strong class="me-2">Warranty:</strong> <?php echo $warranty_badge; ?>
                                </p>
                                <?php if($is_active && !empty($search_result['warranty_end_date'])): ?>
                                    <p class="mb-2 small text-muted">Valid until: <?php echo date('d M Y', strtotime($search_result['warranty_end_date'])); ?></p>
                                <?php endif; ?>
                                <h4 class="mt-3 text-primary fw-bold">
                                    <?php echo htmlspecialchars($search_result['total_privilege_points']); ?> <span class="fs-6 text-muted fw-normal">Total Points</span>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <h6 class="fw-bold text-secondary text-uppercase mb-3 px-1 mt-2">Quick Actions</h6>
            <div class="row">

                <?php if (!empty($search_result)): ?>
                    
                    <div class="col-md-6 mb-4">
                        <a href="index.php?action=create_service&cust_id=<?php echo urlencode($search_result['customer_registration_no']); ?>" class="text-decoration-none text-dark">
                            <div class="card dashboard-card shadow-sm border-0 h-100 border-start border-success border-4 transition-hover">
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex align-items-center mb-1">
                                        <div class="bg-success bg-opacity-10 text-success rounded px-3 py-2 me-3">
                                            <i class="bi bi-tools fs-3"></i>
                                        </div>
                                        <h5 class="card-title fw-bold mb-0">Service Form</h5>
                                    </div>
                                    <button class="btn btn-outline-success mt-4 fw-medium w-100 mt-auto">
                                        Open Service Card <i class="bi bi-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 mb-4">
                        <a href="index.php?action=create_bodyshop&cust_id=<?php echo urlencode($search_result['customer_registration_no']); ?>" class="text-decoration-none text-dark">
                            <div class="card dashboard-card shadow-sm border-0 h-100 border-start border-warning border-4 transition-hover">
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex align-items-center mb-1">
                                        <div class="bg-warning bg-opacity-10 text-warning rounded px-3 py-2 me-3">
                                            <i class="bi bi-bandaid fs-3"></i>
                                        </div>
                                        <h5 class="card-title fw-bold mb-0">BodyShop Form</h5>
                                    </div>
                                    <button class="btn btn-outline-warning mt-4 fw-medium w-100 mt-auto">
                                        Open BodyShop Card <i class="bi bi-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>

                <?php else: ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <a href="index.php?action=create_customer" class="text-decoration-none text-dark">
                            <div class="card dashboard-card shadow-sm border-0 h-100 border-start border-primary border-4 transition-hover">
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex align-items-center mb-1">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded px-3 py-2 me-3">
                                            <i class="bi bi-person-plus-fill fs-3"></i>
                                        </div>
                                        <h5 class="card-title fw-bold mb-0">Customer Registration</h5>
                                    </div>
                                    <button class="btn btn-primary mt-4 fw-medium w-100 mt-auto">
                                        Open Form <i class="bi bi-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>

            </div> </div>
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