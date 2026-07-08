<?php require_once 'views/layouts/header.php'; ?>
<body>
<div class="wrapper">
    <?php include 'views/layouts/sidebar.php'; ?> 
    <div class="main-content">
        <header class="p-3 bg-white border-bottom shadow-sm d-flex align-items-center">
            <button class="btn btn-light border-0 me-3" id="sidebarToggle"><i class="bi bi-list fs-4"></i></button>
            <h5 class="mb-0 text-dark fw-semibold">Customer Body Shop History</h5>
        </header>
        
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold"><i class="bi bi-bandaid text-warning me-2"></i> History for: <span class="text-muted fs-5"><?php echo htmlspecialchars($custId); ?></span></h4>
                <a href="index.php?action=create_bodyshop&cust_id=<?php echo urlencode($custId); ?>" class="btn btn-warning fw-medium shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Form
                </a>
            </div>
            
            <div class="card shadow-sm border-0 border-top border-warning border-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="py-3">Invoice No</th>
                                    <th class="py-3">Bill Amount</th>
                                    <th class="py-3 text-center">Ext. Warranty</th>
                                    <th class="py-3 text-center">AMC</th>
                                    <th class="py-3 text-center">Insurance</th>
                                    <th class="px-4 py-3 text-end">Points Earned</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($historyRecords)): ?>
                                    <?php foreach ($historyRecords as $record): ?>
                                        <tr>
                                            <td class="px-4 py-3 text-muted"><?php echo date('d M Y, h:i A', strtotime($record['created_at'])); ?></td>
                                            <td class="py-3 fw-bold"><?php echo htmlspecialchars($record['invoice_no']); ?></td>
                                            <td class="py-3">₹<?php echo number_format($record['bill_amount'], 2); ?></td>
                                            <td class="py-3 text-center"><?php echo $record['extended_warranty'] === 'Yes' ? '<span class="badge bg-success">Yes</span>' : '<span class="text-muted">-</span>'; ?></td>
                                            <td class="py-3 text-center"><?php echo $record['amc'] === 'Yes' ? '<span class="badge bg-success">Yes</span>' : '<span class="text-muted">-</span>'; ?></td>
                                            <td class="py-3 text-center"><?php echo $record['insurance_renewal'] === 'Yes' ? '<span class="badge bg-success">Yes</span>' : '<span class="text-muted">-</span>'; ?></td>
                                            <td class="px-4 py-3 text-end text-success fw-bold">+<?php echo $record['earned_points']; ?> pts</td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="px-4 py-5 text-center text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>No past body shop records found.
                                        </td>
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
        const sidebar = document.getElementById('sidebar'), toggleBtn = document.getElementById('sidebarToggle');
        if (window.innerWidth > 768) sidebar.classList.remove('collapsed');
        if (toggleBtn) toggleBtn.addEventListener('click', () => sidebar.classList.toggle('collapsed'));
    });
</script>
</body>
</html>