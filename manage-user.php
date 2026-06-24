<?php
require_once __DIR__ . '/dry/init.php';
requireAdmin();

// Delete user
if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    if ($deleteId !== (int) $_SESSION['user']['user_id']) {
        try {
            $db->beginTransaction();
            $orderIds = $db->prepare('SELECT order_id FROM orders WHERE user_id = :id');
            $orderIds->execute([':id' => $deleteId]);
            foreach ($orderIds->fetchAll() as $row) {
                $db->prepare('DELETE FROM order_products WHERE order_id = :oid')->execute([':oid' => $row['order_id']]);
            }
            $db->prepare('DELETE FROM orders WHERE user_id = :id')->execute([':id' => $deleteId]);
            $db->prepare('DELETE FROM users WHERE user_id = :id')->execute([':id' => $deleteId]);
            $db->commit();
            flashSet('success', 'User deleted.');
        } catch (Exception $e) {
            $db->rollBack();
            flashSet('error', 'Cannot delete user.');
        }
    }
    header('Location: ./manage-user.php');
    exit;
}

$users = $db->query('SELECT user_id, username, email, role, address, phone FROM users ORDER BY user_id')->fetchAll();
$success = flashGet('success');
$error = flashGet('error');

$adminTitle = 'Manage Users';
require __DIR__ . '/dry/admin-header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="admin-page-title mb-0">Manage Users</h1>
    <a href="./manage-user-add.php" class="btn btn-dark btn-sm">Add New User</a>
</div>

<?php if ($success): ?><div class="alert alert-success"><?php echo e($success); ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>

<div class="table-responsive admin-table">
    <table class="table table-sm mb-0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?php echo (int) $u['user_id']; ?></td>
                    <td><?php echo e($u['username']); ?></td>
                    <td><?php echo e($u['email']); ?></td>
                    <td><?php echo e($u['role']); ?></td>
                    <td><?php echo e($u['address'] ?? '—'); ?></td>
                    <td><?php echo e($u['phone'] ?? '—'); ?></td>
                    <td class="text-nowrap">
                        <a href="./manage-user-edit.php?id=<?php echo (int) $u['user_id']; ?>" class="btn btn-outline-secondary btn-sm">Edit</a>
                        <a href="./manage-user-changepwd.php?id=<?php echo (int) $u['user_id']; ?>" class="btn btn-outline-secondary btn-sm">Pwd</a>
                        <?php if ((int) $u['user_id'] !== (int) $_SESSION['user']['user_id']): ?>
                            <a href="./manage-user.php?delete=<?php echo (int) $u['user_id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this user?')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<p class="mt-3"><a href="./dashboard.php" class="btn btn-link btn-sm">&larr; Back to Dashboard</a></p>

<?php require __DIR__ . '/dry/admin-footer.php'; ?>