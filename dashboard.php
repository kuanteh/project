<?php
require_once __DIR__ . '/dry/init.php';
requireAdmin();

$userCount = (int) $db->query('SELECT COUNT(*) FROM users')->fetchColumn();
$productCount = (int) $db->query('SELECT COUNT(*) FROM products')->fetchColumn();
$orderCount = (int) $db->query('SELECT COUNT(*) FROM orders')->fetchColumn();

$adminTitle = 'Dashboard';
require __DIR__ . '/dry/admin-header.php';
?>

<h1 class="admin-page-title">Dashboard</h1>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-num"><?php echo $userCount; ?></div>
            <div class="stat-label">Users</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-num"><?php echo $productCount; ?></div>
            <div class="stat-label">Products</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-num"><?php echo $orderCount; ?></div>
            <div class="stat-label">Orders</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card p-4 text-center h-100">
            <i class="bi bi-people display-6 mb-2"></i>
            <h5>Manage Users</h5>
            <a href="./manage-user.php" class="btn btn-dark btn-sm mt-2">Access</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 text-center h-100">
            <i class="bi bi-bag display-6 mb-2"></i>
            <h5>Manage Products</h5>
            <a href="./manage-product.php" class="btn btn-dark btn-sm mt-2">Access</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 text-center h-100">
            <i class="bi bi-receipt display-6 mb-2"></i>
            <h5>Manage Orders</h5>
            <a href="./manage-order.php" class="btn btn-dark btn-sm mt-2">Access</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/dry/admin-footer.php'; ?>