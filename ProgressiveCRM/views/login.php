<?php require_once 'views/layouts/header.php'; ?>
<body>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <img src="assets/Logo.png" alt="ProgressiveCRM Logo" class="img-fluid mb-3" style="max-height: 100px;">
                
                <H6 class="text-muted">Sign in to your account</H6>
            </div>
            
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0 ps-3">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?action=login" method="POST" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required autofocus>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-medium">Login</button>
                        </div>
                    </form>
                    
                    <!-- <div class="text-center mt-4">
                        <p class="text-muted mb-0">Don't have an account? <a href="index.php?action=register" class="text-decoration-none fw-semibold">Register here</a></p>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>