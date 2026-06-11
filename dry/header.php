<?php

/**
 * header.php — 全站导航栏（非首页通用版）
 * 用法：$pageTitle = 'Product'; $bodyClass = 'page-light'; require header.php;
 */
$pageTitle = $pageTitle ?? 'SHARQO';
$bodyClass = $bodyClass ?? 'page-light';
$profileUrl = isLoggedIn() ? './profile.php' : './login-form.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> | SHARQO</title>
    <link rel="stylesheet" href="./css/googleFonts.css">
    <link rel="stylesheet" href="./css/site.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bungee&display=swap" rel="stylesheet">
</head>

<body class="<?php echo e($bodyClass); ?>">

    <header class="site-header">
        <nav class="navbar navbar-expand-lg site-navbar">
            <div class="container">
                <a class="navbar-brand bungee-regular" href="./index.php">SHARQO</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="siteNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="./catalog.php">Shop</a></li>
                        <li class="nav-item"><a class="nav-link" href="./catalog.php?sort=new">New</a></li>
                        <li class="nav-item"><a class="nav-link" href="./about.php">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="./contact.php">Contact</a></li>
                        <?php if (isAdmin()): ?>
                            <li class="nav-item"><a class="nav-link text-danger" href="./dashboard.php">Admin</a></li>
                        <?php endif; ?>
                    </ul>
                    <div class="d-flex align-items-center gap-3">
                        <a href="<?php echo $profileUrl; ?>" class="site-icon-link" title="Account">
                            <i class="bi bi-person-circle"></i>
                        </a>
                        <?php if (isLoggedIn()): ?>
                            <a href="./logout.php" class="site-icon-link" title="Logout">
                                <i class="bi bi-box-arrow-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main class="site-main">