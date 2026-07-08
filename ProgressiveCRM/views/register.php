<?php require_once 'views/layouts/header.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

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
                <a href="index.php?action=customer_register" class="nav-link">
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
                <h5 class="mb-0 text-dark fw-semibold">User Account Registration</h5>
                <span class="badge bg-primary">Active User: <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'System'; ?></span>
            </div>
        </header>

        <div class="container-fluid p-4">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 p-md-5">
                            
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                    <ul class="mb-0 ps-3">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                    <i class="bi bi-check-circle-fill me-2"></i><?php echo $success; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form action="index.php" method="POST" novalidate>
                                
                                <h6 class="text-primary border-bottom pb-2 mb-4">Personal Details</h6>
                                <div class="row mb-4">
                                    <div class="col-md-4 mb-3">
                                        <label for="fname" class="form-label">First Name *</label>
                                        <input type="text" class="form-control" id="fname" name="fname" value="<?php echo isset($_POST['fname']) ? htmlspecialchars($_POST['fname']) : ''; ?>" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="Lname" class="form-label">Last Name *</label>
                                        <input type="text" class="form-control" id="Lname" name="Lname" value="<?php echo isset($_POST['Lname']) ? htmlspecialchars($_POST['Lname']) : ''; ?>" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="emailid" class="form-label">Email ID *</label>
                                        <input type="email" class="form-control" id="emailid" name="emailid" value="<?php echo isset($_POST['emailid']) ? htmlspecialchars($_POST['emailid']) : ''; ?>" required>
                                    </div>
                                </div>

                                <h6 class="text-primary border-bottom pb-2 mb-4">Account Security & Role</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="user_type" class="form-label">User Type *</label>
                                        <select class="form-select border-primary" id="user_type" name="user_type" required>
                                            <option value="" disabled selected>Choose Type...</option>
                                            <option value="Admin" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                                            <option value="Consultant" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] == 'Consultant') ? 'selected' : ''; ?>>Consultant</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="username" class="form-label">Username (Primary Key) *</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password *</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="confirm_password" class="form-label">Confirm Password *</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm_password">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 border-top pt-4">
                                    <p class="text-muted mb-3 mb-md-0">
                                        Already have an account? <a href="index.php?action=login" class="text-decoration-none fw-semibold">Login here</a>
                                    </p>
                                    <div class="d-flex">
                                        <button type="reset" class="btn btn-light me-2 px-4">Clear Form</button>
                                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-person-plus me-2"></i> Register Account</button>
                                    </div>
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
        // Sidebar Toggle Logic
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        
        if (window.innerWidth > 768) {
            sidebar.classList.remove('collapsed');
        }
        
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });

        // Password Visibility Toggle Logic
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });
    });
</script>

</body>
</html>