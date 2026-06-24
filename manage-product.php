<?php
require_once __DIR__ . '/dry/init.php';
requireAdmin();

if (isset($_GET['delete'])) {
    // 将 delete change to integer
    $pid = (int) $_GET['delete'];
    $inOrders = $db->prepare('SELECT COUNT(*) FROM order_products WHERE product_id = :id');
    // execute，给 :id change to商品 ID
    $inOrders->execute([':id' => $pid]);
    if ((int) $inOrders->fetchColumn() > 0) {
        flashSet('error', 'Cannot delete — product exists in orders.');
    } else {
        // delete the product_size data
        $db->prepare('DELETE FROM product_sizes WHERE product_id = :id')->execute([':id' => $pid]);
        // delete product data
        $db->prepare('DELETE FROM products WHERE product_id = :id')->execute([':id' => $pid]);
        flashSet('success', 'Product deleted.');
    }
    header('Location: ./manage-product.php');
    exit;
}

$sql = 'SELECT p.*, COALESCE(SUM(ps.stock), 0) AS total_stock
        FROM products p
        LEFT JOIN product_sizes ps ON ps.product_id = p.product_id
        GROUP BY p.product_id
        ORDER BY p.product_id DESC';
$products = $db->query($sql)->fetchAll();
$success = flashGet('success');
$error = flashGet('error');

$adminTitle = 'Manage Products';
require __DIR__ . '/dry/admin-header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="admin-page-title mb-0">Manage Products</h1>
    <a href="./manage-product-add.php" class="btn btn-dark btn-sm">Add New Product</a>
</div>

<?php if ($success): ?><div class="alert alert-success"><?php echo e($success); ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>

<div class="table-responsive admin-table">
    <table class="table table-sm mb-0 align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?php echo (int) $p['product_id']; ?></td>
                    <td><img src="<?php echo e($p['image']); ?>" alt=""></td>
                    <td><?php echo e($p['product_name']); ?></td>
                    <td><?php echo e($p['brand']); ?></td>
                    <td><?php echo formatPrice($p['price']); ?></td>
                    <td><?php echo (int) $p['total_stock']; ?></td>
                    <td><?php echo e($p['category']); ?></td>
                    <td class="text-nowrap">
                        <a href="./manage-product-edit.php?id=<?php echo (int) $p['product_id']; ?>" class="btn btn-outline-secondary btn-sm">Edit</a>
                        <a href="./manage-product.php?delete=<?php echo (int) $p['product_id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete product?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<p class="mt-3"><a href="./dashboard.php" class="btn btn-link btn-sm">&larr; Back</a></p>

<?php require __DIR__ . '/dry/admin-footer.php'; ?>