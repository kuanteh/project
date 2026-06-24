<?php
require_once __DIR__ . '/dry/init.php';
// header.php 的 <title>
$pageTitle = 'Privacy Policy';
require __DIR__ . '/dry/header.php';
?>
<div class="content-page">
    <h1>Privacy Policy</h1>
    <p>SHARQO respects your privacy. This policy explains how we collect and use your personal information.</p>
    <h2 class="h5 mt-4">Information We Collect</h2>
    <p>When you register or place an order, we collect your username, email, delivery address, and phone number.</p>
    <h2 class="h5 mt-4">How We Use Your Data</h2>
    <p>Your data is used to process orders, manage your account, and communicate about your purchases. We do not sell your personal information to third parties.</p>
    <h2 class="h5 mt-4">Security</h2>
    <p>Passwords are stored using secure hashing. Only authorised admin staff can access order and user data.</p>
</div>
<?php require __DIR__ . '/dry/footer.php'; ?>