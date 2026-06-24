<?php
require_once __DIR__ . '/dry/init.php';

if (isLoggedIn()) {
    header('Location: ./index.php');
    exit;
}

$error = flashGet('error');
$pageTitle = 'Register';
require __DIR__ . '/dry/header.php';
?>

<div class="auth-wrap">
    <div class="auth-card">
        <h1 class="auth-title">REGISTER</h1>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo e($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="./register.php">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" minlength="6" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" minlength="6" required>
            </div>

            <button type="submit" class="btn btn-dark w-100">Create Account</button>
        </form>

        <p class="text-center mt-3 mb-0 small">
            Already have an account? <a href="./login-form.php">Login here</a>
        </p>
    </div>
</div>

<?php require __DIR__ . '/dry/footer.php'; ?>