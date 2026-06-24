<?php
require_once __DIR__ . '/dry/init.php';
requireAdmin();

$userId = (int) ($_GET['id'] ?? 0);
$stmt = $db->prepare('SELECT username FROM users WHERE user_id = :id');
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch();
if (!$user) {
    header('Location: ./manage-user.php');
    exit;
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    if (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $upd = $db->prepare('UPDATE users SET password = :p WHERE user_id = :id');
        $upd->execute([':p' => password_hash($password, PASSWORD_DEFAULT), ':id' => $userId]);
        flashSet('success', 'Password reset for ' . $user['username']);
        header('Location: ./manage-user.php');
        exit;
    }
}

$adminTitle = 'Reset Password';
require __DIR__ . '/dry/admin-header.php';
?>

<h1 class="admin-page-title">Reset Password — <?php echo e($user['username']); ?></h1>
<div class="admin-form-card">
    <?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
    <form method="POST">
        <div class="mb-3"><label class="form-label">New Password</label><input type="password" name="password" class="form-control" minlength="6" required></div>
        <button type="submit" class="btn btn-dark">Reset Password</button>
        <a href="./manage-user.php" class="btn btn-link">Cancel</a>
    </form>
</div>

<?php require __DIR__ . '/dry/admin-footer.php'; ?>