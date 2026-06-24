<?php
require_once __DIR__ . '/dry/init.php';
requireAdmin();

$userId = (int) ($_GET['id'] ?? 0);
$stmt = $db->prepare('SELECT * FROM users WHERE user_id = :id');
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch();
if (!$user) {
    header('Location: ./manage-user.php');
    exit;
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'user';

    if ($username === '' || $email === '') {
        $error = 'Username and email required.';
    } else {
        $upd = $db->prepare('UPDATE users SET username = :u, email = :e, role = :r WHERE user_id = :id');
        $upd->execute([':u' => $username, ':e' => $email, ':r' => $role, ':id' => $userId]);
        flashSet('success', 'User updated.');
        header('Location: ./manage-user.php');
        exit;
    }
}

$adminTitle = 'Edit User';
require __DIR__ . '/dry/admin-header.php';
?>

<h1 class="admin-page-title">Edit User #<?php echo $userId; ?></h1>
<div class="admin-form-card">
    <?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
    <form method="POST">
        <div class="mb-3"><label class="form-label">Username</label><input type="text" name="username" class="form-control" value="<?php echo e($user['username']); ?>" required></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?php echo e($user['email']); ?>" required></div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select">
                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>user</option>
                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-dark">Save</button>
        <a href="./manage-user.php" class="btn btn-link">Cancel</a>
    </form>
</div>

<?php require __DIR__ . '/dry/admin-footer.php'; ?>