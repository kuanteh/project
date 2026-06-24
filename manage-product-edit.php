<?php
require_once __DIR__ . '/dry/init.php';
requireAdmin();

$productId = (int) ($_GET['id'] ?? 0);
$stmt = $db->prepare('SELECT * FROM products WHERE product_id = :id');
$stmt->execute([':id' => $productId]);
$product = $stmt->fetch();
if (!$product) {
    header('Location: ./manage-product.php');
    exit;
}

$sizes = getProductSizes($db, $productId);
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['product_name'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $washing = trim($_POST['washing_instruction'] ?? '');
    $sizeGuide = trim($_POST['size_guide'] ?? '');
    $sizeIds = $_POST['size_ids'] ?? [];
    $sizeNames = $_POST['sizes'] ?? [];
    $stocks = $_POST['stocks'] ?? [];

    if ($name === '' || $brand === '' || $price <= 0 || $category === '' || $image === '') {
        $error = 'Please fill all required fields.';
    } else {
        try {
            $db->beginTransaction();

            $upd = $db->prepare('UPDATE products SET product_name=:name, brand=:brand, price=:price, category=:cat, image=:img,
                description=:desc, washing_instruction=:wash, size_guide=:guide WHERE product_id=:id');
            $upd->execute([
                ':name' => $name,
                ':brand' => $brand,
                ':price' => $price,
                ':cat' => $category,
                ':img' => $image,
                ':desc' => $description ?: null,
                ':wash' => $washing ?: null,
                ':guide' => $sizeGuide ?: null,
                ':id' => $productId,
            ]);

            $updSize = $db->prepare('UPDATE product_sizes SET size = :size, stock = :stock WHERE size_id = :sid AND product_id = :pid');
            $insSize = $db->prepare('INSERT INTO product_sizes (product_id, size, stock) VALUES (:pid, :size, :stock)');

            foreach ($sizeNames as $i => $sizeName) {
                $sizeName = trim($sizeName);
                $stock = (int) ($stocks[$i] ?? 0);
                $sid = (int) ($sizeIds[$i] ?? 0);
                if ($sizeName === '') continue;
                if ($sid > 0) {
                    $updSize->execute([':size' => $sizeName, ':stock' => $stock, ':sid' => $sid, ':pid' => $productId]);
                } else {
                    $insSize->execute([':pid' => $productId, ':size' => $sizeName, ':stock' => $stock]);
                }
            }

            $db->commit();
            flashSet('success', 'Product updated.');
            header('Location: ./manage-product.php');
            exit;
        } catch (Exception $e) {
            $db->rollBack();
            $error = 'Failed to update product.';
        }
    }

    $stmt->execute([':id' => $productId]);
    $product = $stmt->fetch();
    $sizes = getProductSizes($db, $productId);
}

// 确保有 S/M/L/XL 四行
$sizeMap = [];
foreach ($sizes as $s) {
    $sizeMap[$s['size']] = $s;
}
$defaultSizes = ['S', 'M', 'L', 'XL'];
$editSizes = [];
foreach ($defaultSizes as $ds) {
    $editSizes[] = $sizeMap[$ds] ?? ['size_id' => 0, 'size' => $ds, 'stock' => 0];
}

$adminTitle = 'Edit Product';
require __DIR__ . '/dry/admin-header.php';
?>

<h1 class="admin-page-title">Edit Product #<?php echo $productId; ?></h1>
<div class="admin-form-card" style="max-width:800px;">
    <?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Product Name *</label><input type="text" name="product_name" class="form-control" value="<?php echo e($product['product_name']); ?>" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Brand *</label><input type="text" name="brand" class="form-control" value="<?php echo e($product['brand']); ?>" required></div>
            <div class="col-md-4 mb-3"><label class="form-label">Price *</label><input type="number" step="0.01" name="price" class="form-control" value="<?php echo e($product['price']); ?>" required></div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Category *</label>
                <select name="category" class="form-select" required>
                    <?php foreach (['top','bottom','accessories','hoodie', 't shirt', 'cap'] as $cat): ?>
                        <option value="<?php echo e($cat); ?>" <?php echo $product['category'] === $cat ? 'selected' : ''; ?>><?php echo e($cat); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-12 mb-3"><label class="form-label">Image Path *</label><input type="text" name="image" class="form-control" value="<?php echo e($product['image']); ?>" required></div>
            <div class="col-md-12 mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"><?php echo e($product['description'] ?? ''); ?></textarea></div>
            <div class="col-md-12 mb-3"><label class="form-label">Washing Instruction</label><textarea name="washing_instruction" class="form-control" rows="2"><?php echo e($product['washing_instruction'] ?? ''); ?></textarea></div>
            <div class="col-md-12 mb-3"><label class="form-label">Size Guide</label><textarea name="size_guide" class="form-control" rows="4"><?php echo e($product['size_guide'] ?? ''); ?></textarea></div>
        </div>

        <h2 class="h6 mt-3 mb-2">Sizes &amp; Stock</h2>
        <?php foreach ($editSizes as $s): ?>
            <div class="size-stock-row">
                <input type="hidden" name="size_ids[]" value="<?php echo (int) $s['size_id']; ?>">
                <div><label class="form-label small">Size</label><input type="text" name="sizes[]" class="form-control" value="<?php echo e($s['size']); ?>"></div>
                <div><label class="form-label small">Stock</label><input type="number" name="stocks[]" class="form-control" value="<?php echo (int) $s['stock']; ?>" min="0"></div>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-dark mt-3">Save Changes</button>
        <a href="./manage-product.php" class="btn btn-link">Cancel</a>
    </form>
</div>

<?php require __DIR__ . '/dry/admin-footer.php'; ?>





