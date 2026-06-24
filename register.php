<?php

/**
 * register.php — 处理注册
 */
require_once __DIR__ . '/dry/init.php';

if (isLoggedIn()) {
    header('Location: ./index.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if ($username === '' || $email === '' || $password === '') {
    flashSet('error', 'All fields are required.');
    header('Location: ./register-form.php');
    exit;
}

if ($password !== $confirm) {
    flashSet('error', 'Passwords do not match.');
    header('Location: ./register-form.php');
    exit;
}

if (strlen($password) < 6) {
    flashSet('error', 'Password must be at least 6 characters.');
    header('Location: ./register-form.php');
    exit;
}

// 检查 email / username 是否已存在
$check = $db->prepare('SELECT user_id FROM users WHERE email = :email OR username = :username LIMIT 1');
$check->execute([':email' => $email, ':username' => $username]);
if ($check->fetch()) {
    flashSet('error', 'Email or username already exists.');
    header('Location: ./register-form.php');
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$insert = $db->prepare('INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)');
$insert->execute([
    ':username' => $username,
    ':email' => $email,
    ':password' => $hash,
    ':role' => 'user',
]);

flashSet('success', 'Account created! Please login.');
header('Location: ./login-form.php');
exit;
