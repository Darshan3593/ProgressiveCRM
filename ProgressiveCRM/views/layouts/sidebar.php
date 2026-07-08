<nav id="sidebar" class="sidebar shadow-sm collapsed d-md-flex">
    <div class="p-4 border-bottom d-flex align-items-center">
        <img src="assets/Logo.png" alt="ProgressiveCRM Logo" class="img-fluid me-2" style="max-height: 50px;">
    </div>
    
    <div class="p-3 text-center border-bottom bg-light">
        <div class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?></div>
        <span class="badge bg-primary rounded-pill px-3 py-1"><?php echo htmlspecialchars($_SESSION['user_type'] ?? 'Staff'); ?></span>
    </div>

    <ul class="nav nav-pills flex-column mb-auto py-2">
        <li class="nav-item">
            <a href="index.php?action=dashboard" class="nav-link">
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