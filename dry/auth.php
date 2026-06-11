<?php

/**
 * auth.php — 登录状态 & 权限检查
 */

function isLoggedIn(): bool
{
    return isset($_SESSION['user']) && !empty($_SESSION['user']['user_id']);
}

function isAdmin(): bool
{
    return isLoggedIn() && ($_SESSION['user']['role'] ?? '') === 'admin';
}

function currentUser(): ?array
{
    return isLoggedIn() ? $_SESSION['user'] : null;
}

/** 必须登录，否则跳去 login-form.php */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        $return = urlencode($_SERVER['REQUEST_URI'] ?? 'index.php');
        header('Location: ./login-form.php?return=' . $return);
        exit;
    }
}

/** 必须 admin，否则跳去首页 */
function requireAdmin(): void
{
    requireLogin();
    if (!isAdmin()) {
        header('Location: ./index.php');
        exit;
    }
}
