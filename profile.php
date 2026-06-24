<?php
require_once __DIR__ . '/dry/init.php';
// check session 看有没有user 是login 的
// 如果user 是已经login 的话 - 就放空（） 继续给user 看website
requireLogin();

/**
 * 如果user 是登录了的话
 * currenUser 就会去把当前login 了的user 的资料（database 或者是session 里面的）
 * 存进 $user
 */
$user = currentUser();
$success = flashGet('success');

// 读取用户订单
$orderStmt = $db->prepare('SELECT * FROM orders WHERE user_id = :uid ORDER BY purchase_date DESC');
$orderStmt->execute([':uid' => $user['user_id']]);
$orders = $orderStmt->fetchAll();

$pageTitle = 'My Profile';
require __DIR__ . '/dry/header.php';
?>

<div class="profile-wrap">
    <h1 class="mb-4" style="font-family:Bungee,sans-serif; letter-spacing:.08em;">MY ACCOUNT</h1>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo ($success); ?></div>
    <?php endif; ?>

    <!-- Profile Section -->
    <div class="profile-card">
        <h2 class="h5 text-uppercase fw-bold mb-3">Profile</h2>
        <p><strong>Username:</strong> <?php echo ($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo ($user['email']); ?></p>
        <p><strong>Address:</strong> <?php echo ($user['address'] ?? '—'); ?></p>
        <p><strong>Phone:</strong> <?php echo ($user['phone'] ?? '—'); ?></p>
        <div class="d-flex gap-2 mt-3">
            <a href="./profile-edit.php" class="btn btn-outline-dark btn-sm">Edit Profile</a>
            <a href="./change-password.php" class="btn btn-outline-dark btn-sm">Change Password</a>
        </div>
    </div>

    <!-- Orders Section -->
    <div class="profile-card">
        <h2 class="h5 text-uppercase fw-bold mb-3">Orders</h2>

        <?php if (count($orders) === 0): ?>
            <p class="text-muted">No Order Yet</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Items</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <?php
                            $itemStmt = $db->prepare('SELECT op.quantity, p.product_name, ps.size
                                FROM order_products op
                                JOIN products p ON p.product_id = op.product_id
                                JOIN product_sizes ps ON ps.size_id = op.size_id
                                WHERE op.order_id = :oid');
                            $itemStmt->execute([':oid' => $order['order_id']]);
                            $items = $itemStmt->fetchAll();
                            ?>
                            <tr>
                                <td>#<?php echo (int) $order['order_id']; ?></td>
                                <td><?php echo e(date('d M Y', strtotime($order['purchase_date']))); ?></td>
                                <td><span class="status-badge status-<?php echo e($order['status']); ?>"><?php echo e($order['status']); ?></span></td>
                                <td><?php echo formatPrice($order['total_price']); ?></td>
                                <td>
                                    <?php foreach ($items as $item): ?>
                                        <div class="small"><?php echo e($item['product_name']); ?> (<?php echo e($item['size']); ?>) x<?php echo (int) $item['quantity']; ?></div>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/dry/footer.php'; ?>