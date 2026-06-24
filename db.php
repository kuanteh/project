<?php

/**
 * db.php — 数据库连接文件
 * 整个网站只需要 require 一次，就能用 $db 来查询 MySQL
 */
$db = new PDO(
    "mysql:host=localhost;dbname=b18_sharqo;charset=utf8mb4",
    "root",
    ""
);

