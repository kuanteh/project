<?php
require_once __DIR__ . '/dry/init.php';
requireLogin();

$user = currentUser();
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($username === '' || $email === '') {
        $error = 'Username and email are required.';
    } else {
        $dup = $db->prepare('SELECT user_id FROM users WHERE (email = :email OR username = :username) AND user_id != :uid LIMIT 1');
        $dup->execute([':email' => $email, ':username' => $username, ':uid' => $user['user_id']]);
        if ($dup->fetch()) {
            $error = 'Username or email already taken.';
        } else {
            $upd = $db->prepare('UPDATE users SET username = :username, email = :email, address = :address, phone = :phone WHERE user_id = :uid');
            $upd->execute([
                ':username' => $username,
                ':email' => $email,
                ':address' => $address ?: null,
                ':phone' => $phone ?: null,
                ':uid' => $user['user_id'],
            ]);
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['address'] = $address;
            $_SESSION['user']['phone'] = $phone;
            flashSet('success', 'Profile updated successfully.');
            header('Location: ./profile.php');
            exit;
        }
    }
}

$pageTitle = 'Edit Profile';
require __DIR__ . '/dry/header.php';
?>

<div class="auth-wrap" style="max-width:560px;">
    <div class="auth-card">
        <h1 class="auth-title">EDIT PROFILE</h1>
        <?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo e($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo e($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3"><?php echo e($user['address'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?php echo e($user['phone'] ?? ''); ?>">
            </div>
            <button type="submit" class="btn btn-dark w-100">Save Changes</button>
            <a href="./profile.php" class="btn btn-link w-100 mt-2">Cancel</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/dry/footer.php'; ?>