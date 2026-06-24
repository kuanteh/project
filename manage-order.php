<?php
require_once __DIR__ . '/dry/init.php';
requireAdmin();

// Update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = (int) ($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    if (in_array($status, ['Pending', 'Processing', 'Shipped', 'Completed'], true)) {
        $upd = $db->prepare('UPDATE orders SET status = :status WHERE order_id = :id');
        $upd->execute([':status' => $status, ':id' => $orderId]);
        flashSet('success', 'Order #' . $orderId . ' status updated.');
    }
    header('Location: ./manage-order.php');
    exit;
}

// Delete order
if (isset($_GET['delete'])) {
    $oid = (int) $_GET['delete'];
    $db->prepare('DELETE FROM order_products WHERE order_id = :id')->execute([':id' => $oid]);
    $db->prepare('DELETE FROM orders WHERE order_id = :id')->execute([':id' => $oid]);
    flashSet('success', 'Order deleted.');
    header('Location: ./manage-order.php');
    exit;
}

$orders = $db->query('SELECT o.*, u.username FROM orders o JOIN users u ON u.user_id = o.user_id ORDER BY o.purchase_date DESC')->fetchAll();
$success = flashGet('success');

$adminTitle = 'Manage Orders';
require __DIR__ . '/dry/admin-header.php';
?>

<h1 class="admin-page-title">Manage Orders</h1>
<?php if ($success): ?><div class="alert alert-success"><?php echo ($success); ?></div><?php endif; ?>

<div class="table-responsive admin-table">
    <table class="table table-sm mb-0">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Items</th>
                <th>Actions</th>
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
                    <!-- order id -->
                    <td>#<?php echo (int) $order['order_id']; ?></td>
                    <!-- username -->
                    <td><?php echo ($order['username']); ?></td>
                    <!-- Date -->
                    <td><?php echo (date('d M Y H:i', strtotime($order['purchase_date']))); ?></td>
                    <!-- status -->
                    <td>
                        <form method="POST" class="d-flex gap-1 align-items-center">
                            <input type="hidden" name="update_status" value="1">
                            <input type="hidden" name="order_id" value="<?php echo (int) $order['order_id']; ?>">
                            <select name="status" class="form-select form-select-sm" style="width:auto;">
                                <?php foreach (['Pending', 'Processing', 'Shipped', 'Completed'] as $st): ?>
                                    <option value="<?php echo $st; ?>" <?php echo $order['status'] === $st ? 'selected' : ''; ?>><?php echo $st; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-outline-dark btn-sm">Save</button>
                        </form>
                    </td>
                    <!-- Total Price -->
                    <td><?php echo formatPrice($order['total_price']); ?></td>
                    <!-- Item -->
                    <td>
                        <?php foreach ($items as $item): ?>
                            <div class="small"><?php echo ($item['product_name']); ?> (<?php echo ($item['size']); ?>) x<?php echo (int) $item['quantity']; ?></div>
                        <?php endforeach; ?>
                        <div class="small text-muted mt-1"><?php echo ($order['delivery_address']); ?></div>
                    </td>
                    <td>
                        <a href="./manage-order.php?delete=<?php echo (int) $order['order_id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete order?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if (count($orders) === 0): ?>
    <p class="text-muted mt-3">No orders yet.</p>
<?php endif; ?>

<p class="mt-3"><a href="./dashboard.php" class="btn btn-link btn-sm">&larr; Back</a></p>

<?php require __DIR__ . '/dry/admin-footer.php'; ?>