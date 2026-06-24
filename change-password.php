<?php
require_once __DIR__ . '/dry/init.php';
requireLogin();

// 拿current user 的deatail 放在 variable user
$user = currentUser();
$error = null;

// user 用post submit change passoword
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $newPass = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $stmt = $db->prepare('SELECT password FROM users WHERE user_id = :uid');
    $stmt->execute([':uid' => $user['user_id']]);
    $row = $stmt->fetch();

    if (!password_verify($current, $row['password'])) {
        $error = 'Current password is incorrect.';
    } elseif (strlen($newPass) < 6) {
        $error = 'New password must be at least 6 characters.';
    } elseif ($newPass !== $confirm) {
        $error = 'New passwords do not match.';
    } else {
        $hash = password_hash($newPass, PASSWORD_DEFAULT);
        $upd = $db->prepare('UPDATE users SET password = :pwd WHERE user_id = :uid');
        $upd->execute([':pwd' => $hash, ':uid' => $user['user_id']]);
        flashSet('success', 'Password changed successfully.');
        header('Location: ./profile.php');
        exit;
    }
}

$pageTitle = 'Change Password';
require __DIR__ . '/dry/header.php';
?>

<div class="auth-wrap">
    <div class="auth-card">
        <h1 class="auth-title">CHANGE PASSWORD</h1>
        <?php if ($error): ?><div class="alert alert-danger"><?php echo ($error); ?></div><?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control" minlength="6" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" minlength="6" required>
            </div>
            <button type="submit" class="btn btn-dark w-100">Update Password</button>
            <a href="./profile.php" class="btn btn-link w-100 mt-2">Cancel</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/dry/footer.php'; ?>