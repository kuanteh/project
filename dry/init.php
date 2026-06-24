<?php

/**
 * init.php — 每个页面开头 require 这个文件
 * 负责：开启 session + 连接数据库 + 加载通用函数
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/function.php';
require_once __DIR__ . '/auth.php';
