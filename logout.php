<?php

/**
 * logout.php — 清除 session 并登出
 */
require_once __DIR__ . '/dry/init.php';

$_SESSION = [];
session_destroy();

header('Location: ./index.php');
exit;
