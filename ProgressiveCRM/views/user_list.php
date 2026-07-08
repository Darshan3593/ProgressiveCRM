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
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'Admin'): ?>
            <li>
                <a href="index.php?action=user" class="nav-link active">
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
                <h5 class="mb-0 text-dark fw-semibold">User Management</h5>
                <span class="badge bg-light text-dark border"><i class="bi bi-shield-lock me-1"></i> Admin Access</span>
            </div>
        </header>

        <div class="container-fluid p-4">
            
            <div class="card shadow-sm border-0 border-top border-primary border-4">
                <div class="card-header bg-white p-4 d-flex justify-content-between align-items-center border-bottom-0">
                    <div>
                        <h5 class="mb-1 fw-bold text-dark"><i class="bi bi-people-fill text-primary me-2"></i>System Users</h5>
                        <p class="text-muted small mb-0">Manage CRM access and user roles.</p>
                    </div>
                    <a href="index.php?action=register" class="btn btn-primary px-4 shadow-sm">
                        <i class="bi bi-plus-lg me-2"></i> Add New User
                    </a>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3">Full Name</th>
                                    <th class="py-3">Username</th>
                                    <th class="py-3">Email Address</th>
                                    <th class="py-3">Role</th>
                                    <th class="pe-4 py-3 text-end">Registered On</th>
                                </tr>
                            </thead>
                            <tbody class="border-top">
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $u): ?>
                                        <?php 
                                            // Dynamic badge coloring based on role
                                            $badge_class = 'bg-secondary';
                                            if ($u['user_type'] === 'Admin') $badge_class = 'bg-danger';
                                            if ($u['user_type'] === 'Manager') $badge_class = 'bg-warning text-dark';
                                            if ($u['user_type'] === 'Consultant') $badge_class = 'bg-info text-dark';
                                        ?>
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($u['fname'] . ' ' . $u['Lname']); ?></div>
                                            </td>
                                            <td class="py-3 text-muted">@<?php echo htmlspecialchars($u['username']); ?></td>
                                            <td class="py-3"><?php echo htmlspecialchars($u['emailid']); ?></td>
                                            <td class="py-3">
                                                <span class="badge <?php echo $badge_class; ?> rounded-pill px-3 py-1 fw-medium shadow-sm">
                                                    <?php echo htmlspecialchars($u['user_type']); ?>
                                                </span>
                                            </td>
                                            <td class="pe-4 py-3 text-end text-muted small">
                                                <?php echo date('M d, Y', strtotime($u['created_at'])); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">No users found in the system.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });
    });
</script>
</body>
</html>