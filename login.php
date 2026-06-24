<?php

/**
 * login.php — 处理登录表单 POST
 * 支持用 email 或 username 登录
 */
require_once __DIR__ . '/dry/init.php';

if (isLoggedIn()) {
    header('Location: ./index.php');
    exit;
}

$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';
$return = $_POST['return'] ?? './index.php';

if ($login === '' || $password === '') {
    flashSet('error', 'Please enter email/username and password.');
    header('Location: ./login-form.php');
    exit;
}

$query = 'SELECT * FROM users WHERE email = :login OR username = :login LIMIT 1';
$stmt = $db->prepare($query);
$stmt->execute([':login' => $login]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    flashSet('error', 'Invalid email/username or password.');
    header('Location: ./login-form.php?return=' . urlencode($return));
    exit;
}

// 存入 session（不存 password）
unset($user['password']);
$_SESSION['user'] = $user;

// admin 登录后可以去 dashboard，普通用户回原来页面
if ($user['role'] === 'admin' && $return === './index.php') {
    header('Location: ./dashboard.php');
} else {
    header('Location: ' . $return);
}
exit;
