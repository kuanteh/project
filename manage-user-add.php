<?php
require_once __DIR__ . '/dry/init.php';
requireAdmin();

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if ($username === '' || $email === '' || $password === '') {
        $error = 'All fields are required.';
    } elseif (!in_array($role, ['admin', 'user'], true)) {
        $error = 'Invalid role.';
    } else {
        $dup = $db->prepare('SELECT user_id FROM users WHERE email = :email OR username = :username LIMIT 1');
        $dup->execute([':email' => $email, ':username' => $username]);
        if ($dup->fetch()) {
            $error = 'Email or username already exists.';
        } else {
            $ins = $db->prepare('INSERT INTO users (username, email, password, role) VALUES (:u, :e, :p, :r)');
            $ins->execute([
                ':u' => $username,
                ':e' => $email,
                ':p' => password_hash($password, PASSWORD_DEFAULT),
                ':r' => $role,
            ]);
            flashSet('success', 'User added.');
            header('Location: ./manage-user.php');
            exit;
        }
    }
}

$adminTitle = 'Add User';
require __DIR__ . '/dry/admin-header.php';
?>

<h1 class="admin-page-title">Add New User</h1>
<div class="admin-form-card">
    <?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
    <form method="POST">
        <div class="mb-3"><label class="form-label">Username</label><input type="text" name="username" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select">
                <option value="user">user</option>
                <option value="admin">admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-dark">Add User</button>
        <a href="./manage-user.php" class="btn btn-link">Cancel</a>
    </form>
</div>

<?php require __DIR__ . '/dry/admin-footer.php'; ?>