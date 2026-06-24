<?php
require_once __DIR__ . '/dry/init.php';
requireAdmin();

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
    $sizes = $_POST['sizes'] ?? [];
    $stocks = $_POST['stocks'] ?? [];

    if ($name === '' || $brand === '' || $price <= 0 || $category === '' || $image === '') {
        $error = 'Please fill all required fields.';
    } else {
        try {
            $db->beginTransaction();
            $ins = $db->prepare('INSERT INTO products (product_name, brand, price, category, image, description,
            washing_instruction, size_guide)
                VALUES (:name, :brand, :price, :cat, :img, :desc, :wash, :guide)');
            $ins->execute([
                ':name' => $name,
                ':brand' => $brand,
                ':price' => $price,
                ':cat' => $category,
                ':img' => $image,
                ':desc' => $description ?: null,
                ':wash' => $washing ?: null,
                ':guide' => $sizeGuide ?: null,
            ]);
            $productId = (int) $db->lastInsertId();

            $sizeIns = $db->prepare('INSERT INTO product_sizes (product_id, size, stock) VALUES (:pid, :size, :stock)');
            foreach ($sizes as $i => $size) {
                $size = trim($size);
                $stock = (int) ($stocks[$i] ?? 0);
                if ($size !== '') {
                    $sizeIns->execute([':pid' => $productId, ':size' => $size, ':stock' => $stock]);
                }
            }

            $db->commit();
            flashSet('success', 'Product added.');
            header('Location: ./manage-product.php');
            exit;
        /**
         * try catch 的 catch
         * try - try 的里面如果会有error
         * catch -如果真的有error 要怎样处理
         * 
         * exception $e 
         * - exception 代表所有类型的error(有异常)
         * - $e 就是一个 variable (记录这个error 的原因)
         * 
         * db->rollBack();
         * - 如果其他的能add 但是只有size 不能add 
         * - 是没有这串代码 你照样add product 是能的,但是size 的地方因为有error 就没有显示
         * - 如果你有这串代码 ,他就会取消你add 因为你有一个地方是有error
         * - 总结:rollBack() 会撤销刚才的操作 在database
         */
        } catch (Exception $e) {
            $db->rollBack();
            $error = 'Failed to add product.';
        }
    }
}

$adminTitle = 'Add Product';
require __DIR__ . '/dry/admin-header.php';
?>

<h1 class="admin-page-title">Add New Product</h1>
<div class="admin-form-card" style="max-width:800px;">
    <?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Product Name *</label><input type="text" name="product_name" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Brand *</label><input type="text" name="brand" class="form-control" required></div>
            <div class="col-md-4 mb-3"><label class="form-label">Price (RM) *</label><input type="number" step="0.01" name="price" class="form-control" required></div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Category *</label>
                <select name="category" class="form-select" required>
                    <option value="hoodie">hoodie</option>
                    <option value="t shirt">t shirt</option>
                    <option value="cap">cap</option>
                    <option value="accesories">accesories</option>
                </select>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label">Image Path *</label>
                <input type="text" name="image" class="form-control" placeholder="./image/productImage/your-image.jpg" required>
            </div>
            <div class="col-md-12 mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
            <div class="col-md-12 mb-3"><label class="form-label">Washing Instruction</label><textarea name="washing_instruction" class="form-control" rows="2"></textarea></div>
            <div class="col-md-12 mb-3"><label class="form-label">Size Guide (HTML allowed)</label><textarea name="size_guide" class="form-control" rows="4"></textarea></div>
        </div>

        <h2 class="h6 mt-3 mb-2">Sizes &amp; Stock</h2>
        <?php foreach (['S', 'M', 'L', 'XL'] as $idx => $defaultSize): ?>
            <div class="size-stock-row">
                <div><label class="form-label small">Size</label><input type="text" name="sizes[]" class="form-control" value="<?php echo $defaultSize; ?>"></div>
                <div><label class="form-label small">Stock</label><input type="number" name="stocks[]" class="form-control" value="0" min="0"></div>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-dark mt-3">Add Product</button>
        <a href="./manage-product.php" class="btn btn-link">Cancel</a>
    </form>
</div>

<?php require __DIR__ . '/dry/admin-footer.php'; ?>