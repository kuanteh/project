<?php

/**
 * functions.php — 全站共用的辅助函数
 */

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatPrice($price): string
{
    return 'RM ' . number_format((float) $price, 2);
}

function getProductTotalStock(PDO $db, int $productId): int
{
    $stmt = $db->prepare('SELECT COALESCE(SUM(stock), 0) AS total FROM product_sizes WHERE product_id = :id');
    $stmt->execute([':id' => $productId]);
    return (int) $stmt->fetchColumn();
}

function getProductSizes(PDO $db, int $productId): array
{
    $stmt = $db->prepare('SELECT size_id, size, stock FROM product_sizes WHERE product_id = :id ORDER BY FIELD(size, "S","M","L","XL"), size');
    $stmt->execute([':id' => $productId]);
    return $stmt->fetchAll();
}

function flashSet(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function flashGet(string $key): ?string
{
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }
    $msg = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $msg;
}
