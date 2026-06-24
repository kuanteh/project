<?php
requireAdmin();
$adminTitle = $adminTitle ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($adminTitle); ?> | SHARQO Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./css/admin.css">
</head>

<body class="admin-body">
    <nav class="navbar navbar-dark admin-navbar">
        <div class="container">
            <a class="navbar-brand fw-bold" href="./dashboard.php">SHARQO Admin</a>
            <div class="d-flex gap-2">
                <a href="./index.php" class="btn btn-outline-light btn-sm">View Site</a>
                <a href="./logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container admin-container">