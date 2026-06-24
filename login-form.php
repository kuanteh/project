<?php
require_once __DIR__ . '/dry/init.php';

if (isLoggedIn()) {
    header('Location: ./index.php');
    exit;
}

$error = flashGet('error');
$success = flashGet('success');
$return = $_GET['return'] ?? './index.php';
$pageTitle = 'Login';
require __DIR__ . '/dry/header.php';
?>

<div class="auth-wrap">
    <div class="auth-card">
        <h1 class="auth-title">LOGIN</h1>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo ($success); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo ($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="./login.php">
            <input type="hidden" name="return" value="<?php echo ($return); ?>">

            <div class="mb-3">
                <label class="form-label">Email or Username</label>
                <input type="text" name="login" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-dark w-100">Login</button>
        </form>

        <p class="text-center mt-3 mb-0 small">
            No account? <a href="./register-form.php">Register here</a>
        </p>
    </div>
</div>

<?php require __DIR__ . '/dry/footer.php'; ?>